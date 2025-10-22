# FailOnTrait

With this trait, you can use the following methods:

- [failOnHttpStatusCodes](#failOnHttpStatusCodes)
- [failOnResourceHttpStatusCodes](#failOnResourceHttpStatusCodes)
- [failOnResourceLoadingFailed](#failOnResourceLoadingFailed)
- [failOnConsoleExceptions](#failOnConsoleExceptions)

## failOnHttpStatusCodes

default: `[499,599]`

To return a 409 Conflict response if the HTTP status code from the main
page is not acceptable.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->failOnHttpStatusCodes([401, 403])
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium).

## failOnResourceHttpStatusCodes

default: `None`

Return a 409 Conflict response if the HTTP status code from at least one resource
is not acceptable.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->failOnResourceHttpStatusCodes([401, 403])
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#invalid-http-status-codes-chromium).

## failOnResourceLoadingFailed

default: `false`

Return a 409 Conflict response if there are exceptions  to load at least one resource
in the Chromium.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->failOnResourceLoadingFailed()
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#network-errors-chromium).

## failOnConsoleExceptions

default: `false`

Return a 409 Conflict response if there are exceptions in the Chromium
console.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            ->html()
            ->content('content.html.twig', [
                'my_var' => 'value'
            ])
            ->failOnConsoleExceptions()
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#console-exceptions-chromium).
