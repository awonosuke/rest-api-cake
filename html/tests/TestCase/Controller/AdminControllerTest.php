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
     * Get JWT method
     *
     * @param string $role 'root'|'admin'|'test'
     * @return string
     */
    private function getJsonWebToken(string $role): string
    {
        $this->post('/user/login', [
            'email' => $role . '@example.com',
            'password' => $role,
        ]);
        $response_body = json_decode((string) $this->_response->getBody(), true);
        return $response_body['body']['token'];
    }

    private function setRequestHeaders(string $jwt)
    {
        $this->configRequest([
            'headers' => ['Authorization' => 'Bearer ' . $jwt]
        ]);
    }

    /**
     * Expire JWT method byb sending logout request
     *
     * @param string $jwt
     * @return void
     */
    private function expireJsonWebToken(string $jwt)
    {
        $this->setRequestHeaders($jwt);
        $this->post('/user/logout');
    }


    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::index()
     */
    public function testMakeAdminUserApi(): void
    {
        $url = '/admin/user/make-admin/';

        // Unauthorized
        $this->post($url . '3');
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        $jwt = $this->getJsonWebToken('admin');

        // OK
        $this->setRequestHeaders($jwt);
        $this->post($url . '4');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Make new admin user');

        // Bad Request
        $this->setRequestHeaders($jwt);
        $this->post($url . '1');
        $this->assertResponseCode(StatusBadRequest);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Bad Request');

        // Method Not Allowed
        $this->setRequestHeaders($jwt);
        $this->get($url . '4');
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        $this->expireJsonWebToken($jwt);

        // Forbidden
        $jwt = $this->getJsonWebToken('test');
        $this->setRequestHeaders($jwt);
        $this->post($url . '4');
        $this->assertResponseCode(StatusForbidden);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Forbidden');
        $this->expireJsonWebToken($jwt);
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

        // Unauthorized
        $this->get($url);
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        $jwt = $this->getJsonWebToken('admin');

        // OK
        $this->setRequestHeaders($jwt);
        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Get all user');

        // Method Not Allowed
        $this->setRequestHeaders($jwt);
        $this->post($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        $this->expireJsonWebToken($jwt);
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

        // Unauthorized
        $this->post($url . '4');
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        $jwt = $this->getJsonWebToken('admin');

        // OK
        $this->setRequestHeaders($jwt);
        $this->post($url . '4');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Forced resign target user');

        // Record Not Found
        $this->setRequestHeaders($jwt);
        $this->post($url . '0');
        $this->assertResponseCode(StatusOK);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Record Not Found');

        // Bad Request
        $this->setRequestHeaders($jwt);
        $this->post($url . '1');
        $this->assertResponseCode(StatusBadRequest);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Bad Request');

        // Method Not Allowed
        $this->setRequestHeaders($jwt);
        $this->get($url . '3');
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        $this->expireJsonWebToken($jwt);
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

        // Unauthorized
        $this->post($url . '4');
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        $jwt = $this->getJsonWebToken('admin');

        // OK
        $this->setRequestHeaders($jwt);
        $this->post($url . '1');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Forced delete target snippet');

        // Record Not Found
        $this->setRequestHeaders($jwt);
        $this->post($url . '0');
        $this->assertResponseCode(StatusOK);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Record Not Found');

        // Method Not Allowed
        $this->setRequestHeaders($jwt);
        $this->get($url . '1');
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        $this->expireJsonWebToken($jwt);
    }
}
