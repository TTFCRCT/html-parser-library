<?php

namespace HtmlParserLibrary\Contracts;

interface ContentExtractorInterface
{
    public function extract(string $content): array;
}