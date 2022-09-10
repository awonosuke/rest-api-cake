<?php
declare(strict_types=1);

namespace App\Controller;

use App\Error\Exception\RecordSaveErrorException;
use App\Error\Exception\ValidationErrorException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use App\Library\Response;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\UnauthorizedException;
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
        $this->allowHttpRequestMethod(HTTP_METHOD_POST);

        $post_data = $this->request->getData();
        // 新規ユーザー登録で管理者ユーザーは作らせない（強制的に一般ユーザーにする）
        $post_data['role'] = 'user';

        $new_user = $this->Users->newEntity($post_data);
        if ($new_user->getErrors()) throw new ValidationErrorException($new_user);

        if ($this->Users->save($new_user)) {
            $signup_user = $this->Users->find()
                ->select(['id', 'email', 'user_name', 'created'])
                ->where(['id' => $new_user->id])
                ->first();
            if (empty($signup_user)) throw new RecordNotFoundException();

            $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Signup complete', 'user' => $signup_user]);
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
        $this->allowHttpRequestMethod(HTTP_METHOD_POST);

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $privateKey = file_get_contents(PRIVATE_KEY_PATH);
            $payload = [
                'iss' => 'rest-api-cake',
                'sub' => $this->loginUser['id'],
                'exp' => time() + JWT_EXPIRES // token expires in 1 day
            ];
            $jwt_token = JWT::encode($payload, $privateKey, JWT_ALG);

            $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Login complete', 'token' => $jwt_token]);
            return $this->renderJson($response->formatResponse());
        }

        throw new UnauthorizedException();
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response
     */
    public function logoutApi(): \Cake\Http\Response
    {
        $this->allowHttpRequestMethod(HTTP_METHOD_POST);

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $this->Authentication->logout();
        }

        $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Logout complete']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * Resign method: Delete a user
     *
     * @return \Cake\Http\Response
     */
    public function resignApi(): \Cake\Http\Response
    {
        $this->allowHttpRequestMethod(HTTP_METHOD_POST);

        $user = $this->Users->find()->where(['id' => $this->loginUser['id']])->first();
        if (empty($user)) throw new RecordNotFoundException();

        // rootユーザーの退会禁止
        if ($user['email'] === 'root@example.com') throw new BadRequestException();

        if ($this->Users->delete($user)) {
            $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Resign snippetbox']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(StatusOK, $this->requestUrl, (object) ['message' => 'Failed resign snippetbox']);
        return $this->renderJson($response->formatResponse());
    }
}
