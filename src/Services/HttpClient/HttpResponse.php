<?php

namespace GoogleRecaptcha\Services\HttpClient;

class HttpResponse
{

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $body;

    /**
     * @param int $statusCode
     * @param string $body
     */
    public function __construct(int $statusCode, string $body)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }


}
