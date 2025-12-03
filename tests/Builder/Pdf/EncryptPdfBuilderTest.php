<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\EncryptPdfBuilder;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Test\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\DownloadFromTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\EncryptTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\WebhookTestCaseTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @extends GotenbergBuilderTestCase<EncryptPdfBuilder>
 */
final class EncryptPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use DownloadFromTestCaseTrait<EncryptPdfBuilder> */
    use DownloadFromTestCaseTrait;

    /** @use EncryptTestCaseTrait<EncryptPdfBuilder> */
    use EncryptTestCaseTrait;

    /** @use WebhookTestCaseTrait<EncryptPdfBuilder> */
    use WebhookTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withGotenbergVersion('8.25.0');
    }

    protected function createBuilder(): EncryptPdfBuilder
    {
        return new EncryptPdfBuilder();
    }

    /**
     * @param EncryptPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): EncryptPdfBuilder
    {
        return $builder
            ->files('pdf/simple_pdf.pdf')
            ->userPassword('user_password')
            ->ownerPassword('owner_password')
        ;
    }

    public function testRequiredUserPassword(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least userPassword must be provided.');

        $this->getBuilder()
            ->ownerPassword('owner_password')
            ->generate()
        ;
    }

    public function testAddFilesAsContent(): void
    {
        $this->getBuilder()
            ->files('pdf/simple_pdf.pdf')
            ->userPassword('user_password')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/encrypt');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one PDF file is required.');

        $this->getBuilder()
            ->userPassword('user_password')
            ->generate()
        ;
    }

    public function testWithStringableObject(): void
    {
        $class = new class implements \Stringable {
            public function __toString(): string
            {
                return 'pdf/simple_pdf.pdf';
            }
        };

        $this->getBuilder()
            ->files($class)
            ->userPassword('user_password')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/pdfengines/encrypt');
        $this->assertGotenbergFormDataFile('files', 'application/pdf', self::FIXTURE_DIR.'/pdf/simple_pdf.pdf');
    }
}
