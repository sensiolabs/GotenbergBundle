<?php

namespace Configurator\Pdf;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Configurator\Pdf\HtmlPdfBuilderConfigurator;
use Sensiolabs\GotenbergBundle\Tests\Builder\GotenbergClientAsserter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Container;

#[CoversClass(HtmlPdfBuilderConfigurator::class)]
#[UsesClass(HtmlPdfBuilder::class)]
final class HtmlPdfBuilderConfiguratorTest extends TestCase
{
    public function testSetConfigurations(): void
    {
        self::expectException(InvalidConfigurationException::class);
        self::expectExceptionMessage('You cannot use "paper_standard_size" when "paper_height", "paper_width" or both are set".');

        $configurator = new HtmlPdfBuilderConfigurator([
            'paper_standard_size' => 'some_value',
            'paper_height' => 'some_value',
            'paper_width' => 'some_value',
        ]);

        $builder = new HtmlPdfBuilder(
            new GotenbergClientAsserter(),
            new Container(),
        );

        $configurator->setConfigurations($builder);
    }
}
