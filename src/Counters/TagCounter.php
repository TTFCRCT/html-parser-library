<?php

namespace HtmlParserLibrary\Counters;

use HtmlParserLibrary\Contracts\TagCounterInterface;

class TagCounter implements TagCounterInterface
{
    public function count(array $tags): array
    {
        return $tags;
    }
}