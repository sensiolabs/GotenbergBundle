# Processing

Let's say you want to save the PDF or Screenshot as a file, you will need to use a `Sensiolabs\GotenbergBundle\Processor\ProcessorInterface`.
To avoid loading the whole file content in memory you can stream it to the browser.

You can also hook on the stream and save the file chunk by chunk. To do so we leverage the [`->stream`](https://symfony.com/doc/current/http_client.html#streaming-responses) method from the HttpClientInterface and use a powerful feature from PHP Generators : [`->send`](https://www.php.net/manual/en/generator.send.php).

## Using FileProcessor

Useful if you want to store the file in the local filesystem.
Example when generating a PDF :

```php
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Processor\FileProcessor;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/my-pdf', name: 'my_pdf')]
public function pdf(
    GotenbergPdfInterface $gotenbergPdf,
    Filesystem $filesystem,
    
    #[Autowire('%kernel.project_dir%/var/pdf')]
    string $pdfStorage,
): Response {
    return $gotenbergPdf->html()
        //
        ->fileName('my_pdf')
        ->processor(new FileProcessor(
            $filesystem,
            $pdfStorage,
        ))
        ->generate()
        ->stream()
    ;
}
```

This will save the file under `%kernel.project_dir%/var/pdf/my_pdf.pdf` once the file has been fully streamed to the browser.
If you are not streaming to a browser, you can still process the file using the `process` method instead of `stream` :

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

This will return a `SplFileInfo` of the generated file stored at `%kernel.project_dir%/var/pdf/my_pdf.pdf`.

## Other processors

* `Sensiolabs\GotenbergBundle\Processor\AsyncAwsProcessor` : Upload using the `async-aws/s3` package. Uploads using the (multipart upload)[https://docs.aws.amazon.com/AmazonS3/latest/userguide/mpuoverview.html] feature of S3. Returns a `AsyncAws\S3\Result\CompleteMultipartUploadOutput` object.
* `Sensiolabs\GotenbergBundle\Processor\FlysystemProcessor` : Upload using the `league/flysystem-bundle` package. Returns a `callable`. This callable will return the uploaded content.
* `Sensiolabs\GotenbergBundle\Processor\ChainProcessor` : Apply multiple processors. Each chunk will be sent to each processor sequentially. Return an array of vaues returned by chained processors.
* `Sensiolabs\GotenbergBundle\Processor\NullProcessor` : Empty processor. Does nothing. Returns `null`.
* `Sensiolabs\GotenbergBundle\Processor\TempfileProcessor` : Creates a temporary file and dump all chunks into it. Return a `ressource` of said `tmpfile()`.

## Custom processor

A custom processor must implement `Sensiolabs\GotenbergBundle\Processor\ProcessorInterface` which require that your `__invoke` method is a `\Generator`. To receive a chunk you must assign `yield` to a variable like so : `$chunk = yield`.

The basic needed code is the following :

```php
do {
    $chunk = yield;
    // do something with it
} while (!$chunk->isLast());
```
