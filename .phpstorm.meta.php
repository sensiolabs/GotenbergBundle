<?php

namespace PHPSTORM_META {
    expectedArguments(
        \Sensiolabs\GotenbergBundle\Builder\Pdf\AbstractPdfBuilder::fileName(),
        1,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_INLINE,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_ATTACHMENT,
    );

    expectedArguments(
        \Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractScreenshotBuilder::fileName(),
        1,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_INLINE,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_ATTACHMENT,
    );
}
