# CookieTrait

With this trait, you can use the following methods:

- [cookies](#cookies)
- [setCookie](#setCookie)
- [addCookies](#addCookies)
- [forwardCookie](#forwardCookie)

## cookies

default: `None`

Cookies to store in the Chromium cookie jar.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->cookies([[
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]])
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#cookies-chromium).

## setCookie

If you want to add cookies and delete the ones already loaded in the
configuration .

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->setCookie([
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ])
            ->generate()
            ->stream()
        ;
    }
}
```

## addCookies

If you want to add cookies from the ones already loaded in the
configuration.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->addCookies([[
                'name' => 'my_cookie',
                'value' => 'symfony',
                'domain' => 'symfony.com',
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]])
            ->generate()
            ->stream()
        ;
    }
}
```

## forwardCookie

If you want to forward cookies from the current .request

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->forwardCookie('my_cookie')
            ->generate()
            ->stream()
        ;
    }
}
```
