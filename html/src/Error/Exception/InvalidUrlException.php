<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Core\Exception\Exception;

class InvalidUrlException extends Exception
{
    protected $_defaultCode = 500;
    protected $_messageTemplate = 'Invalid response url';

    public function __construct()
    {
        parent::__construct('Invalid response url', 500);
    }
}
