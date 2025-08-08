<?php

namespace HtmlParserLibrary\Contracts;

use HtmlParserLibrary\Contracts\ParserInterface;

interface ParserFactoryInterface
{
    public function createParser(): ParserInterface;
}