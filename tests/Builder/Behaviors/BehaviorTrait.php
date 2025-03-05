<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * @template T of BuilderInterface
 *
 * @phpstan-require-extends GotenbergBuilderTestCase
 */
trait BehaviorTrait
{
    protected Container $dependencies;

    /**
     * @return T
     */
    abstract protected function getBuilder(): BuilderInterface;

    /**
     * @param T $builder
     *
     * @return T
     */
    abstract protected function initializeBuilder(BuilderInterface $builder, Container $container): BuilderInterface;

    /**
     * @return T
     */
    protected function getDefaultBuilder(): BuilderInterface
    {
        $builder = $this->getBuilder();

        return $this->initializeBuilder($builder, $this->dependencies);
    }
}
