# Contributing to GotenbergBundle

Thank you for your interest in contributing to GotenbergBundle!


Your support helps make this project better for everyone.

## How to Contribute

### Report Issues

Found a bug or have a feature request? [Open an issue](https://github.com/sensiolabs/GotenbergBundle/issues) to let us
know.

### Submit Pull Requests

* Fork the repository.
* Create a new branch for your changes.
* Ensure your code follows the existing style and includes tests if applicable.
* Submit a pull request with a clear description of your changes.

## Installation

To set up the project locally for development:

### Clone the Repository

```shell
$ git clone https://github.com/sensiolabs/GotenbergBundle.git
$ cd GotenbergBundle
```

### Install Dependencies

```shell
$ composer install
```

## Testing

Ensure your changes work as expected by running the test suite:

### With dagger (recommended)
#### Requirements

Make sure you have [dagger >= v0.18.10](https://docs.dagger.io/install) installed. Then run

```shell
$ dagger develop
```

#### Run Tests

```shell
$ # Run the PHPUnit 'unit' test suite with specific symfony or / and php version
$ dagger call test --symfony-version '6.4.*' --php-version '8.2' phpunit

$ # Make sure all dependencies are explicitly added to composer.json
$ dagger call test --symfony-version '6.4.*' --php-version '8.2' validate-dependencies

$ # Generate the auto documentation for builders
$ dagger call generate-docs export --path ./docs

$ # Run all tests available with specific symfony / php versions
$ dagger call test --symfony-version '6.4.*' --php-version '8.2' all

$ # Run all tests available with all supported version of both PHP and Symfony
$ dagger call tests-matrix
```

About the list of flags available (`dagger call test --help` or `dagger call tests-matrix --help`) :

| flag                | description                                                                                                                                                          |
|---------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `--symfony-version` | Can be any SemVer compatible value (eg : `6.4.*`, `^6.4`, ...)                                                                                                       |
| `--php-version`     | Can be any tag from the [official PHP Docker image](https://github.com/docker-library/docs/blob/master/php/README.md#supported-tags-and-respective-dockerfile-links) |

Here is the list of all `dagger call` functions you can do :

```shell
$ dagger functions
Name            Description
generate-docs   Generates documentation and returns the Directory to export locally.
test            Provide a container with all dependencies installed and ready to run tests.
tests-matrix    Execute all tests within matrix (PHP version, Symfony version).
```

and here is the list of all tests available in `dagger call test` :

```shell
$ dagger functions test # e.g.: dagger call test phpunit
Name                    Description
all                     Run all tests.
php-cs-fixer            Validate PHP-CS-Fixer and returns the container it ran in.
phpstan                 Run PHPStan and returns the container it ran in.
phpunit                 Run phpunit tests and returns the container it ran in.
terminal                Get the container for tests.
validate-dependencies   Validate composer dependencies and returns the container it ran in.
```

### Without dagger
#### Run Tests with Coverage (optional)

```shell
$ ./vendor/bin/phpunit --coverage-text
```

## Quality Assurance

Maintain high code quality by following these steps before submitting a pull request:

### Code Linting

Check your code for style violations:

```shell
$ dagger call test php-cs-fixer
$ # or without dagger
$ ./vendor/bin/php-cs-fixer check --diff
```

Eventually, you can fix the issues automatically (without dagger):

```shell
$ ./vendor/bin/php-cs-fixer fix --diff
```

### Static Analysis

```shell
$ dagger call test phpstan
$ # or without dagger
$ php -dmemory_limit=-1 ./vendor/bin/phpstan analyse
```

Detect potential issues in your code.

### Dependencies

```shell
$ dagger call test validate-dependencies
$ # or without dagger
$ ./vendor/bin/composer-dependency-analyser
```

Detect potential issues in composer.json dependencies.

### Fix Issues

Address any warnings or errors reported by the tools above.

## Documentation

The project documentation is partially built from the source code.

> [!IMPORTANT]
> When you make changes to the codebase, update the documentation accordingly.

### Update the documentation

```shell
$ dagger call generate-docs export --path ./docs
```

---

Thank you for contributing to GotenbergBundle!
