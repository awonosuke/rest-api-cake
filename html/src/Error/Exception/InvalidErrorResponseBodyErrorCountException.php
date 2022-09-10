<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Core\Exception\Exception;

class InvalidErrorResponseBodyErrorCountException extends Exception
{
    protected $_defaultCode = StatusInternalServerError;
    protected $_messageTemplate = 'Invalid error response body error count';

    public function __construct()
    {
        parent::__construct('Invalid error response body error count', StatusInternalServerError);
    }
}
