<?php

namespace Sensiolabs\GotenbergBundle\Tests\Fixtures\Builder;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

class ValidBuilder implements BuilderInterface
{
    private array $multipartFormData = [];

    public function getEndpoint(): string
    {
        return '/test/endpoint';
    }
    public function getMultipartFormData(): array
    {
        return $this->multipartFormData;
    }
}
