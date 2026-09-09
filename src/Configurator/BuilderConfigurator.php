<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

/**
 * The mapping is built at compile time by {@see \Sensiolabs\GotenbergBundle\DependencyInjection\BuilderStack} and
 * dumped into the container, so a callback can only be a static method target — the container cannot dump a closure.
 *
 * @phpstan-type BuilderConfigurationMapping array<class-string<BuilderInterface>, array<string, array{
 *     method: string,
 *     mustUseVariadic: bool,
 *     callback: array{class-string<\BackedEnum>, string}|null,
 * }>>
 */
final class BuilderConfigurator
{
    /**
     * @param BuilderConfigurationMapping                                 $configurations
     * @param array<class-string<BuilderInterface>, array<string, mixed>> $values
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

        foreach ($configuration as $key => $configurationMap) {
            $value = $values[$key] ?? null;
            if (null === $value) {
                continue;
            }

            if (null !== $configurationMap['callback']) {
                [$callbackClass, $callbackMethod] = $configurationMap['callback'];
                $value = $callbackClass::$callbackMethod($value);
            }

            if (\is_array($value) && true === $configurationMap['mustUseVariadic']) {
                $builder->{$configurationMap['method']}(...$value);
            } else {
                $builder->{$configurationMap['method']}($value);
            }
        }
    }
}
