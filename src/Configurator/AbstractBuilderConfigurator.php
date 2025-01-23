<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * @template T of BuilderInterface
 */
abstract class AbstractBuilderConfigurator
{
    /**
     * @param array<string, mixed> $configuration
     */
    public function __construct(
        private readonly array $configuration = [],
    ) {
    }

    /**
     * @param T $builder
     */
    public function __invoke(BuilderInterface $builder): void
    {
        foreach ($this->configuration as $key => $value) {
            $this->configure($builder, $key, $value);
        }
    }

    /**
     * @param T $builder
     */
    abstract protected function configure(BuilderInterface $builder, string $name, mixed $value): void;
}
