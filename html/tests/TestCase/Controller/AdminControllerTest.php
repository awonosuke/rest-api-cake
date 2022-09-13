<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\AdminController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\SnippetsController Test Case
 *
 * @uses \App\Controller\AdminController
 */
class AdminControllerTest extends TestCase
{
    use IntegrationTestTrait;

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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::index()
     */
    public function testMakeAdminUserApi(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::view()
     */
    public function testAllUserApi(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::add()
     */
    public function testForcedResignApi(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::delete()
     */
    public function testForcedDeleteSnippetApi(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
