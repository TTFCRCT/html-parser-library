<?php

namespace HtmlParserLibrary\Tests;

use HtmlParserLibrary\Parsers\HtmlParser;
use HtmlParserLibrary\Exceptions\InvalidContentException;

use PHPUnit\Framework\TestCase;

class HtmlParserTest extends TestCase
{
    private HtmlParser $parser;

    protected function setUp(): void
    {
        $this->parser = new HtmlParser();
    }

    public function testParseSimpleHtml()
    {
        $html = '<html><body><div>Test</div><p>Paragraph</p></body></html>';
        $this->parser->parse($html);

        $result = $this->parser->getCount();

        $this->assertEquals(1, $result['html']);
        $this->assertEquals(1, $result['body']);
        $this->assertEquals(1, $result['div']);
        $this->assertEquals(1, $result['p']);
    }

    public function testParseEmptyContentThrowsException()
    {
        $this->expectException(InvalidContentException::class);
        $this->expectExceptionMessage('Контент для парсинга не может быть пустым');

        $this->parser->parse('');
    }

    public function testParseInvalidHtml()
    {
        $html = '<div>Test<p>Paragraph</div>';
        $this->parser->parse($html);

        $result = $this->parser->getCount();

        $this->assertEquals(1, $result['div']);
        $this->assertEquals(1, $result['p']);
    }
}