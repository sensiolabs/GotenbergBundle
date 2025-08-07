<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Attribute\ReturnsListOfType;
use Dagger\Container;
use Dagger\Directory;
use Dagger\Service;
use function Amp\async;
use function Amp\Future\await;
use function Dagger\dag;

#[DaggerObject]
#[Doc('Module for GotenbergBundle')]
class GotenbergBundle
{
    private function gotenbergContainer(
        string $gotenbergVersion = '8.0',
    ): Container {
        return dag()
            ->container()
            ->from("gotenberg/gotenberg:{$gotenbergVersion}")
        ;
    }

    private function gotenbergService(
        string $gotenbergVersion = '8.0',
    ): Service {
        return $this->gotenbergContainer($gotenbergVersion)
            ->withExposedPort(3000)
            ->asService()
        ;
    }

    private function phpContainer(
        Directory $source,
        string $phpVersion = '8.4',
    ): Container {
        $aptCache = dag()->cacheVolume("apt-cache-{$phpVersion}");
        $composerBin = dag()->container()->from('composer/composer')->file('/usr/bin/composer');

        return dag()
            ->container()
            ->from("php:{$phpVersion}")
            ->withMountedCache('/var/cache/apt/archives', $aptCache)
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
    }

    private function symfonyContainer(
        Directory $source,
        string $phpVersion = '8.4',
        string $symfonyVersion = '7.3',
        Container|null $phpContainer = null,
    ): Container {
        $phpContainer ??= $this->phpContainer($source, $phpVersion);

        $vendorCache = dag()->cacheVolume("php-{$phpVersion}-symfony-{$symfonyVersion}-vendor-cache");

        return $phpContainer
            ->withMountedCache('/GotenbergBundle/vendor', $vendorCache)
            ->withEnvVariable('SYMFONY_REQUIRE', $symfonyVersion)
            ->withExec(['composer', 'global', 'config', '--no-plugins', 'allow-plugins.symfony/flex', 'true'])
            ->withExec(['composer', 'global', 'require', 'symfony/flex'])
            ->withExec(['composer', 'update'])
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
            yield [$row['symfony-version'], $row['php']];
        }
    }

    #[DaggerFunction]
    #[Doc('Generates documentation and returns the Directory to export locally.')]
    public function generateDocs(
        #[DefaultPath('.')]
        Directory $source,
        Container|null $symfonyContainer = null,
    ): Directory {
        $symfonyContainer ??= $this->symfonyContainer($source);

        return $symfonyContainer
            ->withExec(['./docs/generate.php'])
            ->directory('./docs')
        ;
    }

    #[DaggerFunction]
    #[Doc('Provide a container with all dependencies installed and ready to run tests.')]
    public function test(
        #[DefaultPath('.')]
        Directory $source,

        string $phpVersion = '8.4',
        string $symfonyVersion = '7.3',
        Container|null $symfonyContainer = null,
    ): TestsGotenbergBundle {
        $symfonyContainer ??= $this->symfonyContainer($source, $phpVersion, $symfonyVersion);

        return new TestsGotenbergBundle($symfonyContainer);
    }

    #[DaggerFunction]
    #[Doc('Execute all tests within matrix (PHP version, Symfony version).')]
    #[ReturnsListOfType('string')]
    public function testsMatrix(
        #[DefaultPath('.')]
        Directory $source,
    ): array {
        $tests = [];

        foreach ($this->getMatrix() as [$symfonyVersion, $phpVersion]) {
            $tests[] = async(fn () => $this->test($source, $phpVersion, $symfonyVersion)->all());
        }

        $result = [];

        foreach (await($tests) as $test) {
            $result[] = $test;
        }

        return array_merge(...$result);
    }
}
