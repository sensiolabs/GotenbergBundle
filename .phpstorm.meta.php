<?php

namespace PHPSTORM_META {
    expectedArguments(
        \Sensiolabs\GotenbergBundle\BuilderOld\Pdf\AbstractPdfBuilder::fileName(),
        1,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_INLINE,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_ATTACHMENT,
    );

    expectedArguments(
        \Sensiolabs\GotenbergBundle\BuilderOld\Screenshot\AbstractScreenshotBuilder::fileName(),
        1,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_INLINE,
        \Symfony\Component\HttpFoundation\HeaderUtils::DISPOSITION_ATTACHMENT,
    );
}
