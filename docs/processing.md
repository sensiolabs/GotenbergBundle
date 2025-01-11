# Processing

Let's say you want to save the PDF or Screenshot as a file, you will need to use a `Sensiolabs\GotenbergBundle\Processor\ProcessorInterface`.
To avoid loading the whole file content in memory you can stream it to the browser.

You can also hook on the stream and save the file chunk by chunk. To do so we leverage the [`->stream`](https://symfony.com/doc/current/http_client.html#streaming-responses) method from the HttpClientInterface and use a powerful feature from PHP : [`->send`](https://www.php.net/manual/en/generator.send.php).

## Using FileProcessor

Useful if you want to store the file.
Example when generating a PDF :
```php
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Processor\FileProcessor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/my-pdf', name: 'my_pdf')]
public function pdf(GotenbergPdfInterface $gotenbergPdf): Response
{
    return $gotenbergPdf->html()
        //
        ->fileName('my_pdf')
        ->processor(new FileProcessor(
            new Filesystem(),
            $this->getParameter('kernel.project_dir').'/var/pdf',
        ))
        ->generate()
        ->stream()
    ;
}
```

This will save the file under `%kernel.project_dir%/var/pdf/my_pdf.pdf` once the file has been fully streamed to the browser.
If you are not streaming to a browser you can still process the file this way :

```php
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Processor\FileProcessor;
use Symfony\Component\Filesystem\Filesystem;

class SomeService
{
    public function __construct(private readonly GotenbergPdfInterface $gotenbergPdf) {}
    
    public function pdf(): \SplFileInfo
    {
        return $this->gotenbergPdf->html()
            //
            ->fileName('my_pdf')
            ->processor(new FileProcessor(
                new Filesystem(),
                $this->getParameter('kernel.project_dir').'/var/pdf',
            ))
            ->generate()
            ->process()
        ;
    }
}
```

This will return a SplFileInfo of the generated file store at `%kernel.project_dir%/var/pdf/my_pdf.pdf`.

## Other processors

* `Sensiolabs\GotenbergBundle\Processor\ChainProcessor` : Apply multiple processor. Each chunk will be sent to each processor sequentially.
* `Sensiolabs\GotenbergBundle\Processor\NullProcessor` : Empty processor. Does nothing.

## Custom processor

A custom processor must implement `Sensiolabs\GotenbergBundle\Processor\ProcessorInterface` which require that your `__invoke` method is a `\Generator`. To receive a chunk you must assign `yield` to a variable like so : `$chunk = yield`.

The basic needed code is the following :

```php
do {
    $chunk = yield;
    // do something with it
} while (!$chunk->isLast());
```
