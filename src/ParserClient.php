<?php

namespace HtmlParserLibrary;

use HtmlParserLibrary\Contracts\ExternalServiceInterface;
use HtmlParserLibrary\Contracts\ParserInterface;
use HtmlParserLibrary\Contracts\TagCounterInterface;
use HtmlParserLibrary\Contracts\ContentExtractorInterface;

class ParserClient
{
    private ParserInterface $parser;
    private ?TagCounterInterface $tagCounter = null;
    private ?ContentExtractorInterface $contentExtractor = null;
    private array $observers = [];

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function setTagCounter(TagCounterInterface $counter): void
    {
        $this->tagCounter = $counter;
    }

    public function setContentExtractor(ContentExtractorInterface $extractor): void
    {
        $this->contentExtractor = $extractor;
    }

    public function addObserver(ExternalServiceInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    public function parse(string $content): array
    {
        $this->parser->parse($content);

        $result = [
            'tags' => $this->parser->getCount(),
        ];

        if ($this->tagCounter) {
            $result['tags'] = $this->tagCounter->count($result['tags']);
        }

        if ($this->contentExtractor) {
            $result['content'] = $this->contentExtractor->extract($content);
        }

        foreach ($this->observers as $observer) {
            try {
                $observer->send($result);
            } catch (\RuntimeException $e) {
                error_log($e->getMessage());
                continue;
            }
        }

        return $result;
    }
}