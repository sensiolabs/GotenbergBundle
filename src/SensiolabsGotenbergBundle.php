<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\DependencyInjection\CompilerPass\GotenbergPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SensiolabsGotenbergBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new GotenbergPass());
    }
}
