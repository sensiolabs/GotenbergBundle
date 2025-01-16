<?php

namespace Sensiolabs\GotenbergBundle\BuilderOld\Pdf;

use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

interface AsyncBuilderInterface
{
    /**
     * Generates a file asynchronously.
     *
     * @throws MissingRequiredFieldException if webhook URL was not configured
     */
    public function generateAsync(): void;
}
