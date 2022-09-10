<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Core\Exception\Exception;

class InvalidErrorResponseBodyMessageException extends Exception
{
    protected $_defaultCode = StatusInternalServerError;
    protected $_messageTemplate = 'Invalid error response body message';

    public function __construct()
    {
        parent::__construct('Invalid error response body message', StatusInternalServerError);
    }
}
