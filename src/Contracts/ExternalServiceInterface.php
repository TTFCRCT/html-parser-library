<?php

namespace HtmlParserLibrary\Contracts;

interface ExternalServiceInterface
{
    public function send(array $data): void;
}