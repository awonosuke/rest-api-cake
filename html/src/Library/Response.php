<?php
declare(strict_types=1);

namespace App\Library;

class Response {
    private int $code;
    private string $url;
    private $body;

    private const CODE_MIN = 200;
    private const CODE_MAX = 500;

    /**
     * @param int|null $code
     * @param string|null $url
     * @param mixed $body
     * @throws \Exception
     */
    function __construct(int $code, string $url, $body = null)
    {
        if ($code < self::CODE_MIN) throw new \Exception('Invalid status code');
        if ($code > self::CODE_MAX) throw new \Exception('Invalid status code');

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
