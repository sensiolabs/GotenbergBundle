<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;

interface BuilderAsyncInterface extends BuilderInterface
{
    public function generateAsync(): GotenbergAsyncResult;
}
