<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Core\Exception\Exception;

class RecordSaveErrorException extends Exception
{
    protected $_defaultCode = StatusOK;
    protected $_messageTemplate = 'Failed to save record';

    public function __construct()
    {
        parent::__construct('Failed to save record', StatusOK);
    }
}
