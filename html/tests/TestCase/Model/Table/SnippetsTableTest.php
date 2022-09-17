<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use App\Model\Table\SnippetsTable;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SnippetsTable Test Case
 */
class SnippetsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SnippetsTable
     */
    protected $Snippets;

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
        $config_users = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $config_snippets = $this->getTableLocator()->exists('Snippets') ? [] : ['className' => SnippetsTable::class];

        $this->Users = $this->getTableLocator()->get('Users', $config_users);
        $this->Snippets = $this->getTableLocator()->get('Snippets', $config_snippets);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Users);
        unset($this->Snippets);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SnippetsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        // Required property
        $test_snippet_entity = $this->Snippets->newEntity([]);
        $expected_error = [
            'user_id' => [
                '_required' => 'This field is required'
            ],
            'content' => [
                '_required' => 'This field is required'
            ],
            'expire' => [
                '_required' => 'This field is required'
            ]
        ];
        $this->assertEquals($expected_error, $test_snippet_entity->getErrors());

        // NOT NULL
        $test_snippet_entity = $this->Snippets->newEntity([
            'user_id' => null,
            'content' => null,
            'expire' => null
        ]);
        $expected_error = [
            'user_id' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'content' => [
                '_empty' => 'This field cannot be left empty'
            ],
            'expire' => [
                '_empty' => 'This field cannot be left empty'
            ]
        ];
        $this->assertEquals($expected_error, $test_snippet_entity->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SnippetsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $test_snippet_entity = $this->Snippets->newEntity([
            'user_id' => 0,
            'content' => 'This is test snippet',
            'expire' => (new FrozenTime('+5 days'))->i18nFormat('yyyy-MM-dd HH:mm:ss')
        ]);
        $this->Snippets->save($test_snippet_entity); // 指定したユーザーのレコードがあるか確認
        $expected_error = [
            'user_id' => [
                '_existsIn' => 'This value does not exist'
            ]
        ];
        $this->assertEquals($expected_error, $test_snippet_entity->getErrors());
    }

    /**
     * Test find "a" exist snippet
     *
     * @return void
     */
    public function testFindExistSnippet()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test find all exist snippet
     *
     * @return void
     */
    public function testFindAllExistSnippet()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test find all expired snippet
     *
     * @return void
     */
    public function testFindAllExpiredSnippet()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
