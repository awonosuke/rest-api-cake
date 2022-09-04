<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use App\Library\Response;
use Cake\Http\Exception\BadRequestException;

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
     * @return \Cake\Http\Response
     * @throws \Exception
     */
    public function signupApi(): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $new_user = $this->Users->newEntity($this->request->getData());
        if ($this->Users->save($new_user)) {
            $response = new Response(200, $request_url, $new_user);
            return $this->renderJson($response->formatResponse());
        }

        // 保存に失敗した場合エラー返す
        $response = new Response(400, $request_url, (object) $new_user->getErrors());
        return $this->renderJson($response->formatResponse());
    }

//    public function loginApi()
//    {
//        $this->request->allowMethod('post');
//
//        $result = $this->Authentication->getResult();
//        if ($result->isValid()) {
//            $login_user = $this->Authentication->getIdentity();
//            $response = new Response(200, ['id' => '']);
//        }
//
//        $response = new Response(401, []);
//    }

//    public function logoutApi()
//    {
//        $this->request->allowMethod('post');
//
//        $result = $this->Authentication->getResult();
//        if ($result->isValid()) {
//            $this->Authentication->logout();
//        }
//
//        $response = new Response(200, 'Logout complete');
//        return $this->renderJson($response);
//    }

    /**
     * @param int $user_id
     * @return \Cake\Http\Response
     * @throws \Exception
     */
    public function resignApi(int $user_id): \Cake\Http\Response
    {
        $request_url = $this->request->getRequestTarget();

        $user = $this->Users->get($user_id); // if user does not find, then throw RecordNotFoundException
        if ($this->Users->delete($user)) {
            $response = new Response(200, $request_url, (object) ['message' => 'Resign snippetbox']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(200, $request_url, (object) ['message' => 'Failed resign snippetbox']);
        return $this->renderJson($response->formatResponse());
    }
}
