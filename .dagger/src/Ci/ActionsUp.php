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
        $actionsUpContainer = $this->actionsUpContainer
            ->withExec([
                'npx',
                '-y',
                'actions-up',
                '--dry-run',
                '--json',
            ])
        ;

        $json = $actionsUpContainer->stdout();

        /** @var array{
         *     summary: array {
         *         totalBreakingUpdates: positive-int,
         *         totalCompositeActions: positive-int,
         *         totalWorkflows: positive-int,
         *         totalActionsChecked: positive-int,
         *         totalBlockedByMode: positive-int,
         *         totalActions: positive-int,
         *         totalUpdates: positive-int,
         *         totalSkipped: positive-int,
         *     }
         * } $report */
        $report = json_decode($json, true);

        $count = $report['summary']['totalUpdates'];

        if ($count > 0) {
            $rawOutput = $this->actionsUpContainer
                ->withExec([
                    'npx',
                    '-y',
                    'actions-up',
                    '--dry-run',
                    '--yes',
                ])
                ->stdout()
            ;
            throw new \RuntimeException("Some ({$count}) GitHub actions require updates.\n\n{$rawOutput}");
        }

        return $actionsUpContainer;
    }

    #[DaggerFunction]
    #[Doc('Automatically fix what can be fixed.')]
    public function autofix(): Changeset
    {
        $changedSource = $this->actionsUpContainer
            ->withExec([
                'npx',
                '-y',
                'actions-up',
                '--yes',
            ])
            ->directory('/app')
        ;

        return $changedSource->changes($this->source);
    }
}
