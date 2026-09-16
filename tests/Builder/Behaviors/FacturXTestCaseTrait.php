<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\FacturXConformanceLevel;
use Sensiolabs\GotenbergBundle\Enumeration\FacturXDocumentType;

/**
 * @template T of BuilderInterface
 */
trait FacturXTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    abstract protected function assertGotenbergFormDataFile(string $name, string $contentType, string $path): void;

    public function testFacturxXml(): void
    {
        $this->withGotenbergVersion('8.34.0');

        $this->getDefaultBuilder()
            ->facturxXml('embed/factur-x.xml')
            ->facturxConformanceLevel(FacturXConformanceLevel::En16931)
            ->generate()
        ;

        $this->assertGotenbergFormDataFile('facturxXml', 'application/xml', self::FIXTURE_DIR.'/embed/factur-x.xml');
    }

    public function testFacturxConformanceLevel(): void
    {
        $this->withGotenbergVersion('8.34.0');

        $this->getDefaultBuilder()
            ->facturxXml('embed/factur-x.xml')
            ->facturxConformanceLevel(FacturXConformanceLevel::BasicWl)
            ->generate()
        ;

        $this->assertGotenbergFormData('facturxConformanceLevel', 'BASIC WL');
    }

    public function testFacturxDocumentType(): void
    {
        $this->withGotenbergVersion('8.34.0');

        $this->getDefaultBuilder()
            ->facturxXml('embed/factur-x.xml')
            ->facturxConformanceLevel(FacturXConformanceLevel::En16931)
            ->facturxDocumentType(FacturXDocumentType::Order)
            ->generate()
        ;

        $this->assertGotenbergFormData('facturxDocumentType', 'ORDER');
    }

    public function testFacturxVersion(): void
    {
        $this->withGotenbergVersion('8.34.0');

        $this->getDefaultBuilder()
            ->facturxXml('embed/factur-x.xml')
            ->facturxConformanceLevel(FacturXConformanceLevel::En16931)
            ->facturxVersion('1.0')
            ->generate()
        ;

        $this->assertGotenbergFormData('facturxVersion', '1.0');
    }
}
