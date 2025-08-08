<?php

namespace HtmlParserLibrary\Exceptions;

class InvalidContentException extends \InvalidArgumentException
{
    public static function forEmptyContent(): self
    {
        return new self("Контент для парсинга не может быть пустым");
    }
}