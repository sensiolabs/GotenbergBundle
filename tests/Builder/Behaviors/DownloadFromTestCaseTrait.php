<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\DownloadFromField;

/**
 * @template T of BuilderInterface
 */
trait DownloadFromTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testAddAnExternalResource(): void
    {
        $this->getDefaultBuilder()
            ->downloadFrom([
                [
                    'url' => 'http://url/to/file.com',
                    'extraHttpHeaders' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('downloadFrom', '[{"url":"http:\/\/url\/to\/file.com","extraHttpHeaders":{"MyHeader":"MyValue","User-Agent":"MyValue"}}]');
    }

    public function testAddAnExternalResourceWithField(): void
    {
        $this->getDefaultBuilder()
            ->downloadFrom([
                [
                    'url' => 'http://url/to/file.com',
                    'field' => 'watermark',
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('downloadFrom', '[{"url":"http:\/\/url\/to\/file.com","field":"watermark"}]');
    }

    public function testAddAnExternalResourceWithFieldEnum(): void
    {
        $this->getDefaultBuilder()
            ->downloadFrom([
                [
                    'url' => 'http://url/to/file.com',
                    'field' => DownloadFromField::Watermark,
                ],
            ])
            ->generate()
        ;

        $this->assertGotenbergFormData('downloadFrom', '[{"url":"http:\/\/url\/to\/file.com","field":"watermark"}]');
    }

    public function testUnsetDownloadResource(): void
    {
        $builder = $this->getDefaultBuilder()
            ->downloadFrom([
                [
                    'url' => 'http://url/to/file.com',
                    'extraHttpHeaders' => ['MyHeader' => 'MyValue', 'User-Agent' => 'MyValue'],
                ],
            ])
        ;

        self::assertArrayHasKey('downloadFrom', $builder->getBodyBag()->all());

        $builder->downloadFrom([]);
        self::assertArrayNotHasKey('downloadFrom', $builder->getBodyBag()->all());
    }
}
