<?php
declare(strict_types=1);

namespace App\Library;

class Response {
    private int $code;
    private string $url;
    private object $body;

    private const CODE_MIN = 200;
    private const CODE_MAX = 599;

    /**
     * @param int $code
     * @param string $url
     * @param object $body
     * @throws \Exception
     */
    function __construct(int $code, string $url, object $body)
    {
        if ($code < self::CODE_MIN) throw new \Exception('Invalid status code');
        if ($code > self::CODE_MAX) throw new \Exception('Invalid status code');
        $this->code = $code;

        if ($url === "") throw new \Exception('Invalid request url');
        $this->url  = $url;

        if (empty((array) $body)) throw new \Exception('Invalid response body');
        $this->body = $body;
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
}
