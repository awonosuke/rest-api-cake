<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Core\Exception\Exception;

class InvalidCodeException extends Exception
{
    protected $_defaultCode = 500;
    protected $_messageTemplate = 'Invalid response code';

    public function __construct()
    {
        parent::__construct('Invalid response code', 500);
    }
}
