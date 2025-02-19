<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

final class BuilderConfigurator
{
    /**
     * @param array<class-string<BuilderInterface>, array<string, string>> $configurations
     * @param array<class-string<BuilderInterface>, array<string, mixed>>  $values
     */
    public function __construct(
        private readonly array $configurations,
        private readonly array $values,
    ) {
    }

    public function __invoke(BuilderInterface $builder): void
    {
        $configuration = $this->configurations[$builder::class];
        $values = $this->values[$builder::class];


        foreach ($configuration as $key => $method) {
            $value = $values[$key] ?? null;
            if (null === $value) {
                continue;
            }

            if (!\is_array($value) || (\is_array($value) && array_is_list($value) === true)) {  // TODO Not sure about the logic we should use here...
                $builder->{$method}($value);
            } else {
                $builder->{$method}(...$value);
            }
        }
    }
}
