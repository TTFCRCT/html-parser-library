<?php

namespace HtmlParserLibrary\Factories;

use HtmlParserLibrary\Contracts\ParserInterface;
use HtmlParserLibrary\Contracts\ParserFactoryInterface;
use HtmlParserLibrary\Parsers\XmlParser;

class XmlParserFactory implements ParserFactoryInterface
{
    public function createParser(): ParserInterface
    {
        return new XmlParser();
    }
}