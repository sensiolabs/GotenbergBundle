<?php

declare(strict_types=1);

namespace DaggerModule\Ci;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\Doc;
use Dagger\Changeset;
use Dagger\Container;
use Dagger\Directory;
use function Dagger\dag;

#[DaggerObject]
final class ActionsUp
{
    private Container $actionsUpContainer;

    public function __construct(
        private Directory $source,
    ) {
        $this->actionsUpContainer = dag()->container()->from('node:26-alpine')
            ->withMountedDirectory('/app', $source)
            ->withWorkdir('/app')
        ;
    }

    #[DaggerFunction]
    public function check(): Container
    {
        return $this->actionsUpContainer
            ->withExec([
                'npx',
                'actions-up',
                '--yes',
                '--dry-run',
            ])
        ;
    }

    #[DaggerFunction]
    #[Doc('Automatically fix what can be fixed.')]
    public function autofix(): Changeset
    {
        $changedSource = $this->actionsUpContainer
            ->withExec([
                'npx',
                'actions-up',
                '--yes',
            ])
            ->directory('/app')
        ;

        return $changedSource->changes($this->source);
    }
}
