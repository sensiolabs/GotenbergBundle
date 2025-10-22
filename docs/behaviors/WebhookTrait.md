# WebhookTrait

With this trait, you can use the following methods:

- [webhook](#webhook)
- [webhookUrl](#webhookUrl)
- [webhookErrorUrl](#webhookErrorUrl)
- [webhookExtraHeaders](#webhookExtraHeaders)
- [webhookRoute](#webhookRoute)
- [webhookErrorRoute](#webhookErrorRoute)
- [webhookConfiguration](#webhookConfiguration)

## webhook

Sets the full configuration for your webhook.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            // Your builder call as ->html() and the rest of your configuration code
            ->webhook([
                'config_name' => 'my_config',
                'success' => [
                    'url' =>'https://my.webhook.url/success',
                    // 'route' =>'my_route_success', url or route
                    'method' => 'POST'
                ],
                'error' => [
                    'url' =>'https://my.webhook.url/error',
                    // 'route' =>'my_route_error', url or route
                    'method' => 'POST'
                ]
            ])
            ->generate()
            ->stream()
         ;
    }
}
```

## webhookUrl

Sets the webhook for cases of success.
Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            // Your builder call as ->html() and the rest of your configuration code
            ->webhookUrl('https://my.webhook.url', 'PUT')
            ->generate()
            ->stream()
         ;
    }
}
```

## webhookErrorUrl

Sets the webhook for cases of error.
Optionally sets a custom HTTP method for such endpoint among : POST, PUT or PATCH.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            // Your builder call as ->html() and the rest of your configuration code
            ->webhookErrorUrl('https://my.webhook.url', 'PUT')
            ->generate()
            ->stream()
         ;
    }
}
```

## webhookExtraHeaders

Extra headers that will be provided to the webhook endpoint. May it either be Success or Error.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg->html()
            // Your builder call as ->html() and the rest of your configuration code
            ->webhookUrl('https://my.webhook.url')
            ->webhookExtraHeaders([
                'Authorization' => 'Bearer my-secret-token',
                'X-Custom-Header' => 'CustomValue',
            ])
            ->generate()
            ->stream()
         ;
    }
}
```

## webhookRoute

Sets the webhook route with params and method for cases of success.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->webhookRoute('my_route_success', ['foo' => 'bar'], 'PUT')
            ->generate()
            ->stream()
         ;
    }
}
```

## webhookErrorRoute

Default: `None`

Sets the webhook route with params and method for cases of error.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->webhookErrorRoute('my_route_error', ['foo' => 'bar'], 'PUT')
            ->generate()
            ->stream()
         ;
    }
}
```

## webhookConfiguration

Providing an existing $name from the configuration file, it will correctly
set both success and error webhook URLs as well as extra_http_headers if defined.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\Enum\PdfFormat;use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->webhookConfiguration('my_webhook_config')
            ->generate()
            ->stream()
         ;
    }
}
```
