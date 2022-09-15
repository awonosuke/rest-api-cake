<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

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
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SnippetsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SnippetsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
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
