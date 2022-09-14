<?php
declare(strict_types=1);

namespace App\Controller;

use App\Error\Exception\RecordSaveErrorException;
use App\Error\Exception\ValidationErrorException;
use App\Library\Response;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Snippets Controller
 *
 * @method \App\Model\Entity\Snippet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SnippetsController extends AppController
{
    /**
     * Register method: Create a new snippet
     *
     * @return \Cake\Http\Response
     * @throws \Exception
     */
    public function createSnippetApi(): \Cake\Http\Response
    {
        $this->allowHttpRequestMethod(HTTP_METHOD_POST);

        $snippet_replica = array_merge(['user_id' => $this->loginUser['id']], $this->request->getData());
        $new_snippet = $this->Snippets->newEntity($snippet_replica);
        if ($new_snippet->getErrors()) throw new ValidationErrorException($new_snippet);

        if ($this->Snippets->save($new_snippet)) {
            $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Create a new snippet', 'snippet' => $this->removeUserIdFromSnippet($new_snippet)]);
            return $this->renderJson($response->formatResponse());
        }

        // 保存に失敗した時、例外を投げる
        throw new RecordSaveErrorException();
    }

    /**
     * @param object $snippet
     * @return object
     */
    private function removeUserIdFromSnippet(object $snippet): object
    {
        return (object) array(
            'id' => $snippet->id,
            'content' => $snippet->content,
            'expire' => $snippet->expire,
            'created' => $snippet->created
        );
    }

    /**
     * Get method: Select a snippet
     *
     * @param int $snippet_id
     * @return \Cake\Http\Response
     * @throws \Exception
     */
    public function getSnippetApi(int $snippet_id): \Cake\Http\Response
    {
        $this->allowHttpRequestMethod(HTTP_METHOD_GET);

        $snippet = $this->Snippets->findExistSnippet($snippet_id, $this->loginUser['id'])->first();
        if (empty($snippet)) throw new RecordNotFoundException();

        $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Get a snippet', 'snippet' => $snippet]);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * Find ALL active record method: Select all active snippet
     *
     * @return \Cake\Http\Response
     * @throws \Exception
     */
    public function allSnippetApi(): \Cake\Http\Response
    {
        $this->allowHttpRequestMethod(HTTP_METHOD_GET);

        $all_snippet = $this->Snippets->findAllExistSnippet($this->loginUser['id']);
        if ($all_snippet->count() === 0) {
            $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Snippet Not Found']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Get all snippet', 'snippet' => $all_snippet]);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * Find ALL expired record method: Select all expired snippet
     *
     * @return \Cake\Http\Response
     * @throws \Exception
     */
    public function allExpiredSnippetApi(): \Cake\Http\Response
    {
        $this->allowHttpRequestMethod(HTTP_METHOD_GET);

        $all_expired_snippet = $this->Snippets->findAllExpiredSnippet($this->loginUser['id']);
        if (empty($all_expired_snippet)) {
            $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Expired Snippet Not Found']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Get all expired snippet', 'snippet' => $all_expired_snippet]);
        return $this->renderJson($response->formatResponse());
    }
}
