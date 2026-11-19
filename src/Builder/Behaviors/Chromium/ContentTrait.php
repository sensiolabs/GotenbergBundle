<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Chromium;

trigger_deprecation('sensiolabs/gotenberg-bundle', '1.5', 'The "%s" trait is deprecated, use "%s" or "%s" instead. It will be removed in 2.0.', ContentTrait::class, PdfContentTrait::class, ScreenshotContentTrait::class);

/**
 * @deprecated since sensiolabs/gotenberg-bundle 1.5, use "PdfContentTrait" or "ScreenshotContentTrait" instead. It will be removed in 2.0.
 *
 * @package Behavior\\Content
 */
trait ContentTrait
{
    use PdfContentTrait;
}
