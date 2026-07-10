<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Changeset;
use Dagger\Directory;
use Dagger\Secret;
use DaggerModule\Ci\ActionsUp;
use DaggerModule\Ci\Zizmor;
use function Dagger\dag;

#[DaggerObject]
final class CiGotenbergBundle
{
    private Zizmor $zizmor;
    private ActionsUp $actionsUp;

    private Directory $app;

    public function __construct(
        #[DefaultPath('./.github/')]
        Directory $source,

        Secret|null $ghAuthToken = null,
    ) {
        $this->app = dag()->directory()->withDirectory('./.github', $source);

        $this->zizmor = new Zizmor($this->app, $ghAuthToken);
        $this->actionsUp = new ActionsUp($this->app);
    }

    #[DaggerFunction]
    #[Doc('Check if our CI is clean.')]
    public function zizmor(): Zizmor
    {
        return $this->zizmor;
    }

    #[DaggerFunction]
    #[Doc('Check if our CI is clean.')]
    public function actionsUp(): ActionsUp
    {
        return $this->actionsUp;
    }

    #[DaggerFunction]
    public function check(): string
    {
        $this->zizmor()->check()->sync();
        $this->actionsUp()->check()->sync();

        return ' >> ✅ All good !';
    }

    #[DaggerFunction]
    public function autofix(): Changeset
    {
        $zizmorFixes = $this->zizmor()->autofix();

        return (new ActionsUp(
            $this->app->withChanges($zizmorFixes),
        ))->autofix();
    }
}
