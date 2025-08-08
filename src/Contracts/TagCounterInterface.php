<?php

namespace HtmlParserLibrary\Contracts;

interface TagCounterInterface
{
    public function count(array $tags): array;
}