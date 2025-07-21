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
use function Dagger\dag;

#[DaggerObject]
#[Doc('Module for GotenbergBundle')]
class GotenbergBundle
{
    private const SYMFONY_VERSIONS = [
        '6.4.*' => ['8.1', '8.2', '8.3', '8.4', '8.5-rc'],
        '7.2.*' => ['8.2', '8.3', '8.4', '8.5-rc'],
        '7.3.*' => ['8.2', '8.3', '8.4', '8.5-rc'],
        '7.4.x-dev' => ['8.2', '8.3', '8.4', '8.5-rc'],
        '8.0.x-dev' => ['8.4', '8.5-rc'],
    ];

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
        #[DefaultPath('.')]
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
        #[DefaultPath('.')]
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

    #[DaggerFunction]
    #[Doc('Generates documentation and returns the Directory to export locally.')]
    public function generateDocs(
        #[DefaultPath('.')]
        Directory $source,

        string $phpVersion = '8.4',
        string $symfonyVersion = '7.3',
        Container|null $symfonyContainer = null,
    ): Directory {
        $symfonyContainer ??= $this->symfonyContainer($source, $phpVersion, $symfonyVersion);

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
        $result = [];

        foreach (self::SYMFONY_VERSIONS as $symfonyVersion => $phpVersions) {
            foreach ($phpVersions as $phpVersion) {
                $result[] = $this->test($source, $phpVersion, $symfonyVersion)->all();
            }
        }

        return array_merge(...$result);
    }
}
