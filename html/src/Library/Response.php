<?php
declare(strict_types=1);

namespace App\Library;

class Response {
    private $body;
    private int $code;

    /**
     * @param int|null $code
     * @param mixed $body
     */
    function __construct(int $code, $body = null)
    {
        $this->code = $code;
        $this->body = $body;

        $this->parseResponse();
    }

    /**
     * @return array
     */
    public function formatResponse(): array
    {
        return array(
            'code' => $this->code,
            'body' => $this->body,
        );
    }

    private function parseResponse()
    {
        $this->code = $this->scanCode($this->code);
        $this->body = $this->scanBody($this->body);
    }

    private function scanCode($code): int
    {
        return  $code ?? 999;
    }

    private function scanBody($body)
    {
        return $body ?? ['no-data'];
    }
}
