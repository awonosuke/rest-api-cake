<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Core\Exception\Exception;

class InvalidBodyException extends Exception
{
    protected $_defaultCode = StatusInternalServerError;
    protected $_messageTemplate = 'Invalid response body';

    public function __construct()
    {
        parent::__construct('Invalid response body', StatusInternalServerError);
    }
}
