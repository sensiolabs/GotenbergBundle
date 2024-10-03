<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\ChromiumPdfTestCaseTrait;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergBuilderTestCase;
use Sensiolabs\GotenbergBundle\Twig\GotenbergRuntime;
use Symfony\Component\DependencyInjection\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * @extends GotenbergBuilderTestCase<MarkdownPdfBuilder>
 */
final class MarkdownPdfBuilderTest extends GotenbergBuilderTestCase
{
    /** @use ChromiumPdfTestCaseTrait<MarkdownPdfBuilder> */
    use ChromiumPdfTestCaseTrait;

    protected function createBuilder(GotenbergClientInterface $client, Container $dependencies): MarkdownPdfBuilder
    {
        return new MarkdownPdfBuilder($client, $dependencies);
    }

    /**
     * @param MarkdownPdfBuilder $builder
     */
    protected function initializeBuilder(BuilderInterface $builder, Container $container): MarkdownPdfBuilder
    {
        return $builder
            ->wrapperFile('files/wrapper.html')
            ->files('assets/file.md')
        ;
    }

    public function testFileWithContentFile(): void
    {
        $this->getBuilder()
            ->files('assets/file.md')
            ->wrapperFile('files/wrapper.html')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/markdown');
        $this->assertGotenbergFormDataFile('files', 'text/markdown', self::FIXTURE_DIR.'/assets/file.md');
    }

    public function testFileWithContent(): void
    {
        $twig = new Environment(new FilesystemLoader(self::FIXTURE_DIR), [
            'strict_variables' => true,
        ]);

        $twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
            public function load(string $class): object|null
            {
                return GotenbergRuntime::class === $class ? new GotenbergRuntime() : null;
            }
        });

        $this->dependencies->set('twig', $twig);

        $this->getBuilder()
            ->files('assets/file.md')
            ->wrapper('templates/wrapper.html.twig', ['name' => 'John Doe'])
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/markdown');
        $this->assertGotenbergFormDataFile('files', 'text/markdown', self::FIXTURE_DIR.'/assets/file.md');
    }

    public function testWithStringableObject(): void
    {
        $class = new class implements \Stringable {
            public function __toString(): string
            {
                return 'assets/file.md';
            }
        };

        $this->getBuilder()
            ->files($class)
            ->wrapperFile('files/wrapper.html')
            ->generate()
        ;

        $this->assertGotenbergEndpoint('/forms/chromium/convert/markdown');
        $this->assertGotenbergFormDataFile('files', 'text/markdown', self::FIXTURE_DIR.'/assets/file.md');
    }

    public function testRequiredFileContent(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('HTML template is required');

        $this->getBuilder()
            ->generate()
        ;
    }

    public function testRequiredMarkdownFile(): void
    {
        $this->expectException(MissingRequiredFieldException::class);
        $this->expectExceptionMessage('At least one markdown file is required.');

        $this->getBuilder()
            ->wrapperFile('files/wrapper.html')
            ->generate()
        ;
    }

    public function testFilesExtensionRequirement(): void
    {
        $this->expectException(InvalidBuilderConfiguration::class);
        $this->expectExceptionMessage('The file extension "png" is not valid in this context.');

        $this->getBuilder()
            ->files('b.png')
            ->generate()
        ;
    }

    public function testToWrapWithContent(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Use wrapper() instead of content().');

        $this->getBuilder()
            ->content('templates/wrapper.html.twig', ['name' => 'John Doe'])
        ;
    }

    public function testToWrapWithContentFile(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Use wrapperFile() instead of contentFile().');

        $this->getBuilder()
            ->contentFile('files/wrapper.html')
        ;
    }
}
