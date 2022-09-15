<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Users',
        'app.Snippets',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = $this->getTableLocator()->get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        // Required property
        $test_user_entity = $this->Users->newEntity([]);
        $expected_error = [
            'email' => [
                '_required' => 'This field is required'
            ],
            'password' => [
                '_required' => 'This field is required'
            ],
            'user_name' => [
                '_required' => 'This field is required'
            ]
        ];
        $this->assertEquals($expected_error, $test_user_entity->getErrors());

        // NOT NULL
        $test_user_entity = $this->Users->newEntity([
            'email' => null,
            'password' => null,
            'user_name' => null
        ]);
        $expected_error = [
            'email' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'password' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'user_name' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];
        $this->assertEquals($expected_error, $test_user_entity->getErrors());

        // Over range
        $test_user_entity = $this->Users->newEntity([
            'email' => 'rootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootrootroot@example.com',
            'password' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'user_name' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
        ]);
        $expected_error = [
            'email' => [
                'maxLength' => 'The provided value is invalid'
            ],
            'password' => [
                'maxLength' => 'The provided value is invalid'
            ],
            'user_name' => [
                'maxLength' => 'The provided value is invalid'
            ]
        ];
        $this->assertEquals($expected_error, $test_user_entity->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $test_user_entity = $this->Users->newEntity([
            'email' => 'root@example.com',
            'password' => 'root',
            'user_name' => 'root'
        ]);
        $expected_error = [
            'email' => [
                'unique' => 'The provided value is invalid'
            ]
        ];
        $this->assertEquals($expected_error, $test_user_entity->getErrors());
    }
}
