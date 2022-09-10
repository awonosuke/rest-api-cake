<?php
declare(strict_types=1);

namespace App\Library;

use App\Error\Exception\InvalidErrorResponseBodyErrorCountException;
use App\Error\Exception\InvalidErrorResponseBodyMessageException;

class ErrorResponseBody {
    private string $message;
    private int $error_count;
    private ?object $error;

    /**
     * @param string $message
     * @param int $error_count
     * @param object|null $error
     * @throws \Exception
     */
    function __construct(string $message, int $error_count, object $error = null)
    {
        if ($message === '') throw new InvalidErrorResponseBodyMessageException();
        $this->message = $message;

        if ($error_count < 0) throw new InvalidErrorResponseBodyErrorCountException();
        $this->error_count = $error_count;

        $this->error = $error ?? '';
    }

    /**
     * @return object
     */
    public function formatErrorResponseBody(): object
    {
        return (object) array(
            'message' => $this->message,
            'url'  => $this->error_count,
            'error' => $this->error,
        );
    }
}
