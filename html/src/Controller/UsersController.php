<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use App\Library\Response;

/**
 * Users Controller
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        $this->Authentication->addUnauthenticatedActions(['signupApi', 'loginApi']);
    }

    /**
     * Signup method: Create a new user
     *
     * @return \Cake\Http\Response
     */
    public function signupApi(): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $new_user = $this->Users->newEntity($this->request->getData());
        if ($this->Users->save($new_user)) {
            $response = new Response(StatusOK, $request_url, $new_user);
            return $this->renderJson($response->formatResponse());
        }

        // 保存に失敗した場合エラー返す
        $response = new Response(StatusBadRequest, $request_url, (object) $new_user->getErrors());
        return $this->renderJson($response->formatResponse());
    }

    /**
     * Login method: Authenticate user
     *
     * @return \Cake\Http\Response
     */
    public function loginApi(): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $login_user = $this->Authentication->getIdentity();

            $response = new Response(StatusOK, $request_url, $login_user);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(StatusUnauthorized, $request_url, (object) ['message' => 'Unauthorized']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response
     */
    public function logoutApi(): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $this->Authentication->logout();
        }

        $response = new Response(StatusOK, $request_url, (object) ['message' => 'Logout complete']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * Resign method: Delete a user
     *
     * @param int $user_id
     * @return \Cake\Http\Response
     */
    public function resignApi(int $user_id): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $user = $this->Users->get($user_id); // if user does not find, then throw RecordNotFoundException
        if ($this->Users->delete($user)) {
            $response = new Response(StatusOK, $request_url, (object) ['message' => 'Resign snippetbox']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(StatusOK, $request_url, (object) ['message' => 'Failed resign snippetbox']);
        return $this->renderJson($response->formatResponse());
    }
}
