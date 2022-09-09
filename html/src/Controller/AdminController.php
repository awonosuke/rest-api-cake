<?php
declare(strict_types=1);

namespace App\Controller;

use App\Library\Response;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;

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
}
