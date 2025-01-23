<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver;

use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\HeadersBag;

interface PayloadResolverInterface
{
    public function resolveBody(BodyBag $bodyBag): array;

    public function resolveHeaders(HeadersBag $headersBag): array;
}
