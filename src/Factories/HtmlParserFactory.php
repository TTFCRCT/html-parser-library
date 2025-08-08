<?php

namespace HtmlParserLibrary\Factories;

use HtmlParserLibrary\Contracts\ParserInterface;
use HtmlParserLibrary\Contracts\ParserFactoryInterface;
use HtmlParserLibrary\Parsers\HtmlParser;

class HtmlParserFactory implements ParserFactoryInterface
{
    public function createParser(): ParserInterface
    {
        return new HtmlParser();
    }
}