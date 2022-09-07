<?php
declare(strict_types=1);

namespace App\Controller;

use App\Error\Exception\RecordSaveErrorException;
use App\Error\Exception\ValidationErrorException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use App\Library\Response;
use Cake\Http\Exception\ForbiddenException;
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
            $login_user = $result->getData('id');
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
     * @param int $target_user_id
     * @return \Cake\Http\Response
     */
    public function resignApi(int $target_user_id): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        // 対象ユーザーとログインユーザーが一致するか判定
        if ($this->isMatchTargetAndLoginUser($target_user_id)) throw new ForbiddenException();

        $user = $this->Users->find()->where(['id' => $target_user_id])->first();
        if (empty($user)) throw new RecordNotFoundException();

        if ($this->Users->delete($user)) {
            $response = new Response(StatusOK, $request_url, (object) ['message' => 'Resign snippetbox']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(StatusOK, $request_url, (object) ['message' => 'Failed resign snippetbox']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * Identification method: Compare target user id and login user id
     *
     * @param int $target_user_id
     * @return bool
     */
    private function isMatchTargetAndLoginUser(int $target_user_id): bool
    {
        $login_user = $this->Authentication->getResult()->getData();
        return $target_user_id === $login_user['id'];
    }
}
