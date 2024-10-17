<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Builder\Result\GotenbergAsyncResult;

interface BuilderAsyncInterface
{
    public function generateAsync(): GotenbergAsyncResult;
}