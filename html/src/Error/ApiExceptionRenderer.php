<?php
declare(strict_types=1);

namespace App\Error;

use App\Error\Exception\InvalidBodyException;
use App\Error\Exception\InvalidCodeException;
use App\Error\Exception\InvalidUrlException;
use App\Error\Exception\RecordSaveErrorException;
use App\Error\Exception\ValidationErrorException;
use App\Library\Response;
use Cake\Error\ExceptionRenderer;
use Cake\Http\Exception\MethodNotAllowedException;

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
            ->withStatus($response['code'])
            ->withType("application/json; charset=UTF-8")
            ->withCharset('UTF-8')
            ->withStringBody(json_encode($response, JSON_FORCE_OBJECT));
    }

    /**
     * Build exception response object
     *
     * @param int $code
     * @param string $request_url
     * @param string $message
     * @return Response
     */
    private function normalExceptionResponse(int $code, string $request_url, string $message): Response
    {
        return new Response($code, $request_url, (object) ['message' => $message]);
    }

    /**
     * 200 Record Not Found
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function recordNotFound($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusOK, $request_url, 'Record not found');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 200 Failed to save record
     *
     * @param RecordSaveErrorException $exception
     * @return \Cake\Http\Response
     */
    public function recordSaveError(RecordSaveErrorException $exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse($exception->getCode(), $request_url, $exception->getMessage());
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 4XX Client Error
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function client($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusBadRequest, $request_url, 'Client Error');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 400 Bad Request
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function badRequest($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusBadRequest, $request_url, 'Bad Request');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 401 Unauthorized
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function unauthenticated($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusUnauthorized, $request_url, 'Unauthorized');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 403 Forbidden
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function forbidden($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusForbidden, $request_url, 'Forbidden');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 403 Invalid CSRF token
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function invalidCsrfToken($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusForbidden, $request_url, 'Invalid CSRF token');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 404 Not Found
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function notFound($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusNotFound, $request_url, 'Not Found');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 404 Not Found: Missing Controller
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function missingController($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusNotFound, $request_url, 'Not Found: Missing Controller');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 404 Not Found: Missing Action
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function missingAction($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusNotFound, $request_url, 'Not Found: Missing Action');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 405 Method Not Allowed
     *
     * @param MethodNotAllowedException $exception
     * @return \Cake\Http\Response
     */
    public function methodNotAllowed(MethodNotAllowedException $exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusMethodNotAllowed, $request_url, 'Method Not Allowed')->formatResponse();
        return $this->controller->getResponse()
            ->withStatus($response['code'])
            ->withType("application/json; charset=UTF-8")
            ->withCharset('UTF-8')
            ->withHeader('Allow', $exception->getMessage())
            ->withStringBody(json_encode($response, JSON_FORCE_OBJECT));
    }

    /**
     * 422 Unprocessable Entity: Validation error
     *
     * @param ValidationErrorException $exception
     * @return \Cake\Http\Response
     */
    public function validationError(ValidationErrorException $exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $validation_error = $exception->getValidationErrors();
        $error_count = count($validation_error);
        $response = new Response($exception->getCode(), $request_url, (object) ['message' => $exception->getMessage(), 'errorCount' => $error_count, 'error' => $validation_error]);
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 5XX Server Error
     *
     * @param $error
     * @return \Cake\Http\Response
     */
    public function error500($error): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusInternalServerError, $request_url, 'Internal Server Error');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 500 Internal Server Error
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function internalError($exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse(StatusInternalServerError, $request_url, 'Internal Server Error');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 500 Invalid Response Code
     *
     * @param InvalidCodeException $exception
     * @return \Cake\Http\Response
     */
    public function invalidCode(InvalidCodeException $exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse($exception->getCode(), $request_url, $exception->getMessage());
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 500 Invalid Response URL
     *
     * @param InvalidUrlException $exception
     * @return \Cake\Http\Response
     */
    public function invalidUrl(InvalidUrlException $exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse($exception->getCode(), $request_url, $exception->getMessage());
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 500 Invalid Response Body
     *
     * @param InvalidBodyException $exception
     * @return \Cake\Http\Response
     */
    public function invalidBody(InvalidBodyException $exception): \Cake\Http\Response
    {
        $request_url = $this->controller->getRequest()->getRequestTarget();

        $response = $this->normalExceptionResponse($exception->getCode(), $request_url, $exception->getMessage());
        return $this->renderJson($response->formatResponse());
    }
}
