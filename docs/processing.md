# Processing

Let's say you want to save the PDF or Screenshot as a file, you will need to use a `Sensiolabs\GotenbergBundle\Processor\ProcessorInterface`.
To avoid loading the whole file content in memory you can stream it to the browser.

You can also hook on the stream and save the file chunk by chunk. To do so we leverage the [`->stream`](https://symfony.com/doc/current/http_client.html#streaming-responses) method from the HttpClientInterface and use a powerful feature from PHP Generators : [`->send`](https://www.php.net/manual/en/generator.send.php).

## Native Processors

Given an exemple for a PDF (works the same for Screenshots):

```php
/** @var GotenbergPdfInterface $gotenbergPdf */
use Sensiolabs\GotenbergBundle\Builder\GotenbergFileResult;$gotenbergPdf = /* ... */;

/** @var GotenbergFileResult $gotenbergFileResult */
$gotenbergFileResult = $gotenbergPdf->html()
    // ...
    ->fileName('my_pdf')
    ->processor(/* ... */)
    ->generate()
;

// Either process it with
$result = $gotenbergFileResult->process(); // `$result` depends on the Processor used. See below.
// or to send a response to the browser :
$result = $gotenbergFileResult->stream(); // `$result` is a `Symfony\Component\HttpFoundation\StreamedResponse`
```

> [!NOTE]
> `stream()` copies the headers of the Gotenberg response to the `StreamedResponse`, except:
> * the hop-by-hop headers, which only concern the connection between Gotenberg (or a proxy in front of it)
>   and your application: `Connection`, `Keep-Alive`, `Proxy-Authenticate`, `Proxy-Authorization`,
>   `Proxy-Connection`, `TE`, `Trailer`, `Transfer-Encoding`, `Upgrade`, and any header named in the
>   `Connection` value (see [RFC 9110 §7.6.1](https://www.rfc-editor.org/rfc/rfc9110#section-7.6.1));
> * `Content-Encoding` and `Content-Length`, which describe the body as received: the HttpClient may have
>   decompressed it (e.g. when a reverse proxy gzips Gotenberg's response), so they would no longer match.
>
> `Content-Disposition` is rebuilt from the file name and disposition, and `X-Accel-Buffering: no` is added.
> Use `getHeaders()` if you need the headers exactly as Gotenberg sent them.

Here is the list of existing Processors :

### `Sensiolabs\GotenbergBundle\Processor\FileProcessor`

Useful if you want to store the file in the local filesystem.
<details>
<summary>Example in a controller</summary>

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
        // ...
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

</details>

<details>
<summary>If you are not streaming to a browser, you can still process the file using the `process` method instead of `stream`</summary>

```php
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Processor\FileProcessor;
use Symfony\Component\Filesystem\Filesystem;

class SomeService
{
    public function __construct(
        private readonly GotenbergPdfInterface $gotenbergPdf,

        #[Autowire('%kernel.project_dir%/var/pdf')]
        private readonly string $pdfStorage,
    ) {}

    public function pdf(): \SplFileInfo
    {
        return $this->gotenbergPdf->html()
            //
            ->fileName('my_pdf')
            ->processor(new FileProcessor(
                new Filesystem(),
                $pdfStorage,
            ))
            ->generate()
            ->process()
        ;
    }
}
```

This will return a `SplFileInfo` of the generated file stored at `%kernel.project_dir%/var/pdf/my_pdf.pdf`.

</details>

### `Sensiolabs\GotenbergBundle\Processor\NullProcessor`

Empty processor. Does nothing. Returns `null`.

### `Sensiolabs\GotenbergBundle\Processor\TempfileProcessor`

Creates a temporary file and dump all chunks into it. Return a `ressource` of said `tmpfile()`.

<details>
<summary>Example in a service</summary>

```php
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Processor\TempfileProcessor;

class SomeService
{
    public function __construct(
        private readonly GotenbergPdfInterface $gotenbergPdf,
    ) {}

    /**
     * @return resource
     */
    public function pdf(): mixed
    {
        return $this->gotenbergPdf->html()
            //
            ->fileName('my_pdf')
            ->processor(new TempfileProcessor())
            ->generate()
            ->process()
        ;
    }
}
```

</details>

### `Sensiolabs\GotenbergBundle\Processor\ChainProcessor`

Apply multiple processors. Each chunk will be sent to each processor sequentially. Return an array of values returned by chained processors.

<details>
<summary>Example in a service</summary>

```php
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Processor\ChainProcessor;
use Sensiolabs\GotenbergBundle\Processor\FileProcessor;
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @implements ProcessorInterface<int>
 */
class CustomProcessor implements ProcessorInterface
{
    public function __invoke(string|null $fileName): \Generator { /* ... */ } // Implement your own logic
}

class SomeService
{
    public function __construct(
        private readonly GotenbergPdfInterface $gotenbergPdf,

        #[Autowire('%kernel.project_dir%/var/pdf')]
        private readonly string $pdfStorage,
    ) {}

    /**
     * @return array{0: \SplFileInfo, 1: int}
     */
    public function pdf(): array
    {
        return $this->gotenbergPdf->html()
            //
            ->fileName('my_pdf')
            ->processor(new ChainProcessor([
                new FileProcessor(
                    new Filesystem(),
                    $pdfStorage,
                ),
                new CustomProcessor(),
            ]))
            ->generate()
            ->process()
        ;
    }
}
```

</details>

### `Sensiolabs\GotenbergBundle\Bridge\LeagueFlysystem\Processor\FlysystemProcessor`

Upload using the `league/flysystem-bundle` package. Returns a `callable`. This callable will return the uploaded content.

<details>
<summary>Example in a service</summary>

```php
use League\Flysystem\FilesystemOperator;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Bridge\LeagueFlysystem\Processor\FlysystemProcessor;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class SomeService
{
    public function __construct(
        private readonly GotenbergPdfInterface $gotenbergPdf,

        #[Autowire(service: 'pdfs.storage')] // Use the name under the `flysystem.storages` key in your packages configuration.
        private readonly FilesystemOperator $filesystemOperator,
    ) {}

    /**
     * @return Closure(): string
     */
    public function pdf(): Closure
    {
        return $this->gotenbergPdf->html()
            //
            ->fileName('my_pdf')
            ->processor(new FlysystemProcessor(
                $this->filesystemOperator,
            ))
            ->generate()
            ->process()
        ;
    }
}
```

</details>

### `Sensiolabs\GotenbergBundle\Bridge\AsyncAws\Processor\AsyncAwsS3MultiPartProcessor`

Upload using the `async-aws/s3` package. Uploads using the [multipart upload](https://docs.aws.amazon.com/AmazonS3/latest/userguide/mpuoverview.html) feature of S3. Returns a `AsyncAws\S3\Result\CompleteMultipartUploadOutput` object.

<details>
<summary>Example in a service</summary>

```php
use AsyncAws\S3\Result\CompleteMultipartUploadOutput;
use Sensiolabs\GotenbergBundle\Bridge\AsyncAws\Processor\AsyncAwsS3MultiPartProcessor;
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;

class SomeService
{
    public function __construct(
        private readonly GotenbergPdfInterface $gotenbergPdf,
        private readonly S3Client $s3Client,
    ) {}

    public function pdf(): CompleteMultipartUploadOutput
    {
        return $this->gotenbergPdf->html()
            //
            ->fileName('my_pdf')
            ->processor(new AsyncAwsS3MultiPartProcessor(
                $this->s3Client,
                'bucket-name',
            ))
            ->generate()
            ->process()
        ;
    }
}
```

</details>

### `Sensiolabs\GotenbergBundle\Processor\InMemoryProcessor`

Loads the full PDF in memory. Should **NOT** be used in production.
This is not memory safe and you might end up with a "Fatal Error: Allowed Memory Size".
Consider using one of the other Processors.

<details>
<summary>Example in a service</summary>

```php
use Sensiolabs\GotenbergBundle\GotenbergPdfInterface;
use Sensiolabs\GotenbergBundle\Processor\InMemoryProcessor;

class SomeService
{
    public function __construct(
        private readonly GotenbergPdfInterface $gotenbergPdf,
    ) {}

    public function pdf(): string
    {
        return $this->gotenbergPdf->html()
            //
            ->fileName('my_pdf')
            ->processor(new InMemoryProcessor())
            ->generate()
            ->process()
        ;
    }
}
```

</details>

## Custom processor

A custom processor must implement `Sensiolabs\GotenbergBundle\Processor\ProcessorInterface` which require that your `__invoke` method is a `\Generator`. To receive a chunk you must assign `yield` to a variable like so : `$chunk = yield`.

The basic needed code is the following :

```php
use Sensiolabs\GotenbergBundle\Processor\ProcessorInterface;

/**
 * @implements ProcessorInterface<YOUR_GENERATOR_RETURN_TYPE>
 */
class CustomProcessor implements ProcessorInterface
{
    public function __invoke(string|null $fileName): \Generator
    {
        do {
            $chunk = yield;
            // do something with it
        } while (!$chunk->isLast());
        // rest of your code
    }
}
```
