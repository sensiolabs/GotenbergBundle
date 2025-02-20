<?php

namespace Sensiolabs\GotenbergBundle\Configurator;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\PaperSize;

final class BuilderConfigurator
{
    /**
     * @param array<class-string<BuilderInterface>, array<string, array{'method': string, 'parametersType': array<array-key, string>}>> $configurations
     * @param array<class-string<BuilderInterface>, array<string, mixed>>                                                               $values
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

            if (!\is_array($value) && \count($configurationMap['parametersType']) === 1) {
                if (class_exists($configurationMap['parametersType'][0]) || interface_exists($configurationMap['parametersType'][0])) {
                    $class = 'Sensiolabs\GotenbergBundle\Enumeration\PaperSizeInterface' === $configurationMap['parametersType'][0]
                        ? PaperSize::class
                        : $configurationMap['parametersType'][0]
                    ;

                    $builder->{$configurationMap['method']}($class::from($value));
                } else {
                    $builder->{$configurationMap['method']}($value);
                }
            } else {
                $builder->{$configurationMap['method']}(...$value);
            }
        }
    }
}
