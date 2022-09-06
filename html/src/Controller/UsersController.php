<?php
declare(strict_types=1);

namespace App\Controller;

use App\Error\Exception\RecordSaveErrorException;
use App\Error\Exception\ValidationErrorException;
use Cake\Event\EventInterface;
use App\Library\Response;
use Firebase\JWT\JWT;

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
        if ($new_user->getErrors()) throw new ValidationErrorException($new_user);

        if ($this->Users->save($new_user)) {
            $response = new Response(StatusOK, $request_url, $new_user);
            return $this->renderJson($response->formatResponse());
        }

        // 保存に失敗した時、例外を投げる
        throw new RecordSaveErrorException();
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
            $privateKey = file_get_contents(CONFIG . '/jwt.key');
            $login_user = $result->getData();
            $payload = [
                'iss' => 'rest-api',
                'sub' => $login_user->id,
                'exp' => time() + 60,
            ];

            $jwt_token = [
                'token' => JWT::encode($payload, $privateKey, 'RS256'),
            ];

            $response = new Response(StatusOK, $request_url, (object) $jwt_token);
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
