<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Screenshot;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\AbstractScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Client\GotenbergClient;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\GotenbergResponse;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Processor\NullProcessor;
use Sensiolabs\GotenbergBundle\Tests\Builder\AbstractBuilderTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\Mime\Part\DataPart;

#[CoversClass(AbstractScreenshotBuilder::class)]
#[UsesClass(GotenbergClient::class)]
#[UsesClass(GotenbergResponse::class)]
final class AbstractScreenshotBuilderTest extends AbstractBuilderTestCase
{
    public function testFilenameIsCorrectlySetOnResponse(): void
    {
        // @phpstan-ignore-next-line
        $this->gotenbergClient = new GotenbergClient(new MockHttpClient([
            new MockResponse(),
        ]));

        $response = $this->getScreenshotBuilder()
            ->fileName('some_file.png', HeaderUtils::DISPOSITION_ATTACHMENT)
            ->build()
            ->streamResponse()
        ;

        self::assertSame('attachment; filename=some_file.png', $response->headers->get('Content-Disposition'));
    }

    public static function nativeNormalizersProvider(): \Generator
    {
        yield 'boolean (true)' => ['boolean', true, 'true'];
        yield 'boolean (false)' => ['boolean', false, 'false'];

        yield 'int' => ['int', 12, '12'];
        yield 'float (.0)' => ['float', 12.0, '12.0'];
        yield 'float (.5)' => ['float', 12.5, '12.5'];
    }

    #[DataProvider('nativeNormalizersProvider')]
    #[TestDox('Native "$_dataName" is correctly normalized')]
    public function testNativeNormalizers(string $key, mixed $raw, mixed $expected): void
    {
        $builder = $this->getScreenshotBuilder([$key => $raw]);
        $data = $builder->getMultipartFormData()[0];

        self::assertArrayHasKey($key, $data);
        self::assertSame($expected, $data[$key]);
    }

    /**
     * @param array{
     *     'extraHttpHeaders'?: array<string, string>,
     *     'assets'?: array<string, DataPart>,
     *     'index.html'?: DataPart,
     *     'failOnHttpStatusCodes'?: list<int>,
     *     'cookies'?: list<array{name: string, value: string, domain: string, path?: string|null, secure?: bool|null, httpOnly?: bool|null, sameSite?: 'Strict'|'Lax'|null}>,
     *     'metadata'?: array<string, mixed>
     * } $formFields
     */
    private function getScreenshotBuilder(array $formFields = []): AbstractScreenshotBuilder
    {
        return (new class($this->gotenbergClient, self::$assetBaseDirFormatter, $formFields) extends AbstractScreenshotBuilder {
            /**
             * @param array<mixed> $formFields
             */
            public function __construct(GotenbergClientInterface $gotenbergClient, AssetBaseDirFormatter $asset, array $formFields = [])
            {
                parent::__construct($gotenbergClient, $asset);
                $this->formFields = $formFields;
            }

            public function setConfigurations(array $configurations): static
            {
                return $this;
            }

            protected function getEndpoint(): string
            {
                return '/fake/endpoint';
            }
        })
            ->processor(new NullProcessor())
        ;
    }
}
