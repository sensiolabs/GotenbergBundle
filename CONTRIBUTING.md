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

```
git clone https://github.com/sensiolabs/GotenbergBundle.git
cd GotenbergBundle
```

### Install Dependencies

```
composer install
```

### Configure Environment

Copy the example environment file and adjust configurations as needed.

```
cp .env.example .env
```

Update .env with your local settings.

## Testing

Ensure your changes work as expected by running the test suite:

### Run Tests

```
./vendor/bin/phpunit
```

### Run Tests with Coverage (optional)

```
./vendor/bin/phpunit --coverage-text
```

## Quality Assurance

Maintain high code quality by following these steps before submitting a pull request:

### Code Linting

```
./vendor/bin/phpcs
```

Ensure your code adheres to the coding standards.

### Static Analysis

```
./vendor/bin/phpstan analyse
```

Detect potential issues in your code.

### Fix Issues

Address any warnings or errors reported by the tools above.

## Documentation

The project documentation is partially built from the source code. 

> [!IMPORTANT]
> When you make changes to the codebase, update the documentation accordingly.

### Update the documentation

```
php ./docs/generate.php
``` 


---

Thank you for contributing to GotenbergBundle!
