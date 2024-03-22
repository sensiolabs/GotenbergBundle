<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\ProcessBuildersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SensiolabsGotenbergBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ProcessBuildersPass());
    }
}
