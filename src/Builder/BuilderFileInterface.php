<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;

interface BuilderFileInterface
{
    public function generate(): GotenbergFileResult;
}