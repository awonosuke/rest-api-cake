<?php
declare(strict_types=1);

namespace App\Controller;

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
            $response = ['code' => 200, 'user' => $new_user];

            return $this->renderJson($response);
        }

        // 保存に失敗した場合エラー返す
        $response = ['code' => 400, 'error' => $new_user->getErrors()];
        return $this->renderJson($response);
    }
}
