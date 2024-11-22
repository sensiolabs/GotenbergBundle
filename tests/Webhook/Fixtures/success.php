<?php

use Sensiolabs\GotenbergBundle\RemoteEvent\SuccessGotenbergEvent;

$file = new SplFileInfo(__DIR__.'/success.pdf');

return new SuccessGotenbergEvent(
    '52fce8b6-a594-4b90-82cf-347b58ab06ae',
    fopen($file->getRealPath(), 'r') ?: throw new LogicException('Fixture not found.'),
    $file->getFilename(),
    'application/pdf',
    $file->getSize(),
);
