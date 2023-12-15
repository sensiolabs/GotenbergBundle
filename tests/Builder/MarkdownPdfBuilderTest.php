<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\MarkdownPdfBuilder;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(MarkdownPdfBuilder::class)]
final class MarkdownPdfBuilderTest extends TestCase
{
    use BuilderTestTrait;

    public function testMarkdownFile(): void
    {
        $builder = new MarkdownPdfBuilder($this->getGotenbergMock(), $this->getTwig(), self::FIXTURE_DIR);
        $builder->markdownFile('assets/file.md');

        $multipart = $builder->getMultipartFormData();
        $itemMarkdown = $multipart[array_key_first($multipart)];

        self::assertArrayHasKey('files', $itemMarkdown);
        self::assertInstanceOf(DataPart::class, $itemMarkdown['files']);

        $dataPart = $itemMarkdown['files'];
        self::assertEquals('text/markdown', $dataPart->getContentType());
    }
}
