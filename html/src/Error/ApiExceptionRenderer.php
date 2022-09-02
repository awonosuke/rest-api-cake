<?php
namespace App\Error;

use Cake\Error\ExceptionRenderer;

class ApiExceptionRenderer extends ExceptionRenderer
{
//    public function methodNotAllowed($error)
//    {
//        $response = [
//            'code' => 405,
//            'error' => 'Method Not Allowed'
//        ];
//        return $this->renderJson($response);
//    }

    /**
     * @param string[] $response
     * @return mixed
     */
    private function renderJson(array $response)
    {
        return $this->controller->getResponse()
            ->withType("application/json; charset=UTF-8")
            ->withStringBody(json_encode($response, JSON_FORCE_OBJECT));
    }
}
