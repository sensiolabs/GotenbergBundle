<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Attribute\Ignore;
use Dagger\Attribute\ReturnsListOfType;
use Dagger\Changeset;
use Dagger\Container;
use Dagger\Directory;
use Dagger\Service;
use DaggerModule\Test\PhpCsFixer;
use function Amp\async;
use function Amp\Future\await;
use function Dagger\dag;

#[DaggerObject]
#[Doc('Module for GotenbergBundle')]
class GotenbergBundle
{
    private const DEFAULT_PHP_VERSION = '8.4';
    private const DEFAULT_SYMFONY_VERSION = '8.0.*';
    private const DEFAULT_GOTENBERG_VERSION = '8.0';

    private function gotenbergContainer(
        string $gotenbergVersion = self::DEFAULT_GOTENBERG_VERSION,
    ): Container {
        return dag()
            ->container()
            ->from("gotenberg/gotenberg:{$gotenbergVersion}")
        ;
    }

    private function gotenbergService(
        string $gotenbergVersion = self::DEFAULT_GOTENBERG_VERSION,
    ): Service {
        return $this->gotenbergContainer($gotenbergVersion)
            ->withExposedPort(3000)
            ->asService()
        ;
    }

    private function phpContainer(
        Directory $source,
        string $phpVersion = self::DEFAULT_PHP_VERSION,
    ): Container {
        $aptCache = dag()->cacheVolume("apt-cache-{$phpVersion}");
        $composerBin = dag()->container()->from('composer/composer:latest-bin')->file('/composer');

        $composerCache = dag()->cacheVolume('composer-cache');

        $phpContainer = dag()
            ->container()
            ->from("php:{$phpVersion}")
            ->withMountedCache('/var/cache/apt/archives', $aptCache)
            ->withMountedCache('/root/.composer/cache/files', $composerCache)
            ->withExec(['apt', 'update'])
            ->withExec(['apt', 'install', '--yes',
                'git',
                'zip',
            ])
            ->withMountedFile('/usr/bin/composer', $composerBin)
            ->withEnvVariable('COMPOSER_ALLOW_SUPERUSER', '1')
            ->withWorkdir('/GotenbergBundle')
            ->withMountedDirectory('/GotenbergBundle', $source)
        ;

        $globalDataDir = trim($phpContainer->withExec(['composer', 'global', 'config', 'data-dir'])->stdout());
        $globalBinDir = trim($phpContainer->withExec(['composer', 'global', 'config', 'bin-dir'])->stdout());

        return $phpContainer
            ->withEnvVariable(
                'PATH',
                "{$phpContainer->envVariable('PATH')}:{$globalDataDir}/{$globalBinDir}",
            )
        ;
    }

    private function symfonyContainer(
        Directory $source,
        string $phpVersion = self::DEFAULT_PHP_VERSION,
        string $symfonyVersion = self::DEFAULT_SYMFONY_VERSION,
        string $minimumStability = 'stable',
        Container|null $phpContainer = null,
    ): Container {
        $phpContainer ??= $this->phpContainer($source, $phpVersion);

        $minimumStability = trim($minimumStability);
        if ('' === $minimumStability) {
            $minimumStability = 'stable';
        }

        return $phpContainer
            ->withExec(['composer', 'global', 'config', '--no-plugins', 'allow-plugins.symfony/flex', 'true'])
            ->withExec(['composer', 'global', 'require', 'symfony/flex'])
            ->withEnvVariable('SYMFONY_REQUIRE', $symfonyVersion)
            ->withExec(['composer', 'config', 'minimum-stability', $minimumStability])
            ->withExec(['composer', 'update', '--prefer-dist', '--prefer-stable', '--no-progress'])
        ;
    }

    /**
     * @return \Generator<int, array{string, string}>
     */
    private function getMatrix(): \Generator
    {
        /** @var list<array{name: string, symfony-version: string, php: string, 'allow-failure': bool}> $matrix */
        $matrix = json_decode(file_get_contents(__DIR__.'/matrix-versions.json'), associative: true);

        foreach ($matrix as $row) {
            yield [$row['symfony-version'], $row['php'], $row['minimum-stability'] ?? 'stable'];
        }
    }

    #[DaggerFunction]
    #[Doc('Generates documentation and returns the ChangeSet to apply locally.')]
    public function generateDocs(
        #[DefaultPath('.')]
        #[Ignore(
            './.github/',
            './.phpunit.cache/',
            './.coverage/',
            './var/',
            './vendor/',
        )]
        Directory $source,
        Container|null $symfonyContainer = null,
    ): Changeset {
        $symfonyContainer ??= $this->symfonyContainer($source);

        $generatedDocs = dag()->directory()->withDirectory('./docs', $symfonyContainer
            ->withExec(['./docs/generate.php'])
            ->directory('./docs'),
        );

        return $generatedDocs->changes(dag()->directory()->withDirectory('./docs', $source->directory('./docs')));
    }

    #[DaggerFunction]
    #[Doc('Run php-cs-fixer. Returns the Directory diff.')]
    public function phpCsFixer(
        #[DefaultPath('.')]
        #[Ignore(
            './.github/',
            './.phpunit.cache/',
            './.coverage/',
            './var/',
            './vendor/',
        )]
        Directory $source,
        Container|null $symfonyContainer = null,
    ): PhpCsFixer {
        $symfonyContainer ??= $this->symfonyContainer($source, phpVersion: self::DEFAULT_PHP_VERSION);

        return new PhpCsFixer($source, $symfonyContainer);
    }

    #[DaggerFunction]
    #[Doc('Provide a container with all dependencies installed and ready to run tests.')]
    public function test(
        #[DefaultPath('.')]
        #[Ignore(
            './.github/',
            './.phpunit.cache/',
            './.coverage/',
            './var/',
            './vendor/',
        )]
        Directory $source,
        string $phpVersion = self::DEFAULT_PHP_VERSION,
        string $symfonyVersion = self::DEFAULT_SYMFONY_VERSION,
        string $minimumStability = 'stable',
        Container|null $symfonyContainer = null,
    ): TestsGotenbergBundle {
        $symfonyContainer ??= $this->symfonyContainer($source, $phpVersion, $symfonyVersion, $minimumStability);

        return new TestsGotenbergBundle($symfonyContainer);
    }

    #[DaggerFunction]
    #[Doc('Execute all tests within matrix (PHP version, Symfony version).')]
    #[ReturnsListOfType(TestsGotenbergBundle::class)]
    public function testsMatrix(
        #[DefaultPath('.')]
        #[Ignore(
            './.github/',
            './.phpunit.cache/',
            './.coverage/',
            './var/',
            './vendor/',
        )]
        Directory $source,
    ): array {
        $tests = [];

        foreach ($this->getMatrix() as [$symfonyVersion, $phpVersion, $minimumStability]) {
            $tests[] = async(fn () => $this->test($source, $phpVersion, $symfonyVersion, $minimumStability));
        }

        $result = [];

        foreach (await($tests) as $test) {
            $result[] = $test;
        }

        return $result;
    }
}
