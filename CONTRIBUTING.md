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

### Run Tests

```shell
$ ./vendor/bin/phpunit
```

### Run Tests with Coverage (optional)

```shell
$ ./vendor/bin/phpunit --coverage-text
```

## Quality Assurance

Maintain high code quality by following these steps before submitting a pull request:

### Code Linting

Check your code for style violations:

```shell
$ PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer check --diff
```

Eventually, you can fix the issues automatically:

```shell
$ PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix --diff
```

### Static Analysis

```shell
$ php -dmemory_limit=-1 ./vendor/bin/phpstan analyse --debug
```

Detect potential issues in your code.

### Dependencies

```shell
$ ./vendor/bin/composer-dependency-analyser --show-all-usages
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
$ php ./docs/generate.php
``` 


---

Thank you for contributing to GotenbergBundle!
