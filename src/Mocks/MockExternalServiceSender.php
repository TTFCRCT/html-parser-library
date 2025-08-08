<?php

namespace HtmlParserLibrary\Mocks;

use HtmlParserLibrary\Contracts\ExternalServiceInterface;

class MockExternalServiceSender implements ExternalServiceInterface
{
    private bool $wasCalled = false;
    private ?array $lastData = null;

    public function send(array $data): void
    {
        $this->wasCalled = true;
        $this->lastData = $data;
    }

    public function wasCalled(): bool
    {
        return $this->wasCalled;
    }

    public function getLastData(): ?array
    {
        return $this->lastData;
    }
}