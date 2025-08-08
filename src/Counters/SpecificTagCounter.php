<?php

namespace HtmlParserLibrary\Counters;

use HtmlParserLibrary\Contracts\TagCounterInterface;

class SpecificTagCounter implements TagCounterInterface
{
    private array $tagsToCount;

    public function __construct(array $tagsToCount)
    {
        $this->tagsToCount = array_map('strtolower', $tagsToCount);
    }

    public function count(array $tags): array
    {
        return array_filter($tags, function ($tag) {
            return in_array(strtolower($tag), $this->tagsToCount);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getAllowedTags(): array
    {
        return $this->tagsToCount;
    }
}