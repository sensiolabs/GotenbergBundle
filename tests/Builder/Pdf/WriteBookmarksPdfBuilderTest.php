<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\WriteBookmarksPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Exception\VersionCompatibilityException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WriteBookmarksTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<WriteBookmarksPdfBuilder>
 */
final class WriteBookmarksPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<WriteBookmarksPdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use WebhookTestCaseTrait<WriteBookmarksPdfBuilder> */
    use WebhookTestCaseTrait;

    /** @use WriteBookmarksTestCaseTrait<WriteBookmarksPdfBuilder> */
    use WriteBookmarksTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withGotenbergVersion('8.28.0');
    }

    protected function createBuilder(): WriteBookmarksPdfBuilder
    {
        return new WriteBookmarksPdfBuilder();
    }

    /**
     * @param WriteBookmarksPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): WriteBookmarksPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->addBookmark('Introduction', 1)
        ;
    }

    public function testEndpointIsCorrect(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->addBookmark('Introduction', 1)
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/bookmarks/write');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
        $this->assertGotenbergFormData('bookmarks', '[{"title":"Introduction","page":1}]');
    }

    public function testAddBookmark(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->addBookmark('Introduction', 1)
            ->addBookmark('Appendix', 5, [['title' => 'Sub-section', 'page' => 5]])
            ->generate()
        ;

        $this->assertGotenbergFormData('bookmarks', '[{"title":"Introduction","page":1},{"title":"Appendix","page":5,"children":[{"title":"Sub-section","page":5}]}]');
    }

    public function testFilesExtensionRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files(self::FIXTURE_DIR.'/assets/logo.png')
            ->addBookmark('Introduction', 1)
            ->generate()
        ;
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->addBookmark('Introduction', 1)
            ->generate()
        ;
    }

    public function testRequiredBookmarks(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('Field "bookmarks" must be provided.');

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->generate()
        ;
    }

    public function testRequiresGotenberg828(): void
    {
        $this->withGotenbergVersion('8.27.0');

        $this->expectException(VersionCompatibilityException::class);

        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->addBookmark('Introduction', 1)
            ->generate()
        ;
    }
}
