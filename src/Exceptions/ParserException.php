<?php

namespace HtmlParserLibrary\Exceptions;

class ParserException extends \RuntimeException
{
    public static function forUnsupportedParserType(string $type): self
    {
        return new self("Неподдерживаемый тип парсера: {$type}");
    }

    public static function forParsingError(string $message): self
    {
        return new self("Ошибка парсинга: {$message}");
    }
}