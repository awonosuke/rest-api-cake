<?php
declare(strict_types=1);

namespace App\Library;

class ResponseBodyWithError {
    private string $message;
    private int $error_count;
    private object $error;

    /**
     * @param string $message
     * @param int $error_count
     * @param object $error
     * @throws \Exception
     */
    function __construct(string $message, int $error_count, object $error)
    {
        if ($message === '') throw new \Exception('Invalid response body message');
        $this->message = $message;

        if ($error_count < 0) throw new \Exception('Invalid response error count');
        $this->error_count = $error_count;

        if (empty((array) $error)) throw new \Exception('Invalid response body error');
        $this->error = $error;
    }

    /**
     * @return object
     */
    public function formatResponseBody(): object
    {
        return (object) array(
            'code' => $this->message,
            'errorCount' => $this->error_count,
            'error' => $this->error,
        );
    }
}
