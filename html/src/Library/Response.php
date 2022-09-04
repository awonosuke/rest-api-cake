<?php
declare(strict_types=1);

namespace App\Library;

class Response {
    private int $code;
    private string $url;
    private $body;

    /**
     * @param int|null $code
     * @param string|null $url
     * @param mixed $body
     */
    function __construct(int $code, string $url, $body = null)
    {
        $this->code = $code;
        $this->url  = $url;
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
            'url'  => $this->url,
            'body' => $this->body,
        );
    }

    private function parseResponse()
    {
        $this->code = $this->scanCode($this->code);
        $this->url  = $this->scanUrl($this->url);
        $this->body = $this->scanBody($this->body);
    }

    private function scanCode($code): int
    {
        return  $code ?? 999;
    }

    private function scanUrl($url): string
    {
        return $url ?? 'unknown url';
    }

    private function scanBody($body)
    {
        return $body ?? ['no-data'];
    }
}
