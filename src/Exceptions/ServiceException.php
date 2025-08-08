<?php

namespace HtmlParserLibrary\Exceptions;

class ServiceException extends \RuntimeException
{
    public static function forSendError(string $url): self
    {
        return new self("Ошибка отправки данных на сервис: {$url}");
    }
}