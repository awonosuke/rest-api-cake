<?php
declare(strict_types=1);

namespace App\Library;

use App\Error\Exception\InvalidBodyException;
use App\Error\Exception\InvalidCodeException;
use App\Error\Exception\InvalidUrlException;

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
     *
     * @throws InvalidBodyException
     * @throws InvalidUrlException
     * @throws InvalidBodyException
     */
    function __construct(int $code, string $url, object $body)
    {
        if ($code < self::CODE_MIN) throw new InvalidCodeException();
        if ($code > self::CODE_MAX) throw new InvalidCodeException();
        $this->code = $code;

        if ($url === "") throw new InvalidUrlException();
        $this->url  = $url;

        if (empty((array) $body)) throw new InvalidBodyException();
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
