<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\Doc;
use Dagger\Attribute\ReturnsListOfType;
use Dagger\Container;
use Dagger\Service;
use function Amp\async;
use function Amp\Future\await;

#[DaggerObject]
final class IntegrationTestsGotenbergBundle
{
    public function __construct(
        private readonly Container $symfonyContainer,
        private readonly Service $gotenbergService,
        private readonly string $gotenbergVersion,
        private readonly string|null $gotenbergVariant = null,
    ) {
    }

    #[DaggerFunction]
    #[Doc('Output the versions used for integration tests.')]
    #[ReturnsListOfType('string')]
    public function versions(): array
    {
        $phpVersion = $this->symfonyContainer->withExec(['php', '-v'])->stdout();
        $composerPackages = $this->symfonyContainer->withExec(['composer', 'show'])->stdout();

        return [
            'Integration Versions Debug:',
            '===========================',
            \sprintf('Gotenberg Version: %s', $this->gotenbergVersion),
            \sprintf('Gotenberg Variant: %s', $this->gotenbergVariant ?? 'full'),
            '',
            'PHP Version Debug:',
            '==================',
            $phpVersion,
            '',
            'Composer Packages:',
            '==================',
            $composerPackages,
        ];
    }

    #[DaggerFunction]
    #[Doc('Get the integration container for tests.')]
    public function terminal(): Container
    {
        return $this->symfonyContainer->terminal();
    }

    #[DaggerFunction]
    #[Doc('Checks that the bound Gotenberg service is reachable from the test container.')]
    public function health(): string
    {
        $script = <<<'PHP'
            $baseUri = getenv("GOTENBERG_BASE_URI");
            if (false === $baseUri || "" === $baseUri) {
                fwrite(STDERR, "GOTENBERG_BASE_URI is not set\n");
                exit(1);
            }
            $version = @file_get_contents($baseUri . "/version");
            if (false === $version) {
                $error = error_get_last();
                fwrite(STDERR, sprintf("Gotenberg is unreachable at %s: %s\n", $baseUri, $error["message"] ?? "unknown error"));
                exit(1);
            }
            echo "ok";
            PHP;

        return $this->symfonyContainer
            ->withExec(['php', '-r', $script])
            ->stdout()
        ;
    }

    #[DaggerFunction]
    #[Doc('Reads the raw /version response directly from the bound Gotenberg service.')]
    public function rawVersion(): string
    {
        return trim($this->symfonyContainer
            ->withExec(['php', '-r', 'echo file_get_contents(getenv("GOTENBERG_BASE_URI")."/version");'])
            ->stdout());
    }

    #[DaggerFunction]
    #[Doc('Run the version fetch integration tests.')]
    public function version(): string
    {
        return $this->symfonyContainer
            ->withExec(['./vendor/bin/phpunit', '--testsuite', 'integration', '--filter', 'HttpVersionFetcherTest'])
            ->stdout()
        ;
    }

    #[DaggerFunction]
    #[Doc('Run all integration PHPUnit tests.')]
    public function phpunit(string $filter = '', bool $displaySkipped = false): string
    {
        $exec = ['./vendor/bin/phpunit', '--testsuite', 'integration'];
        if ('' !== $filter) {
            $exec[] = "--filter={$filter}";
        }
        if ($displaySkipped) {
            $exec[] = '--display-skipped';
        }

        return $this->symfonyContainer
            ->withExec($exec)
            ->stdout()
        ;
    }

    #[DaggerFunction]
    #[Doc('Run the integration workflow.')]
    #[ReturnsListOfType('string')]
    public function all(): array
    {
        $outputs = [];

        $outputs[] = async(fn (): array => [
            '  >> Checking Gotenberg health...',
            $this->health(),
        ]);

        $outputs[] = async(fn (): array => [
            '  >> Fetching Gotenberg version...',
            $this->version(),
        ]);

        $outputs[] = async(fn (): array => [
            '  >> Running integration PHPUnit tests...',
            $this->phpunit(),
        ]);

        $title = \sprintf('Running integration tests for Gotenberg %s (%s)', $this->gotenbergVersion, $this->gotenbergVariant ?? 'full');

        $result = [
            $title,
            str_repeat('=', \strlen($title))."\n",
        ];

        foreach (await($outputs) as [$label, $output]) {
            $result[] = $label;
            $result[] = $output;
        }

        return $result;
    }
}
