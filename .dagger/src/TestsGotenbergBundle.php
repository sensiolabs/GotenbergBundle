<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\Doc;
use Dagger\Attribute\ReturnsListOfType;
use Dagger\Container;
use function Amp\async;
use function Amp\Future\await;

#[DaggerObject]
final class TestsGotenbergBundle
{
    private string $phpVersion;
    private string $symfonyVersion;

    public function __construct(
        private readonly Container $symfonyContainer,
    ) {
    }

    private function getPhpVersion(): string
    {
        return $this->phpVersion ??= $this->symfonyContainer->envVariable('PHP_VERSION');
    }

    private function getSymfonyVersion(): string
    {
        return $this->symfonyVersion ??= $this->symfonyContainer->envVariable('SYMFONY_REQUIRE');
    }

    #[DaggerFunction]
    #[Doc('Output the versions used for tests.')]
    #[ReturnsListOfType('string')]
    public function versions(): array
    {
        $phpVersion = $this->symfonyContainer->withExec(['php', '-v'])->stdout();
        $composerPackages = $this->symfonyContainer->withExec(['composer', 'show'])->stdout();

        return [
            'PHP Version Debug:',
            '==================',
            $phpVersion,
            '',
            '',
            'Composer Packages:',
            '==================',
            $composerPackages,
        ];
    }

    #[DaggerFunction]
    #[Doc('Get the container for tests.')]
    public function terminal(): Container
    {
        return $this->symfonyContainer->terminal();
    }

    #[DaggerFunction]
    #[Doc('Validate composer dependencies and returns the container it ran in.')]
    public function validateDependencies(): string
    {
        return $this->symfonyContainer
            ->withExec(['./vendor/bin/composer-dependency-analyser', '--show-all-usages'])
            ->stdout()
        ;
    }

    #[DaggerFunction]
    #[Doc('Run phpunit tests and returns the container it ran in.')]
    public function phpunit(string $filter = ''): string
    {
        $exec = ['./vendor/bin/phpunit', '--display-all-issues'];
        if ('' !== $filter) {
            $exec[] = "--filter={$filter}";
        }

        return $this->symfonyContainer
            ->withExec($exec)
            ->stdout()
        ;
    }

    #[DaggerFunction]
    #[Doc('Run PHPStan and returns the container it ran in.')]
    public function phpstan(): string
    {
        return $this->symfonyContainer
            ->withExec(['php', '-dmemory_limit=-1', './vendor/bin/phpstan', 'analyse'])
            ->stdout()
        ;
    }

    #[DaggerFunction]
    #[Doc('Validate PHP-CS-Fixer and returns the container it ran in.')]
    public function phpCsFixer(): string
    {
        return $this->symfonyContainer
            ->withExec(['./vendor/bin/php-cs-fixer', 'check', '-v', '--diff'])
            ->stdout()
        ;
    }

    #[DaggerFunction]
    #[Doc('Validate all URL docs.')]
    public function validateUrlDoc(): string
    {
        return $this->symfonyContainer
            ->withExec(['php', './docs/ValidateUrlDoc.php'])
            ->stdout()
        ;
    }

    #[DaggerFunction]
    #[Doc('Run all tests.')]
    #[ReturnsListOfType('string')]
    public function all(): array
    {
        $outputs = [];

        $outputs[] = async(fn (): array => [
            '  >> Running PHPUnit tests...',
            $this->phpunit(),
        ]);

        $outputs[] = async(fn (): array => [
            '  >> Validating dependencies...',
            $this->validateDependencies(),
        ]);

        $outputs[] = async(fn (): array => [
            '  >> Checking code style...',
            $this->phpCsFixer(),
        ]);

        $outputs[] = async(fn (): array => [
            '  >> Checking phpstan...',
            $this->phpstan(),
        ]);

        $title = "Running tests for PHP {$this->getPhpVersion()}, Symfony {$this->getSymfonyVersion()}";

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
