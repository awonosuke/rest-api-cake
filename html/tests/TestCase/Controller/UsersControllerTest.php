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
     * Test index method
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

        // Validation Unique Constraint (email)
        $this->post($url, [
            'email' => 'test2@example.com',
            'password' => 'test2',
            'user_name' => 'test2'
        ]);
        $this->assertResponseCode(StatusUnprocessableEntity);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Validation error occur');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\UsersController::loginApi()
     */
    public function testLoginApi(): void
    {
        // OK
        $this->post('/user/login', [
            'email' => 'test@example.com',
            'password' => 'test',
        ]);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Login complete');

        // Unauthorized
        $this->post('/user/login', [
            'email' => 'dummy@example.com',
            'password' => 'dummy',
        ]);
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');
    }

    /**
     * Test add method
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

//        // OK
//        $this->login();
//        $token = $this->getToken();
//        $this->configRequest([
//            'headers' => ['Authorization' => 'Bearer' . $token]
//        ]);
//        $this->post($url);
//        $this->assertResponseOk();
//        $this->assertContentType('application/json');
//        $this->assertResponseContains('Logout complete');
//
//        // Method Not Allowed
//        $this->configRequest([
//            'headers' => ['Authorization' => 'Bearer' . $token]
//        ]);
//        $this->get($url);
//        $this->assertResponseCode(StatusMethodNotAllowed);
//        $this->assertContentType('application/json');
//        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test edit method
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

//        // OK
//        $this->post($url);
//        $this->assertResponseOk();
//        $this->assertContentType('application/json');
//        $this->assertResponseContains('Resign snippetbox');
//
//        // Method Not Allowed
//        $this->get($url);
//        $this->assertResponseCode(StatusMethodNotAllowed);
//        $this->assertContentType('application/json');
//        $this->assertResponseContains('Method Not Allowed');
    }
}
