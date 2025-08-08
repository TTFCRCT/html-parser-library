<?php

namespace HtmlParserLibrary\Parsers;

use HtmlParserLibrary\Contracts\ParserInterface;
use HtmlParserLibrary\Exceptions\InvalidContentException;

class HtmlParser implements ParserInterface
{
    private array $tagCounts = [];

    public function parse(string $content): void
    {
        if (empty(trim($content))) {
            throw InvalidContentException::forEmptyContent();
        }
        
        preg_match_all('/<([a-z]+)(?:[^>]*)?>/i', $content, $matches);

        foreach ($matches[1] as $tag) {
            $this->tagCounts[strtolower($tag)] = ($this->tagCounts[strtolower($tag)] ?? 0) + 1;
        }
    }

    public function getCount(): array
    {
        return $this->tagCounts;
    }
}