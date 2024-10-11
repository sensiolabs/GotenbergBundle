<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\DefaultBuilderTrait;
use Sensiolabs\GotenbergBundle\Enumeration\PdfFormat;
use Sensiolabs\GotenbergBundle\Exception\JsonEncodingException;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(DefaultBuilderTrait::class)]
#[UsesClass(AssetBaseDirFormatter::class)]
class DefaultBuilderTraitTest extends TestCase
{
    public function testCanEncodeData(): void
    {
        $builder = $this->getBuilder();

        self::assertSame([
            'key' => '{"key_v1":"value_v1"}',
        ], $builder->encodeData('key', ['key_v1' => 'value_v1']));
    }

    public function testEncodeDataFailIfSomethingWentWrong(): void
    {
        $builder = $this->getBuilder();

        $this->expectException(JsonEncodingException::class);
        $this->expectExceptionMessage('Could not encode property "key" into JSON');

        $builder->encodeData('key', [\INF]);
    }

    public static function canConvertToMultiPartProvider(): \Generator
    {
        yield 'simple boolean (true)' => [true, [['key' => 'true']]];
        yield 'simple boolean (false)' => [false, [['key' => 'false']]];

        yield 'simple int' => [12, [['key' => '12']]];

        yield 'simple float' => [12.2, [['key' => '12.2']]];
        yield 'rounded float' => [12.0, [['key' => '12.0']]];

        yield 'any BackedEnum' => [PdfFormat::Pdf2b, [['key' => 'PDF/A-2b']]];

        yield 'any Stringable' => [new class implements \Stringable {
            public function __toString(): string
            {
                return __FUNCTION__;
            }
        }, [['key' => '__toString']]];

        yield 'nested array' => [[
            'level1' => [
                'level2' => [
                    'level2_rounded_float' => 12.0,
                    'level2_simple_float' => 12.5,
                    'level2_boolean' => true,
                ],
                'simple_boolean' => true,
            ],
        ], [
            ['key' => '12.0'],
            ['key' => '12.5'],
            ['key' => 'true'],
            ['key' => 'true'],
        ]];

        yield 'simple string' => ['hello world', [['key' => 'hello world']]];

        yield 'with custom normalizer' => ['', [['key' => 'plop']], static function (): array {
            return ['key' => 'plop'];
        }];
    }

    #[DataProvider('canConvertToMultiPartProvider')]
    public function testCanConvertToMultiPart(mixed $value, array $expectedResult, callable|null $normalizer = null): void
    {
        $builder = $this->getBuilder();
        $builder->addFormField('key', $value);

        if (null !== $normalizer) {
            $builder->addNormalizer('key', $normalizer);
        }

        self::assertSame($expectedResult, $builder->getMultipartFormData());
    }

    public function testCanAssertFileExtensionsAndThrowIfNotValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The file extension "html" is not valid in this context.');

        $builder = $this->getBuilder();
        $builder->assertFileExtension('content.html', ['txt']);
    }

    public function testCanAssertFileExtensions(): void
    {
        $builder = $this->getBuilder();
        $builder->assertFileExtension('content.html', ['html']);

        $this->addToAssertionCount(1);
    }

    public function testTraceGeneration(): void
    {
        $builder = $this->getBuilder();
        $builder->traceGenerator(fn () => 'foo');
        $this->assertSame('foo', $builder->generate());
    }

    private function getBuilder(): object
    {
        return new class {
            use DefaultBuilderTrait {
                addNormalizer as public;
                encodeData as public;
                assertFileExtension as public;
            }

            public function __construct()
            {
                $this->asset = new AssetBaseDirFormatter(
                    new Filesystem(),
                    __DIR__.'/../Fixtures',
                    'files',
                );
            }

            protected function getEndpoint(): string
            {
                // TODO: Implement getEndpoint() method.
            }

            public function setConfigurations(array $configurations): static
            {
                // TODO: Implement setConfigurations() method.
            }

            public function addFormField(string $key, mixed $value): void
            {
                $this->formFields[$key] = $value;
            }
        };
    }
}
