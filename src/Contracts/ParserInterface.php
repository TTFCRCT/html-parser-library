<?php

namespace HtmlParserLibrary\Contracts;

interface ParserInterface
{
    public function parse(string $content): void;

    public function getCount(): array;
}