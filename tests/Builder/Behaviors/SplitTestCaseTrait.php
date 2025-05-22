<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\SplitMode;

/**
 * @template T of BuilderInterface
 */
trait SplitTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSplitPdfIntoPageMode(): void
    {
        $this->getDefaultBuilder()
            ->splitMode(SplitMode::Pages)
            ->generate()
        ;

        $this->assertGotenbergFormData('splitMode', SplitMode::Pages->value);
    }

    public function testSplitPdfWithPageRanges(): void
    {
        $this->getDefaultBuilder()
            ->splitSpan('1-2')
            ->generate()
        ;

        $this->assertGotenbergFormData('splitSpan', '1-2');
    }

    public function testUnifyTheSplittingResultIntoOnePdf(): void
    {
        $this->getDefaultBuilder()
            ->splitUnify()
            ->generate()
        ;

        $this->assertGotenbergFormData('splitUnify', 'true');
    }

    public function testUnsetSplitMode(): void
    {
        $builder = $this->getDefaultBuilder()
            ->splitMode(SplitMode::Pages)
        ;

        self::assertArrayHasKey('splitMode', $builder->getBodyBag()->all());

        $builder->splitMode(null);
        self::assertArrayNotHasKey('splitMode', $builder->getBodyBag()->all());
    }
}
