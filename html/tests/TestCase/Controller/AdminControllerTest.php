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
        $url = '/admin/make-admin/';

        // OK
        $this->post($url . '2');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Make new admin user');

        // Bad Request
        $this->post($url . '2');
        $this->assertResponseCode(StatusBadRequest);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Bad Request');

        // Method Not Allowed
        $this->get($url . '2');
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::view()
     */
    public function testAllUserApi(): void
    {
        $url = '/admin/user/all';

        // OK
        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Get all user');

        // Method Not Allowed
        $this->post($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::add()
     */
    public function testForcedResignApi(): void
    {
        $url = '/admin/user/forced-resign/';

        // OK
        $this->post($url . '2');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Forced resign target user');

        // Record Not Found
        $this->post($url . '0');
        $this->assertResponseCode(StatusOK);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Record Not Found');

        // Method Not Allowed
        $this->get($url . '2');
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::delete()
     */
    public function testForcedDeleteSnippetApi(): void
    {
        $url = '/admin/snippet/forced-delete/';

        // OK
        $this->post($url . '1');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Make new admin user');

        // Record Not Found
        $this->post($url . '0');
        $this->assertResponseCode(StatusOK);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Record Not Found');

        // Method Not Allowed
        $this->get($url . '1');
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');
    }
}
