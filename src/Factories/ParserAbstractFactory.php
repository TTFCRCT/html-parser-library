<?php

namespace HtmlParserLibrary\Factories;

use HtmlParserLibrary\Contracts\ParserFactoryInterface;
use HtmlParserLibrary\Exceptions\ParserException;
use HtmlParserLibrary\Contracts\ParserInterface;

class ParserAbstractFactory
{
    private array $factories = [];

    public function registerFactory(string $type, ParserFactoryInterface $factory): void
    {
        $this->factories[strtolower($type)] = $factory;
    }

    public function create(string $type): ParserInterface
    {
        if (!isset($this->factories[strtolower($type)])) {
            throw ParserException::forUnsupportedParserType($type);
        }

        return $this->factories[strtolower($type)]->createParser();
    }
}