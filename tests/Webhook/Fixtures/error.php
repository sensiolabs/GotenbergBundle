<?php

use Sensiolabs\GotenbergBundle\RemoteEvent\ErrorGotenbergEvent;

return new ErrorGotenbergEvent(
    '52fce8b6-a594-4b90-82cf-347b58ab06ae',
    json_decode(
        file_get_contents(str_replace('.php', '.json', __FILE__)) ?: throw new LogicException('Fixture not found.'),
        true,
        flags: \JSON_THROW_ON_ERROR,
    ),
    500,
    'An error occurred.',
);
