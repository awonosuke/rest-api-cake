<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Datasource\EntityInterface;
use Cake\Http\Exception\HttpException;

class ValidationErrorException extends HttpException
{
    protected $_validationErrors;

    public function __construct(EntityInterface $entity, $message = null, $code = StatusUnprocessableEntity)
    {
        $this->_validationErrors = $entity->getErrors();

        if ($message === null) {
            $message = 'Validation error occur';
        }

        parent::__construct($message, $code);
    }

    public function getValidationErrors()
    {
        return $this->_validationErrors;
    }
}
