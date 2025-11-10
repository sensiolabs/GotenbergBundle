<?php

namespace Sensiolabs\GotenbergBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Inspired by Symfony's AppKernel.
 *
 * @see https://github.com/symfony/symfony/blob/7.4/src/Symfony/Bundle/FrameworkBundle/Tests/Functional/app/AppKernel.php
 */
final class TestKernel extends BaseKernel implements ExtensionInterface, ConfigurationInterface
{
    use MicroKernelTrait;

    public function __construct(
        private readonly string $projectDir,
        private readonly string $tmpDir,
    ) {
        parent::__construct('test', false);
    }

    public function getProjectDir(): string
    {
        return $this->projectDir;
    }

    public function getCacheDir(): string
    {
        return $this->tmpDir.'/cache';
    }

    public function getLogDir(): string
    {
        return $this->tmpDir.'/logs';
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
    }

    public function getNamespace(): string
    {
        return '';
    }

    public function getXsdValidationBasePath(): bool
    {
        return false;
    }

    public function getAlias(): string
    {
        return 'foo';
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        return new TreeBuilder('foo');
    }
}
