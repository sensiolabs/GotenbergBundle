# Testing

## Creating mock results

The `GotenbergFileResult` class requires a `ResponseStreamInterface` instance, and `GotenbergAsyncResult` needs a
`ResponseInterface` instance—making them difficult to mock during tests. To simplify this, the GotenbergBundle includes
a
`GotenbergMockFileResult` class for easily creating mock results.
You can use one of two static methods to obtain a working mock instance:

`fromFile`: Creates a response from an existing file.

`fromContent`: Creates a response from a string.

Example :

```php
$invoicePdfGenerator
    ->method('getPdfForInvoice')
    ->with($invoiceId)
    ->willReturn(GotenbergMockFileResult::fromFile(
        sprintf('%s/fixtures/test_%d.pdf', __DIR__, $invoiceId),
        'invoice.pdf',
        disposition: HeaderUtils::DISPOSITION_ATTACHMENT,
    ))
;
```

## Builder Testing Support

For testing custom builders, you can extend the abstract class GotenbergBuilderTestCase, which provides utility methods
for assertions. You'll need to implement the createBuilder method to return a new instance of your builder.

Available assertion methods include:

**Endpoint Assertion**

`assertGotenbergEndpoint`: Asserts the expected API URL was called.

**Form Data Assertions**

`assertGotenbergFormData`: Asserts that the correct data was sent in the API request.

`assertGotenbergFormDataFile`: Asserts that the correct file was included in the API request.

**Header Assertion**

`assertGotenbergHeader`: Asserts that the correct headers were sent to the API.

**Error Assertion**

`assertGotenbergException`: Asserts that the correct exception was thrown for an API error.

**Content Assertion**

`assertContentFile`: Asserts the file received from the API matches expectations.

Example :

```php
final class HtmlPdfBuilderTest extends GotenbergBuilderTestCase
{
    protected function createBuilder(): HtmlPdfBuilder
    {
        return new HtmlPdfBuilder();
    }

    public function testPdfGenerationFromAGivenRoute(): void
    {
        // Arrange
        $routeCollection = new RouteCollection();
        $routeCollection->add('article_read', new Route('/article/{id}', methods: Request::METHOD_GET));
        $this->container->set('router', new UrlGenerator($routeCollection, new RequestContext())); // Add needed dependency

        // Act
        $this->getBuilder()
            ->route('article_read', ['id' => 1])
            ->filename('article')
            ->generate()
        ;

        // Assert
        $this->assertGotenbergEndpoint('/forms/chromium/convert/url');
        $this->assertGotenbergHeader('Gotenberg-Output-Filename', 'article');
        $this->assertGotenbergFormData('url', 'http://localhost/article/1');
    }
}
```
