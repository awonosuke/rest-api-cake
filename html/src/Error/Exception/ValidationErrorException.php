<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Datasource\EntityInterface;
use Cake\Http\Exception\HttpException;

class ValidationErrorException extends HttpException
{
    protected array $_validationErrors;

    public function __construct(EntityInterface $entity, $message = null, $code = StatusUnprocessableEntity)
    {
        $this->_validationErrors = $entity->getErrors();

        if ($message === null) {
            $message = 'Validation error occur';
        }

        parent::__construct($message, $code);
    }

    public function getValidationErrors(): array
    {
        return $this->_validationErrors;
    }
}
