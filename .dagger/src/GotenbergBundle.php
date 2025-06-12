<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Attribute\ListOfType;
use Dagger\Attribute\ReturnsListOfType;
use Dagger\Container;
use Dagger\Directory;
use Dagger\Service;
use function Dagger\dag;

#[DaggerObject]
#[Doc('Module for GotenbergBundle')]
class GotenbergBundle
{
    #[DaggerFunction]
    #[Doc('Returns a Gotenberg container.')]
    public function gotenbergContainer(
        string $gotenbergVersion = '8.0',
    ): Container {
        return dag()
            ->container()
            ->from("gotenberg/gotenberg:{$gotenbergVersion}")
        ;
    }

    #[DaggerFunction]
    #[Doc('Returns a Gotenberg service.')]
    public function gotenbergService(
        string $gotenbergVersion = '8.0',
    ): Service {
        return $this->gotenbergContainer($gotenbergVersion)
            ->withExposedPort(3000)
            ->asService()
        ;
    }

    #[DaggerFunction]
    #[Doc('Returns a PHP container.')]
    public function phpContainer(
        #[DefaultPath('.')]
        Directory $source,

        string $phpVersion = '8.4',
    ): Container {
        $aptCache = dag()->cacheVolume("apt-cache-{$phpVersion}");
        $composerBin = dag()->container()->from('composer/composer')->file('/usr/bin/composer');

        return dag()
            ->container()
            ->from("php:{$phpVersion}")
            ->withWorkdir('/GotenbergBundle')
            ->withMountedDirectory('/GotenbergBundle', $source)
            ->withMountedFile('/usr/bin/composer', $composerBin)
            ->withEnvVariable('COMPOSER_ALLOW_SUPERUSER', '1')
            ->withMountedCache('/var/cache/apt/archives', $aptCache)
            ->withExec(['apt', 'update'])
            ->withExec(['apt', 'install', '--yes',
                'git',
                'zip',
            ])
        ;
    }

    #[DaggerFunction]
    #[Doc('Returns a PHP container with symfony set to the desired version.')]
    public function symfonyContainer(
        #[DefaultPath('.')]
        Directory $source,

        string $phpVersion = '8.4',
        string $symfonyVersion = '7.3',
        Container|null $phpContainer = null,
    ): Container {
        $phpContainer ??= $this->phpContainer($source, $phpVersion);

        $vendorCache = dag()->cacheVolume("php-{$phpVersion}-symfony-{$symfonyVersion}-vendor-cache");

        return $phpContainer
            ->withExec(['composer', 'global', 'config', '--no-plugins', 'allow-plugins.symfony/flex', 'true'])
            ->withExec(['composer', 'global', 'require', 'symfony/flex'])
            ->withEnvVariable('SYMFONY_REQUIRE', $symfonyVersion)
            ->withMountedCache('/GotenbergBundle/vendor', $vendorCache)
            ->withExec(['composer', 'update'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Runs PHPUnit tests and returns the container in which it ran.')]
    public function testPhpunitUnit(
        #[DefaultPath('.')]
        Directory $source,

        string $phpVersion = '8.4',
        string $symfonyVersion = '7.3',
        Container|null $symfonyContainer = null,
    ): Container {
        $symfonyContainer ??= $this->symfonyContainer($source, $phpVersion, $symfonyVersion);

        return $symfonyContainer
            ->withExec(['./vendor/bin/phpunit', '--display-deprecations'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Runs composer dependency analyser and returns the container in which it ran.')]
    public function testValidateDependencies(
        #[DefaultPath('.')]
        Directory $source,

        string $phpVersion = '8.4',
        string $symfonyVersion = '7.3',
        Container|null $symfonyContainer = null,
    ): Container {
        $symfonyContainer ??= $this->symfonyContainer($source, $phpVersion, $symfonyVersion);

        return $symfonyContainer
            ->withExec(['./vendor/bin/composer-dependency-analyser', '--show-all-usages'])
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
    #[Doc('Execute all tests.')]
    #[ReturnsListOfType('string')]
    public function tests(
        #[DefaultPath('.')]
        Directory $source,

        string $phpVersion = '8.4',
        string $symfonyVersion = '7.3',
    ): array {
        $result = [];

        $symfonyContainer = $this->symfonyContainer($source, $phpVersion, $symfonyVersion);

        $result[] = $this->testPhpunitUnit($source, $phpVersion, $symfonyVersion, $symfonyContainer)->stdout();
        $result[] = $this->testValidateDependencies($source, $phpVersion, $symfonyVersion, $symfonyContainer)->stdout();

        return $result;
    }

    #[DaggerFunction]
    #[Doc('Execute all tests within matrix (PHP version, Symfony version.')]
    #[ReturnsListOfType('string')]
    public function testsMatrix(
        #[DefaultPath('.')]
        Directory $source,
    ): array {
        $result = [];
        foreach (['8.2', '8.3', '8.4'] as $phpVersion) {
            foreach (['6.4.*', '7.2.*', '7.3.*'] as $symfonyVersion) {
                $result[] = "PHP {$phpVersion}, Symfony {$symfonyVersion}";
                $symfonyContainer = $this->symfonyContainer($source, $phpVersion, $symfonyVersion);

                $result[] = $this->testPhpunitUnit($source, $phpVersion, $symfonyVersion, $symfonyContainer)->stdout();
                $result[] = $this->testValidateDependencies($source, $phpVersion, $symfonyVersion, $symfonyContainer)->stdout();
            }
        }

        return $result;
    }
}
