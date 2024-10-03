<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;

final class BuilderConfigurator
{
    /**
     * @param array<class-string<BuilderInterface>, array<string, array{'method': string, 'mustUseVariadic': bool, 'callback': (\Closure(mixed): mixed)|null}>> $configurations
     * @param array<class-string<BuilderInterface>, array<string, mixed>>                                                                                       $values
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
                $value = $configurationMap['callback']($value);
            }

            if (\is_array($value) && true === $configurationMap['mustUseVariadic']) {
                $builder->{$configurationMap['method']}(...$value);
            } else {
                $builder->{$configurationMap['method']}($value);
            }
        }
    }
}
