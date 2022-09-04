<?php
declare(strict_types=1);

namespace App\Controller;

use App\Library\Response;

/**
 * Snippets Controller
 *
 * @method \App\Model\Entity\Snippet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SnippetsController extends AppController
{
    /**
     * @return \Cake\Http\Response
     */
    public function createSnippetApi(): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $new_snippet = $this->Snippets->newEntity($this->request->getData());
        if ($this->Snippets->save($new_snippet)) {
            $response = new Response(200, $request_url, $new_snippet);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(400, $request_url, $new_snippet->getErrors());
        return $this->renderJson($response->formatResponse());
    }

    /**
     * @param int $snippet_id
     * @return \Cake\Http\Response
     */
    public function getSnippetApi(int $snippet_id): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $snippet = $this->Snippets->get($snippet_id); // if snippet does not find, then throw RecordNotFoundException

        $response = new Response(200, $request_url, $snippet);
        return $this->renderJson($response->formatResponse());
    }

    public function allSnippetApi(): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $all_snippet = $this->Snippets->findAllExistSnippet();
        if (empty($all_snippet)) {
            $response = new Response(200, $request_url, ['message' => 'Snippet Not Found']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(200, $request_url, $all_snippet);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * @return \Cake\Http\Response
     */
    public function allExpiredSnippetApi(): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $all_expire_snippet = $this->Snippets->findAllExpiredSnippet();
        if (empty($all_expire_snippet)) {
            $response = new Response(200, $request_url, ['message' => 'Expired Snippet Not Found']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(200, $request_url, $all_expire_snippet);
        return $this->renderJson($response->formatResponse());
    }
}
