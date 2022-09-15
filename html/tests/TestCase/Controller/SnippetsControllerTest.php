<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\SnippetsController;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\SnippetsController Test Case
 *
 * @uses \App\Controller\SnippetsController
 */
class SnippetsControllerTest extends TestCase
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
     * Test create new snippet method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::index()
     */
    public function testCreateSnippetApi(): void
    {
        $url = '/snippet/create';

        // Unauthorized
        $this->post($url);
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        $jwt = $this->getJsonWebToken('test');

        // OK
        $this->setRequestHeaders($jwt);
        $this->post($url, [
            'content' => 'An old silent pond',
            'expire' => (new FrozenTime('+3 days'))->i18nFormat('yyyy-MM-dd HH:mm:ss')
        ]);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Create a new snippet');

        // Method Not Allowed
        $this->setRequestHeaders($jwt);
        $this->get($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        // Validation Unique Constraint (email)
        $this->setRequestHeaders($jwt);
        $this->post($url);
        $this->assertResponseCode(StatusUnprocessableEntity);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Validation error occur');

        $this->expireJsonWebToken($jwt);
    }

    /**
     * Test get snippet method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::view()
     */
    public function testGetSnippetApi(): void
    {
        $url = '/snippet/';

        // Unauthorized
        $this->get($url . '2');
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        $jwt = $this->getJsonWebToken('test');

        // OK
        $this->setRequestHeaders($jwt);
        $this->get($url . '2');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Get a snippet');

        // Record Not Found
        $this->setRequestHeaders($jwt);
        $this->get($url . '0');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Record Not Found');

        // Method Not Allowed
        $this->setRequestHeaders($jwt);
        $this->post($url . '2');
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        $this->expireJsonWebToken($jwt);
    }

    /**
     * Test get all snippet method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::add()
     */
    public function testAllSnippetApi(): void
    {
        $url = '/snippet/all';

        // Unauthorized
        $this->get($url);
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        $jwt = $this->getJsonWebToken('test');

        // OK
        $this->setRequestHeaders($jwt);
        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Get all snippet');

        // Method Not Allowed
        $this->setRequestHeaders($jwt);
        $this->post($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        $this->expireJsonWebToken($jwt);
    }
}
