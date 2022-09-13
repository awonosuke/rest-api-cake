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
                'email' => 'test@example.com',
                'password' => 'test',
                'user_name' => 'test',
                'created' => '2022-08-30 01:18:27',
                'updated' => '2022-08-30 01:18:27',
            ],
        ];
        parent::init();
    }
}
