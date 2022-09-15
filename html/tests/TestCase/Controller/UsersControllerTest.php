<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UsersController Test Case
 *
 * @uses \App\Controller\UsersController
 */
class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Users',
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
     * Test signup method
     *
     * @return void
     * @uses \App\Controller\UsersController::signupApi()
     */
    public function testSignupApi(): void
    {
        $url = '/user/signup';

        // OK
        $this->post($url, [
            'email' => 'test2@example.com',
            'password' => 'test2',
            'user_name' => 'test2'
        ]);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Signup complete');

        // Method Not Allowed
        $this->get($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        // Validation Unique Constraint (same email)
        $this->post($url, [
            'email' => 'test2@example.com',
            'password' => 'test2',
            'user_name' => 'test2'
        ]);
        $this->assertResponseCode(StatusUnprocessableEntity);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Validation error occur');

        // Validation Unique Constraint (missing property)
        $this->post($url, [
            'email' => 'test2@example.com',
            'password' => 'test2'
        ]);
        $this->assertResponseCode(StatusUnprocessableEntity);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Validation error occur');
    }

    /**
     * Test login method
     *
     * @return void
     * @uses \App\Controller\UsersController::loginApi()
     */
    public function testLoginApi(): void
    {
        $url = '/user/login';

        // OK
        $this->post($url, [
            'email' => 'test@example.com',
            'password' => 'test',
        ]);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Login complete');

        // token取得
        $response_body = json_decode((string) $this->_response->getBody(), true);
        $jwt = $response_body['body']['token'];

        // Failed Login
        $this->post($url, [
            'email' => 'dummy@example.com',
            'password' => 'dummy',
        ]);
        $this->assertResponseCode(StatusOK);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Either email or password is invalid');

        // Method Not Allowed
        $this->get($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        $this->expireJsonWebToken($jwt);
    }

    /**
     * Test logout method
     *
     * @return void
     * @uses \App\Controller\UsersController::logoutApi()
     */
    public function testLogoutApi(): void
    {
        $url = '/user/logout';

        // Unauthorized
        $this->get($url);
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        $jwt = $this->getJsonWebToken('test');

        // OK
        $this->setRequestHeaders($jwt);
        $this->post($url);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Logout complete');

        // Method Not Allowed
        $this->setRequestHeaders($jwt);
        $this->get($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        $this->expireJsonWebToken($jwt);
    }

    /**
     * Test resign user method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testResignApi(): void
    {
        $url = '/user/resign';

        // Unauthorized
        $this->get($url);
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        $jwt = $this->getJsonWebToken('test');

        // Method Not Allowed
        $this->setRequestHeaders($jwt);
        $this->get($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        // OK
        $this->setRequestHeaders($jwt);
        $this->post($url);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Resign snippetbox');

        $this->expireJsonWebToken($jwt);

        // Bad Request : Try to resign "root" user
        $jwt = $this->getJsonWebToken('root');
        $this->setRequestHeaders($jwt);
        $this->post($url);
        $this->assertResponseCode(StatusBadRequest);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Bad Request');
        $this->expireJsonWebToken($jwt);
    }
}
