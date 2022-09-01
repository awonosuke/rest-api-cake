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
    public function initialize(): void
    {
        parent::initialize();
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

//        $this->Authentication->allowUnauthenticated(['login', 'signup]);
    }

    /**
     * Signup method: Create a new user
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function signupApi()
    {
        // POST以外は405エラー(with `Allow`ヘッダー)
        $this->request->allowMethod('post');

        $new_user = $this->Users->newEntity($this->request->getData());
        if ($this->Users->save($new_user)) {
            $response = new Response(200, $new_user);
            return $this->renderJson($response->formatResponse());
        }

        // 保存に失敗した場合エラー返す
        $response = new Response(400, $new_user->getErrors());
        return $this->renderJson($response->formatResponse());
    }

    public function loginApi()
    {
        $this->request->allowMethod('post');

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $login_user = $this->Authentication->getIdentity();
            $response = new Response(200, ['id' => '']);
        }

        $response = new Response(401, []);
    }

    public function logoutApi()
    {
        $this->request->allowMethod('post');

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $this->Authentication->logout();
        }

        $response = new Response(200, 'Logout complete');
        return $this->renderJson($response);
    }
}
