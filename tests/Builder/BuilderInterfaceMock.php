<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

final class BuilderInterfaceMock
{
    public static function getDefault(): BuilderInterface
    {
        return new class() implements BuilderInterface {
            public function getEndpoint(): string
            {
                return '/endpoint/test';
            }

            public function getMultipartFormData(): array
            {
                return [
                    ['url' => 'https://gotenberg.dev/docs/routes'],
                ];
            }
        };
    }
}
