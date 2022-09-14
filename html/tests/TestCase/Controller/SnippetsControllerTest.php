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
     * Test index method
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

        // OK
        $this->post($url, [
            'content' => 'An old silent pond',
            'expire' => (new FrozenTime('+3 days'))->i18nFormat('yyyy-MM-dd HH:mm:ss')
        ]);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Create a new snippet');

        // Method Not Allowed
        $this->get($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');

        // Validation Unique Constraint (email)
        $this->post($url);
        $this->assertResponseCode(StatusUnprocessableEntity);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Validation error occur');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\SnippetsController::view()
     */
    public function testGetSnippetApi(): void
    {
        $url = '/snippet/1';

        // Unauthorized
        $this->get($url);
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        // OK
        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Get a snippet');

        // Record not found
        $this->get('/snippet/0');
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Record not found');

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
    public function testAllSnippetApi(): void
    {
        $url = '/snippet/all';

        // Unauthorized
        $this->get($url);
        $this->assertResponseCode(StatusUnauthorized);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Unauthorized');

        // OK
        $this->get($url);
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('Get all snippet');

        // Method Not Allowed
        $this->post($url);
        $this->assertResponseCode(StatusMethodNotAllowed);
        $this->assertContentType('application/json');
        $this->assertResponseContains('Method Not Allowed');
    }
}
