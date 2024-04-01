<?php

namespace PHPSTORM_META {
    expectedArguments(
        \Sensiolabs\GotenbergBundle\Builder\AbstractPdfBuilder::fileName(),
        1,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_INLINE,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_ATTACHMENT,
    );
}
