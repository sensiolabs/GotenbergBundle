# DownloadFromTrait

With this trait, you can use the following method:

- [downloadFrom](#downloadFrom)

## downloadFrom

> [!WARNING]
> URL of the file. It MUST return a `Content-Disposition` header with a filename parameter.

To download files resource from URLs.

```php
namespace App\Controller;

use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class YourController
{
    public function yourControllerMethod(GotenbergPdfInterface $gotenberg): Response
    {
        return $gotenberg
            // Your builder call as ->html() and the rest of your configuration code
            ->downloadFrom([
                [
                    'url' => 'http://example.com/url/to/file',
                    'extraHttpHeaders' =>
                    [
                        'MyHeader' => 'MyValue',
                    ],
                ],
                [
                    'url' => 'http://example.com/url/to/file',
                    'extraHttpHeaders' =>
                    [
                        'MyHeaderOne' => 'MyValue',
                        'MyHeaderTwo' => 'MyValue',
                    ],
                ],
            ])
            ->generate()
            ->stream()
        ;
    }
}
```

> [!TIP]
> For more information go to [Gotenberg documentations](https://gotenberg.dev/docs/routes#download-from).

