<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Event\EventManagerInterface;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    protected $loginUser;
    protected string $requestUrl;

    public function __construct(?ServerRequest $request = null, ?Response $response = null, ?string $name = null, ?EventManagerInterface $eventManager = null, ?ComponentRegistry $components = null)
    {
        parent::__construct($request, $response, $name, $eventManager, $components);

        $this->loginUser = $this->Authentication->getResult()->getData();
        $this->requestUrl = $request->getRequestTarget();
    }

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     * @throws \Exception
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');

        $this->loadComponent('Authentication.Authentication');
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->disableAutoLayout(false);

        $this->autoRender = false;
    }

    /**
     * Render JSON response method
     *
     * @param array $response
     * @return Response JSON response
     */
    protected function renderJson(array $response): Response
    {
        return $this->response
            ->withStatus($response['code'])
            ->withType("application/json; charset=UTF-8")
            ->withCharset('UTF-8')
            ->withStringBody(json_encode($response, JSON_FORCE_OBJECT));
    }

    /**
     * @param string $http_method
     * @return void
     * @throws MethodNotAllowedException
     */
    protected function allowHttpRequestMethod(string $http_method)
    {
        if (!$this->request->is($http_method)) throw new MethodNotAllowedException($http_method);
    }
}
