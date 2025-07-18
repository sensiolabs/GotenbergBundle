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
    public function __construct(
        private readonly Container $symfonyContainer,
    ) {
    }

    #[DaggerFunction]
    #[Doc('Validate composer dependencies.')]
    public function validateDependencies(): Container
    {
        return $this->symfonyContainer
            ->withExec(['./vendor/bin/composer-dependency-analyser', '--show-all-usages'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Run phpunit tests.')]
    public function phpunit(): Container
    {
        return $this->symfonyContainer
            ->withExec(['./vendor/bin/phpunit', '--display-deprecations'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Run PHPStan.')]
    public function phpstan(): Container
    {
        return $this->symfonyContainer
            ->withExec(['php', '-dmemory_limit=-1', './vendor/bin/phpstan', 'analyse'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Validate PHP-CS-Fixer.')]
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
        $result = [];

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
