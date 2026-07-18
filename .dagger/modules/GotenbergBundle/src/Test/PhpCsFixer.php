<?php

declare(strict_types=1);

namespace DaggerModule\Test;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Changeset;
use Dagger\Container;
use Dagger\Directory;
use GraphQL\Exception\QueryError;
use function Dagger\dag;

#[DaggerObject]
final class PhpCsFixer
{
    private Container $symfonyContainer;

    public function __construct(
        #[DefaultPath('.')]
        private Directory $source,
        Container $symfonyContainer,
    ) {
        $this->symfonyContainer = $symfonyContainer
            ->withExec(['composer', 'global', 'require', 'friendsofphp/php-cs-fixer'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Apply changes from php-cs-fixer.')]
    public function fix(): Changeset
    {
        $directoryFormatter = static function (Directory $directory): Directory {
            $result = dag()->directory();

            $paths = [
                './.dagger/src',
                './bin',
                './config',
                './docs',
                './src',
                './tests',
            ];

            foreach ($paths as $path) {
                $result = $result->withDirectory($path, $directory->directory($path));
            }

            return $result;
        };

        $changedSource = $directoryFormatter($this->symfonyContainer
            ->withExec(['php-cs-fixer', 'fix', '--diff'])
            ->directory('./'),
        );

        return $changedSource->changes($directoryFormatter($this->source));
    }

    #[DaggerFunction]
    #[Doc('See diff from php-cs-fixer.')]
    public function diff(): string
    {
        return $this->fix()->asPatch()->contents();
    }

    #[DaggerFunction]
    #[Doc('Throw an error if php-cs-fixer found some issues.')]
    public function check(): void
    {
        $exec = $this->symfonyContainer
            ->withExec(['php-cs-fixer', 'fix', '--dry-run', '--diff', '--verbose'])
        ;

        $exitCode = $exec->exitCode();

        if (0 !== $exitCode) {
            throw new QueryError(['errors' => [[
                'message' => 'Please run "dagger call php-cs-fixer fix" to fix the issues.',
                'extensions' => [
                    'exitCode' => $exitCode,
                    'stdout' => $exec->stdout(),
                    'stderr' => $exec->stderr(),
                ]],
            ]]);
        }
    }
}
