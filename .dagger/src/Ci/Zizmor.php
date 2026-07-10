<?php

declare(strict_types=1);

namespace DaggerModule\Ci;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\Doc;
use Dagger\Attribute\ListOfType;
use Dagger\Changeset;
use Dagger\Container;
use Dagger\Directory;
use Dagger\ReturnType;
use Dagger\Secret;
use function Dagger\dag;

#[DaggerObject]
final class Zizmor
{
    private Container $zizmorContainer;

    public function __construct(
        private Directory $source,
        Secret|null $ghAuthToken = null,
    ) {
        $zizmorContainer = dag()->container()->from('ghcr.io/zizmorcore/zizmor:latest');

        $zizmorContainer = $zizmorContainer
            ->withMountedDirectory('/app', $this->source)
            ->withMountedCache('/zizmor-cache', dag()->cacheVolume('zizmor'))
            ->withWorkdir('/app')
        ;

        if (null !== $ghAuthToken) {
            $zizmorContainer = $zizmorContainer
                ->withSecretVariable('ZIZMOR_GITHUB_TOKEN', $ghAuthToken)
            ;
        }

        $this->zizmorContainer = $zizmorContainer;
    }

    #[DaggerFunction]
    public function do(
        #[ListOfType('string')]
        array $args
    ): Container {
        return $this->zizmorContainer
            ->withExec([
                'zizmor',
                ...$args,
            ])
        ;
    }

    #[DaggerFunction]
    public function check(string|null $format = null): Container
    {
        $zizmorCall = [
            'zizmor',
            '.',
            '--cache-dir=/zizmor-cache',
        ];

        if (null !== $format) {
            $zizmorCall[] = "--format={$format}";
        }

        return $this->zizmorContainer
            ->withExec($zizmorCall)
        ;
    }

    #[DaggerFunction]
    #[Doc('Automatically fix what can be fixed. You should run "check" to see remaining manual actions.')]
    public function autofix(): Changeset
    {
        $changedSource = $this->zizmorContainer
            ->withExec([
                'zizmor',
                '.',
                '--cache-dir=/zizmor-cache',
                '--fix=all',
            ], expect: ReturnType::SUCCESS)
            ->directory('/app')
        ;

        return $changedSource->changes($this->source);
    }
}
