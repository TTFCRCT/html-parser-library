<?php

namespace HtmlParserLibrary\Parsers;

use HtmlParserLibrary\Contracts\ParserInterface;
use HtmlParserLibrary\Exceptions\InvalidContentException;
use HtmlParserLibrary\Exceptions\ParserException;
use DOMDocument;
use DOMElement;

class XmlParser implements ParserInterface
{
    private array $tagCounts = [];

    public function parse(string $content): void
    {
        if (empty(trim($content))) {
            throw InvalidContentException::forEmptyContent();
        }

        try {
            $xml = new DOMDocument();
            $xml->loadXML($content);

            if (!$xml->documentElement) {
                throw new \Exception("Invalid XML");
            }
            $this->countTags($xml->documentElement);
        } catch (\Exception $e) {
            throw ParserException::forParsingError($e->getMessage());
        }
    }

    private function countTags(DOMElement $element): void
    {
        $tagName = $element->tagName;
        $this->tagCounts[$tagName] = ($this->tagCounts[$tagName] ?? 0) + 1;

        foreach ($element->childNodes as $child) {
            if ($child instanceof DOMElement) {
                $this->countTags($child);
            }
        }
    }

    public function getCount(): array
    {
        return $this->tagCounts;
    }
}