<?php

namespace HtmlParserLibrary\Tests;

use HtmlParserLibrary\Factories\HtmlParserFactory;
use HtmlParserLibrary\Factories\XmlParserFactory;
use PHPUnit\Framework\TestCase;

class ParserFactoryTest extends TestCase
{
    public function testCreateHtmlParser()
    {
        $factory = new HtmlParserFactory();
        $parser = $factory->createParser();

        $this->assertInstanceOf('HtmlParserLibrary\Parsers\HtmlParser', $parser);
    }

    public function testCreateXmlParser()
    {
        $factory = new XmlParserFactory();
        $parser = $factory->createParser();

        $this->assertInstanceOf('HtmlParserLibrary\Parsers\XmlParser', $parser);
    }
}