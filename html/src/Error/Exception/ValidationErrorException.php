<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Core\Exception\Exception;

class ValidationErrorException extends Exception
{
    protected $_defaultCode = StatusBadRequest;
    protected $_messageTemplate = 'Validation error occur';

    public function __construct()
    {
        parent::__construct('Validation error occur', StatusBadRequest);
    }
}
