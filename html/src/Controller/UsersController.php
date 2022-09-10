<?php
declare(strict_types=1);

namespace App\Controller;

use App\Error\Exception\RecordSaveErrorException;
use App\Error\Exception\ValidationErrorException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use App\Library\Response;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['signupApi', 'loginApi']);
    }

    /**
     * Signup method: Create a new user
     *
     * @return \Cake\Http\Response
     */
    public function signupApi(): \Cake\Http\Response
    {
        if (!$this->request->is(HTTP_METHOD_POST)) throw new MethodNotAllowedException(HTTP_METHOD_POST);

        $request_url = $this->request->getRequestTarget();

        $new_user = $this->Users->newEntity($this->request->getData());
        if ($new_user->getErrors()) throw new ValidationErrorException($new_user);

        if ($this->Users->save($new_user)) {
            $signup_user = $this->Users->find()
                ->select(['id', 'email', 'user_name', 'created'])
                ->where(['id' => $new_user->id])
                ->first();
            if (empty($signup_user)) throw new RecordNotFoundException();

            $response = new Response(StatusOK, $request_url, (object) ['message' => 'Signup complete', 'user' => $signup_user]);
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
        if (!$this->request->is(HTTP_METHOD_POST)) throw new MethodNotAllowedException(HTTP_METHOD_POST);

        $request_url = $this->request->getRequestTarget();

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $privateKey = file_get_contents(PRIVATE_KEY_PATH);
            $payload = [
                'iss' => 'rest-api-cake',
                'sub' => $this->loginUser['id'],
                'exp' => time() + JWT_EXPIRES // token expires in 1 hour
            ];

            $jwt_token = JWT::encode($payload, $privateKey, JWT_ALG);

            $response = new Response(StatusOK, $request_url, (object) ['message' => 'Login complete', 'token' => $jwt_token]);
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
        if (!$this->request->is(HTTP_METHOD_POST)) throw new MethodNotAllowedException(HTTP_METHOD_POST);

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
     * @return \Cake\Http\Response
     */
    public function resignApi(): \Cake\Http\Response
    {
        if (!$this->request->is(HTTP_METHOD_POST)) throw new MethodNotAllowedException(HTTP_METHOD_POST);

        $request_url = $this->request->getRequestTarget();

        $user = $this->Users->find()->where(['id' => $this->loginUser['id']])->first();
        if (empty($user)) throw new RecordNotFoundException();

        // rootユーザーの退会禁止
        if ($user['email'] === 'root@example.com') throw new BadRequestException();

        if ($this->Users->delete($user)) {
            $response = new Response(StatusOK, $request_url, (object) ['message' => 'Resign snippetbox']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(StatusOK, $request_url, (object) ['message' => 'Failed resign snippetbox']);
        return $this->renderJson($response->formatResponse());
    }
}
