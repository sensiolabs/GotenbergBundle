<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergFileResult;

interface BuilderFileInterface extends BuilderInterface
{
    public function generate(): GotenbergFileResult;
}
