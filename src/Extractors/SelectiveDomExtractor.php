<?php

namespace HtmlParserLibrary\Extractors;

use HtmlParserLibrary\Contracts\ContentExtractorInterface;
use HtmlParserLibrary\Exceptions\InvalidContentException;
use DOMDocument;
use DOMElement;
use DOMXPath;

class SelectiveDomExtractor implements ContentExtractorInterface
{
    private array $allowedTags;
    private bool $isXml;

    public function __construct(
        array $allowedTags = [],
        bool  $isXml = false
    )
    {
        $this->allowedTags = array_map('strtolower', $allowedTags);
        $this->isXml = $isXml;
    }

    public function extract(string $content): array
    {
        if (empty(trim($content))) {
            throw new InvalidContentException('Контент не может быть пустым');
        }

        $dom = new DOMDocument('1.0', 'UTF-8');

        libxml_use_internal_errors(true);
        if ($this->isXml) {
            $dom->loadXML($content, LIBXML_NOERROR | LIBXML_NOWARNING);
        } else {
            $dom->loadHTML('<?xml encoding="UTF-8">' . $content);
        }
        libxml_clear_errors();

        $targetTags = empty($this->allowedTags)
            ? $this->getAllDocumentTags($dom)
            : $this->allowedTags;

        return $this->extractFlattened($dom, $targetTags);
    }

    private function extractFlattened(DOMDocument $dom, array $targetTags): array
    {
        $xpath = new DOMXPath($dom);
        $result = [];
        foreach ($targetTags as $tag) {
            $nodes = $xpath->query("//{$tag}");

            foreach ($nodes as $node) {
                if ($node instanceof DOMElement) {
                    if (!empty($node->textContent)) {
                        // Удаляем BOM, тримим, нормализуем пробелы
                        $textContent = preg_replace('/^\x{EF}\x{BB}\x{BF}/u', '', $node->textContent);
                        $textContent = trim(preg_replace('/\s+/u', ' ', $textContent));

                        // Декодируем HTML-сущности, если есть
                        $textContent = html_entity_decode($textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                        $result[$tag][] = $textContent;
                    }
                }
            }
        }

        return $result;
    }

    private function getAllDocumentTags(DOMDocument $dom): array
    {
        $xpath = new DOMXPath($dom);
        $tags = [];
        $nodes = $xpath->query('//*');

        foreach ($nodes as $node) {
            if ($node instanceof DOMElement) {
                $tagName = strtolower($node->tagName);
                if (!in_array($tagName, $tags)) {
                    $tags[] = $tagName;
                }
            }
        }

        return $tags;
    }
}