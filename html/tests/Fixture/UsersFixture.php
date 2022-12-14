<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'email' => 'root@example.com',
                'password' => '$2y$10$eHPTMoPNLtgYidm7j8mOZOs3uK0sYpG9d8kYa.rjTLsVI.TMqNSji',
                'user_name' => 'root',
                'role' => 'admin',
                'created' => '2022-09-13 23:31:13',
                'updated' => '2022-09-13 23:31:13'
            ],
            [
                'id' => 2,
                'email' => 'admin@example.com',
                'password' => '$2y$10$/aPKYGvt8M9WM0phBKL8le8vOkhC0sEUCtlcPwu7HBvc50xkh433m',
                'user_name' => 'admin',
                'role' => 'admin',
                'created' => '2022-09-13 23:31:13',
                'updated' => '2022-09-13 23:31:13'
            ],
            [
                'id' => 3,
                'email' => 'test@example.com',
                'password' => '$2y$10$Afyt31Owvbm4/oeL9C2LmOYOCLJntrEIgkfJOyNmrLsSfuPlcVpqq',
                'user_name' => 'test',
                'role' => 'user',
                'created' => '2022-09-13 23:31:13',
                'updated' => '2022-09-13 23:31:13'
            ],
            [
                'id' => 4,
                'email' => 'cakephp@example.com',
                'password' => '$2y$10$e6f9KyM2AchdaM5LQG7BjO/dKVvlgdlXLCBoSuNmwXlTzSD/m.ATu',
                'user_name' => 'cakephp',
                'role' => 'user',
                'created' => '2022-09-13 23:31:13',
                'updated' => '2022-09-13 23:31:13'
            ]
        ];
        parent::init();
    }
}
