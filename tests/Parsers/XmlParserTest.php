<?php

namespace HtmlParserLibrary\Tests\Parsers;

use HtmlParserLibrary\Parsers\XmlParser;
use HtmlParserLibrary\Exceptions\InvalidContentException;
use HtmlParserLibrary\Exceptions\ParserException;

use PHPUnit\Framework\TestCase;

class XmlParserTest extends TestCase
{
    private XmlParser $parser;

    protected function setUp(): void
    {
        $this->parser = new XmlParser();
    }

    public function testParseSimpleXml()
    {
        $xml = '<?xml version="1.0"?><root><item>Test</item><item>Another</item></root>';
        $this->parser->parse($xml);

        $result = $this->parser->getCount();

        $this->assertEquals(1, $result['root']);
        $this->assertEquals(2, $result['item']);
    }

    public function testParseEmptyContentThrowsException()
    {
        $this->expectException(InvalidContentException::class);
        $this->expectExceptionMessage('Контент для парсинга не может быть пустым');

        $this->parser->parse('');
    }

    public function testParseInvalidXmlThrowsException()
    {
        $this->expectException(ParserException::class);

        $invalidXml = '<root><item>Test</root>';
        $this->parser->parse($invalidXml);
    }

    public function testParseXmlWithAttributes()
    {
        $xml = '<root attr="value"><item id="1">Test</item></root>';
        $this->parser->parse($xml);

        $result = $this->parser->getCount();

        $this->assertEquals(1, $result['root']);
        $this->assertEquals(1, $result['item']);
    }
}