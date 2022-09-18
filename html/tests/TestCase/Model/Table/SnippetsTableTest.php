<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use App\Model\Table\SnippetsTable;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
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
        $exist_snippet_query = $this->Snippets
            ->findExistSnippet(1, 1)
            ->select(['id', 'content'], true);
        $expected_query_result = [
            0 => [
                'id' => 1,
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            ]
        ];
        $this->assertInstanceOf('Cake\ORM\Query', $exist_snippet_query);
        $this->assertEquals($expected_query_result, $exist_snippet_query->disableHydration()->toArray());

        $expire_snippet_query = $this->Snippets->findExistSnippet(4, 3)->select(['id', 'content'], true);
        $expected_query_result = [];
        $this->assertEquals($expected_query_result, $expire_snippet_query->disableHydration()->toArray());
    }

    /**
     * Test find all exist snippet
     *
     * @return void
     */
    public function testFindAllExistSnippet()
    {
        $all_exist_snippet_query = $this->Snippets
            ->findAllExistSnippet(3)
            ->select(['id', 'content'], true);
        $expected_query_result = [
            0 => [
                'id' => 2,
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            ],
            1 => [
                'id' => 3,
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            ]
        ];
        $this->assertInstanceOf('Cake\ORM\Query', $all_exist_snippet_query);
        $this->assertEquals($expected_query_result, $all_exist_snippet_query->disableHydration()->toArray());

        $all_exist_snippet_query = $this->Snippets
            ->findAllExistSnippet(4);
        $expected_query_result = [];
        $this->assertEquals($expected_query_result, $all_exist_snippet_query->disableHydration()->toArray());
    }

    /**
     * Test find all expired snippet
     *
     * @return void
     */
    public function testFindAllExpiredSnippet()
    {
        $all_exist_snippet_query = $this->Snippets
            ->findAllExpiredSnippet(3)
            ->select(['id', 'content'], true);
        $expected_snippet = [
            0 => [
                'id' => 4,
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            ]
        ];
        $this->assertInstanceOf('Cake\ORM\Query', $all_exist_snippet_query);
        $this->assertEquals($expected_snippet, $all_exist_snippet_query->disableHydration()->toArray());

        $all_exist_snippet_query = $this->Snippets
            ->findAllExistSnippet(4);
        $expected_query_result = [];
        $this->assertEquals($expected_query_result, $all_exist_snippet_query->disableHydration()->toArray());
    }
}
