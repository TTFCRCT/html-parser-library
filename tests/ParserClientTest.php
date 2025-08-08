<?php

namespace HtmlParserLibrary\Tests;

use HtmlParserLibrary\Contracts\ExternalServiceInterface;
use HtmlParserLibrary\Exceptions\ServiceException;
use HtmlParserLibrary\Extractors\SelectiveDomExtractor;
use HtmlParserLibrary\ParserClient;
use HtmlParserLibrary\Factories\ParserAbstractFactory;
use HtmlParserLibrary\Factories\XmlParserFactory;
use HtmlParserLibrary\Factories\HtmlParserFactory;
use HtmlParserLibrary\Counters\SpecificTagCounter;
use HtmlParserLibrary\Exceptions\InvalidContentException;
use HtmlParserLibrary\Mocks\MockExternalServiceSender;
use PHPUnit\Framework\TestCase;

class ParserClientTest extends TestCase
{
    private ParserClient $client;
    private ParserAbstractFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new ParserAbstractFactory();
        $this->factory->registerFactory('html', new HtmlParserFactory());
        $parser = $this->factory->create('html');
        $this->client = new ParserClient($parser);
    }

    public function testParseBasicHtml()
    {
        $html = '<div><p>Test</p></div>';
        $result = $this->client->parse($html);

        $this->assertEquals([
            'tags' => ['div' => 1, 'p' => 1],
        ], $result);
    }

    public function testParseWithTagCounter()
    {
        $this->client->setTagCounter(new SpecificTagCounter(['div']));
        $html = '<div><p>Test</p></div><span>Other</span>';
        $result = $this->client->parse($html);

        $expected = [
            'tags' => ['div' => 1],
        ];

        $this->assertEquals($expected, $result);
        $this->assertArrayNotHasKey('p', $result['tags']);
        $this->assertArrayNotHasKey('span', $result['tags']);
    }

    public function testParseWithExtractTag()
    {
        $this->client->setTagCounter(new SpecificTagCounter(['div']));
        $this->client->setContentExtractor(new SelectiveDomExtractor(['div']));
        $html = '<div><p>Test</p></div><span>Other</span>';
        $result = $this->client->parse($html);

        $expected = [
            'tags' => ['div' => 1],
            'content' => ['div' => [ 0 => 'Test']],
        ];

        $this->assertEquals($expected, $result);
        $this->assertArrayNotHasKey('p', $result['tags']);
        $this->assertArrayNotHasKey('span', $result['tags']);
    }

    public function testParseWithObserver()
    {
        $mockService = new MockExternalServiceSender('http://fake-url.test');
        $this->client->addObserver($mockService);

        $html = '<div>Content</div>';
        $this->client->parse($html);

        $this->assertTrue($mockService->wasCalled());

        $expected = [
            'tags' => ['div' => 1],
        ];
        $this->assertEquals($expected, $mockService->getLastData());
    }

    public function testParseEmptyContentThrowsException()
    {
        $this->expectException(InvalidContentException::class);
        $this->expectExceptionMessage('Контент для парсинга не может быть пустым');

        $this->client->parse('');
    }

    public function testParseInvalidContent()
    {
        $invalidHtml = '<div><p>Unclosed tags';
        $result = $this->client->parse($invalidHtml);

        $expected = [
            'tags' => ['div' => 1, 'p' => 1],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testParseWithAllComponents()
    {
        $this->client->setTagCounter(new SpecificTagCounter(['div', 'p']));

        $mockService = new MockExternalServiceSender('http://fake-url.test');
        $this->client->addObserver($mockService);

        $html = '<div><p>Test</p><a href="#">Link</a></div>';

        $result = $this->client->parse($html);

        $this->assertEquals(['div' => 1, 'p' => 1], $result['tags']);
        $this->assertTrue($mockService->wasCalled());

        $expected = [
            'tags' => ['div' => 1, 'p' => 1],
        ];
        $this->assertEquals(
            $expected,
            $mockService->getLastData()
        );
    }

    public function testParseXmlContent()
    {
        $this->factory->registerFactory('xml', new XmlParserFactory());
        $xmlParser = $this->factory->create('xml');
        $client = new ParserClient($xmlParser);

        $xml = '<?xml version="1.0"?><root><item>Test</item></root>';
        $result = $client->parse($xml);

        $expected = [
            'tags' => ['root' => 1, 'item' => 1],
        ];
        $this->assertEquals($expected, $result);
    }

    public function testServiceObserverExceptionHandling()
    {
        $failingService = new class implements ExternalServiceInterface {
            public function send(array $data): void
            {
                throw ServiceException::forSendError('http://fake-url.test');
            }
        };
        $this->client->addObserver($failingService);

        $failingService2 = new class implements ExternalServiceInterface {
            public function send(array $data): void
            {
                throw ServiceException::forSendError('http://fake-url2.test');
            }
        };
        $this->client->addObserver($failingService2);

        $html = '<div>Content</div>';

        $result = $this->client->parse($html);

        $expected = [
            'tags' => ['div' => 1],
        ];
        $this->assertEquals($expected, $result);
    }
}