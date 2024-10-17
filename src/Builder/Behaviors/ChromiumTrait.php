<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait ChromiumTrait
{
    use Chromium\HeaderFooterTrait { Chromium\HeaderFooterTrait::configure as configureHeaderFooter; }
    use Chromium\PagePropertiesTrait { Chromium\PagePropertiesTrait::configure as configurePageProperties; }
    use Chromium\WaitBeforeRenderingTrait { Chromium\WaitBeforeRenderingTrait::configure as configureWaitBeforeRendering; }

    protected function configure(OptionsResolver $optionsResolver): void
    {
        $this->configureHeaderFooter($optionsResolver);
        $this->configurePageProperties($optionsResolver);
        $this->configureWaitBeforeRendering($optionsResolver);
    }
}
