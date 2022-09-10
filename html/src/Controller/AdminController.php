<?php
declare(strict_types=1);

namespace App\Controller;

use App\Library\Response;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\MethodNotAllowedException;

/**
 * Admin Controller
 */
class AdminController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Users = $this->fetchTable('Users');
    }

    /**
     * @return bool
     */
    private function isAdmin(): bool
    {
        return ($this->loginUser['role'] === 'admin');
    }

    /**
     * Find ALL record method* Select all user
     *
     * @return \Cake\Http\Response
     */
    public function allUserApi(): \Cake\Http\Response
    {
        if (!$this->request->is(HTTP_METHOD_GET)) throw new MethodNotAllowedException(HTTP_METHOD_GET);
        if (!$this->isAdmin()) throw new ForbiddenException();

        $request_url = $this->request->getRequestTarget();

        $all_user = $this->Users->find()->all();
        if (empty($all_user)) {
            $response = new Response(StatusOK, $request_url, (object) ['message' => 'User Not Found']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(StatusOK, $request_url, (object) ['message' => 'Get all user', 'user' => $all_user]);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * Forced resign target user
     *
     * @param int $user_id
     * @return \Cake\Http\Response
     */
    public function forcedResignApi(int $user_id): \Cake\Http\Response
    {
        if (!$this->request->is(HTTP_METHOD_POST)) throw new MethodNotAllowedException(HTTP_METHOD_POST);
        if (!$this->isAdmin()) throw new ForbiddenException();

        // 自分を強制退会させることを禁止
        if ($user_id === $this->loginUser['id']) throw new BadRequestException();

        $request_url = $this->request->getRequestTarget();

        $target_user = $this->Users->find()->where(['id' => $user_id])->first();
        // rootユーザーの削除禁止
        if ($target_user['email'] === 'root@example.com') throw new BadRequestException();
        if (empty($target_user)) throw new RecordNotFoundException();

        if ($this->Users->delete($target_user)) {
            $response = new Response(StatusOK, $request_url, (object) ['message' => 'Forced resign target user']);
            return $this->renderJson($response->formatResponse());
        }

        $response = new Response(StatusOK, $request_url, (object) ['message' => 'Failed forced resign target user']);
        return $this->renderJson($response->formatResponse());
    }
}
