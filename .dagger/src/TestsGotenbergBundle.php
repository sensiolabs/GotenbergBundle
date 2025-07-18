<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\Doc;
use Dagger\Attribute\ReturnsListOfType;
use Dagger\Container;

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
        return $this->symfonyVersion ??= $this->symfonyContainer->envVariable('SYMFONY_VERSION');
    }

    #[DaggerFunction]
    #[Doc('Get the container for tests.')]
    public function terminal(): Container
    {
        return $this->symfonyContainer->terminal();
    }

    #[DaggerFunction]
    #[Doc('Validate composer dependencies and returns the container it ran in.')]
    public function validateDependencies(): Container
    {
        return $this->symfonyContainer
            ->withExec(['./vendor/bin/composer-dependency-analyser', '--show-all-usages'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Run phpunit tests and returns the container it ran in.')]
    public function phpunit(): Container
    {
        return $this->symfonyContainer
            ->withExec(['./vendor/bin/phpunit', '--display-deprecations'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Run PHPStan and returns the container it ran in.')]
    public function phpstan(): Container
    {
        return $this->symfonyContainer
            ->withExec(['php', '-dmemory_limit=-1', './vendor/bin/phpstan', 'analyse'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Validate PHP-CS-Fixer and returns the container it ran in.')]
    public function phpCsFixer(): Container
    {
        return $this->symfonyContainer
            ->withExec(['./vendor/bin/php-cs-fixer', 'check', '-v'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Run all tests.')]
    #[ReturnsListOfType('string')]
    public function all(): array
    {
        $title = "Running tests for PHP {$this->getPhpVersion()}, Symfony {$this->getSymfonyVersion()}";

        $result = [
            $title,
            str_repeat('=', \strlen($title))."\n",
        ];

        $result[] = '  >> Running PHPUnit tests...';
        $result[] = $this->phpunit()->stdout();

        $result[] = '  >> Validating dependencies...';
        $result[] = $this->validateDependencies()->stdout();

        $result[] = '  >> Checking code style...';
        $result[] = $this->phpCsFixer()->stdout();

        $result[] = '  >> Checking phpstan...';
        $result[] = $this->phpstan()->stdout();

        return $result;
    }
}
