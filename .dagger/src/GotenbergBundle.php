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
use Dagger\Secret;
use Dagger\Service;
use DaggerModule\Test\PhpCsFixer;
use function Amp\async;
use function Amp\Future\await;
use function Dagger\dag;

#[DaggerObject]
#[Doc('Module for GotenbergBundle')]
class GotenbergBundle
{
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
        string $phpVersion,
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
        string $phpVersion = '',
        string $symfonyVersion = '',
        string $minimumStability = 'stable',
        Container|null $phpContainer = null,
    ): Container {
        ['php' => $phpVersion, 'symfony' => $symfonyVersion] = $this->resolveVersions(trim($phpVersion), trim($symfonyVersion));

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
     * @return array<string, mixed>
     */
    private function fetchJson(string $url): array
    {
        return json_decode(file_get_contents($url), associative: true, flags: \JSON_THROW_ON_ERROR);
    }

    /**
     * Builds the matrix from the maintained Symfony versions (symfony.com) and the PHP versions
     * between the bundle minimum (composer.json) and the latest active one (php.net).
     *
     * @return list<array{name: string, symfony-version: string, php: string, minimum-stability: string, allow-failure: bool}>
     */
    private function getMatrix(Directory $source): array
    {
        $symfony = $this->fetchJson('https://symfony.com/releases.json');

        // e.g. ">=8.1" in composer.json and ["8.2", ..., "8.5"] on php.net give 8.1 to 8.5.
        $composerPhpRequirement = json_decode($source->file('composer.json')->contents(), associative: true)['require']['php'];
        preg_match('#\d+\.\d+#', $composerPhpRequirement, $minPhpVersion);
        $phpVersions = [$minPhpVersion[0]];
        foreach ($this->fetchJson('https://www.php.net/releases/active.php') as $activeVersions) {
            foreach (array_keys($activeVersions) as $activeVersion) {
                if (version_compare($activeVersion, $minPhpVersion[0], '>')) {
                    $phpVersions[] = $activeVersion;
                }
            }
        }
        usort($phpVersions, version_compare(...));

        // "8.2.0" -> "8.2"
        preg_match('#^\d+\.\d+#', $symfony['symfony_versions']['next'], $nextSymfonyVersion);

        $matrix = [];
        foreach ([...$symfony['supported_versions'], $nextSymfonyVersion[0]] as $symfonyVersion) {
            $isNext = $nextSymfonyVersion[0] === $symfonyVersion;
            // "8.4.0" -> "8.4"
            preg_match('#^\d+\.\d+#', $symfony['min_php_requirements'][$symfonyVersion], $minPhpVersion);

            foreach ($phpVersions as $phpVersion) {
                if (version_compare($phpVersion, $minPhpVersion[0], '<')) {
                    continue;
                }

                $matrix[] = [
                    'name' => "Symfony '{$symfonyVersion}' with PHP '{$phpVersion}'",
                    'symfony-version' => "{$symfonyVersion}.*",
                    'php' => $phpVersion,
                    'minimum-stability' => $isNext ? 'dev' : 'stable',
                    'allow-failure' => $isNext,
                ];
            }
        }

        return $matrix;
    }

    /**
     * Resolves the empty versions from symfony.com:
     * - Symfony: the latest stable version, or the highest supported version that runs on the given PHP version.
     * - PHP: the minimum PHP version of the Symfony version.
     *
     * @return array{php: string, symfony: string}
     */
    private function resolveVersions(string $phpVersion, string $symfonyVersion): array
    {
        if ('' !== $phpVersion && '' !== $symfonyVersion) {
            return ['php' => $phpVersion, 'symfony' => $symfonyVersion];
        }

        $symfony = $this->fetchJson('https://symfony.com/releases.json');

        if ('' !== $symfonyVersion) {
            // "8.1.*" -> "8.1"
            preg_match('#^\d+\.\d+#', $symfonyVersion, $matches);
            $symfonyMinorVersion = $matches[0];
        } elseif ('' === $phpVersion) {
            $symfonyMinorVersion = $symfony['latest_stable_version'];
        } else {
            // supported_versions is sorted from the lowest to the highest version.
            $versions = array_filter(
                $symfony['supported_versions'],
                static function (string $version) use ($symfony, $phpVersion): bool {
                    // "8.4.0" -> "8.4", as version_compare('8.4', '8.4.0') returns -1.
                    preg_match('#^\d+\.\d+#', $symfony['min_php_requirements'][$version], $matches);

                    return version_compare($phpVersion, $matches[0], '>=');
                },
            );
            $symfonyMinorVersion = end($versions);
        }

        if ('' === $phpVersion) {
            // "8.4.0" -> "8.4"
            preg_match('#^\d+\.\d+#', $symfony['min_php_requirements'][$symfonyMinorVersion], $matches);
            $phpVersion = $matches[0];
        }

        return ['php' => $phpVersion, 'symfony' => $symfonyVersion ?: "{$symfonyMinorVersion}.*"];
    }

    #[DaggerFunction]
    #[Doc('Output the test matrix (maintained Symfony versions x supported PHP versions) as JSON.')]
    public function matrix(
        #[DefaultPath('.')]
        #[Ignore(
            './.github/',
            './.phpunit.cache/',
            './.coverage/',
            './var/',
            './vendor/',
        )]
        Directory $source,
    ): string {
        return json_encode($this->getMatrix($source), \JSON_THROW_ON_ERROR);
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
        $symfonyContainer ??= $this->symfonyContainer($source);

        return new PhpCsFixer($source, $symfonyContainer);
    }

    #[DaggerFunction]
    #[Doc('Provide a container with all dependencies installed and ready to run tests.')]
    public function ci(
        #[DefaultPath('./.github/')]
        Directory $source,

        Secret|null $ghAuthToken = null,
    ): CiGotenbergBundle {
        return new CiGotenbergBundle($source, $ghAuthToken);
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
        #[Doc('Defaults to the minimum PHP version of the latest stable Symfony version.')]
        string $phpVersion = '',
        #[Doc('Defaults to the latest stable Symfony version.')]
        string $symfonyVersion = '',
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

        foreach ($this->getMatrix($source) as $row) {
            $tests[] = async(fn () => $this->test($source, $row['php'], $row['symfony-version'], $row['minimum-stability']));
        }

        $result = [];

        foreach (await($tests) as $test) {
            $result[] = $test;
        }

        return $result;
    }
}
