<?php
declare(strict_types=1);

namespace App\Error;

use App\Library\Response;
use Cake\Error\ExceptionRenderer;

class ApiExceptionRenderer extends ExceptionRenderer
{
    /**
     * Render JSON response method
     *
     * @param array $response
     * @return \Cake\Http\Response JSON response
     */
    private function renderJson(array $response): \Cake\Http\Response
    {
        return $this->controller->getResponse()
            ->withType("application/json; charset=UTF-8")
            ->withCharset('UTF-8')
            ->withStringBody(json_encode($response, JSON_FORCE_OBJECT));
    }

    /**
     * 400 Bad Request
     */
    public function badRequest($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = new Response(StatusBadRequest, $request_url, (object) ['message' => 'Bad request']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 401 Unauthorized
     */
    public function unauthorized($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = new Response(StatusUnauthorized, $request_url, (object) ['message' => 'Bad request']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 403 Forbidden
     */
    public function forbidden($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = new Response(StatusForbidden, $request_url, (object) ['message' => 'Bad request']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 404 Not Found
     */
    public function notFound($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = new Response(StatusNotFound, $request_url, (object) ['message' => 'Not found']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 405 Method Not Allowed
     */
    public function methodNotAllowed($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = new Response(StatusMethodNotAllowed, $request_url, (object) ['message' => 'Method not allowed']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 500 Record Not Found
     */
    public function recordNotFound($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = new Response(StatusOK, $request_url, (object) ['message' => 'Record not found']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 500 Invalid Response Code
     */
    public function invalidCode($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();
        $response = new Response(StatusInternalServerError, $request_url, (object) ['message' => 'Invalid response code']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 500 Invalid Response URL
     *
     * @throws \Exception
     */
    public function invalidUrl($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();
        $response = new Response(StatusInternalServerError, $request_url, (object) ['message' => 'Invalid response url']);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 500 Invalid Response Body
     */
    public function invalidBody($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();
        $response = new Response(StatusInternalServerError, $request_url, (object) ['message' => 'Invalid response body']);
        return $this->renderJson($response->formatResponse());
    }
}
