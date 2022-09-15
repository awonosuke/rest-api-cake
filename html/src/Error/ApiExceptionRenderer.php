<?php
declare(strict_types=1);

namespace App\Error;

use App\Error\Exception\InvalidBodyException;
use App\Error\Exception\InvalidCodeException;
use App\Error\Exception\InvalidUrlException;
use App\Error\Exception\RecordSaveErrorException;
use App\Error\Exception\ValidationErrorException;
use App\Library\ErrorResponseBody;
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
     * @return string
     */
    private function requestUrl(): string
    {
        return $this->controller->getRequest()->getRequestTarget();
    }

    /**
     * 200 Record Not Found
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function recordNotFound($exception): \Cake\Http\Response
    {
        $response = $this->normalExceptionResponse(StatusOK, $this->requestUrl(), 'Record Not Found');
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
        $response = $this->normalExceptionResponse($exception->getCode(), $this->requestUrl(), $exception->getMessage());
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
        $response = $this->normalExceptionResponse(StatusBadRequest, $this->requestUrl(), 'Client Error');
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
        $response = $this->normalExceptionResponse(StatusBadRequest, $this->requestUrl(), 'Bad Request');
        return $this->renderJson($response->formatResponse());
    }

    /**
     * 401 Unauthorized
     *
     * @param $exception
     * @return \Cake\Http\Response
     */
    public function unauthorized($exception): \Cake\Http\Response
    {
        $response = $this->normalExceptionResponse(StatusUnauthorized, $this->requestUrl(), 'Unauthorized');
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
        $response = $this->normalExceptionResponse(StatusUnauthorized, $this->requestUrl(), 'Unauthorized');
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
        $response = $this->normalExceptionResponse(StatusForbidden, $this->requestUrl(), 'Forbidden');
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
        $response = $this->normalExceptionResponse(StatusForbidden, $this->requestUrl(), 'Invalid CSRF token');
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
        $response = $this->normalExceptionResponse(StatusNotFound, $this->requestUrl(), 'Not Found');
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
        $response = $this->normalExceptionResponse(StatusNotFound, $this->requestUrl(), 'Not Found: Missing Controller');
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
        $response = $this->normalExceptionResponse(StatusNotFound, $this->requestUrl(), 'Not Found: Missing Action');
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

        $response = $this->normalExceptionResponse(StatusMethodNotAllowed, $this->requestUrl(), 'Method Not Allowed');
        return $this->renderJson($response->formatResponse())
            ->withHeader('Allow', $exception->getMessage());
    }

    /**
     * 422 Unprocessable Entity: Validation error
     *
     * @param ValidationErrorException $exception
     * @return \Cake\Http\Response
     * @throws \Exception
     */
    public function validationError(ValidationErrorException $exception): \Cake\Http\Response
    {
        $validation_error = $exception->getValidationErrors();
        $error_count = count($validation_error);
        $error_response_body = new ErrorResponseBody($exception->getMessage(), $error_count, (object) $validation_error);

        $response = new Response(StatusUnprocessableEntity, $this->requestUrl(), $error_response_body->formatErrorResponseBody());
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
        $response = $this->normalExceptionResponse(StatusInternalServerError, $this->requestUrl(), 'Internal Server Error');
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
        $response = $this->normalExceptionResponse(StatusInternalServerError, $this->requestUrl(), 'Internal Server Error');
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
        $response = $this->normalExceptionResponse($exception->getCode(), $this->requestUrl(), $exception->getMessage());
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
        $response = $this->normalExceptionResponse($exception->getCode(), $this->requestUrl(), $exception->getMessage());
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
        $response = $this->normalExceptionResponse($exception->getCode(), $this->requestUrl(), $exception->getMessage());
        return $this->renderJson($response->formatResponse());
    }
}
