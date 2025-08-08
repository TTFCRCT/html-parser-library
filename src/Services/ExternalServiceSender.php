<?php

namespace HtmlParserLibrary\Services;

use HtmlParserLibrary\Contracts\ExternalServiceInterface;
use HtmlParserLibrary\Exceptions\ServiceException;

class ExternalServiceSender implements ExternalServiceInterface
{
    private string $serviceUrl;

    public function __construct(string $serviceUrl = 'http://fake-url.test')
    {
        $this->serviceUrl = $serviceUrl;
    }

    public function send(array $data): void
    {
        // Логика подключения к API

        $response = true;

        if ($response === false) {
            throw ServiceException::forSendError($this->serviceUrl);
        }
    }
}