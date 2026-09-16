<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Enumeration\FacturXConformanceLevel;
use Sensiolabs\GotenbergBundle\Enumeration\FacturXDocumentType;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
 * @package Behavior\\FacturX
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
 */
trait FacturXTrait
{
    use AssetBaseDirFormatterAwareTrait;
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * The Factur-X/ZUGFeRD CII invoice XML, embedded as `factur-x.xml` regardless of the
     * uploaded filename. Must be provided together with `facturxConformanceLevel()`.
     *
     * As an asset file, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
     *
     * @example facturxXml('invoice.xml')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('facturx_xml'))]
    public function facturxXml(string|\Stringable $path): self
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The facturxXml option is not available.');

        $path = (string) $path;
        $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($path));
        $this->getBodyBag()->set('facturxXml', [$path => $info]);

        return $this;
    }

    /**
     * The Factur-X/ZUGFeRD conformance level. Must be provided together with `facturxXml()`.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
     *
     * @example facturxConformanceLevel(FacturXConformanceLevel::En16931)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('facturx_conformance_level', enumClass: FacturXConformanceLevel::class))]
    public function facturxConformanceLevel(FacturXConformanceLevel $conformanceLevel): self
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The facturxConformanceLevel option is not available.');

        $this->getBodyBag()->set('facturxConformanceLevel', $conformanceLevel);

        return $this;
    }

    /**
     * The Factur-X/ZUGFeRD document type. (Default INVOICE).
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
     *
     * @example facturxDocumentType(FacturXDocumentType::Order)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('facturx_document_type', enumClass: FacturXDocumentType::class))]
    public function facturxDocumentType(FacturXDocumentType $documentType): self
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The facturxDocumentType option is not available.');

        $this->getBodyBag()->set('facturxDocumentType', $documentType);

        return $this;
    }

    /**
     * The Factur-X version. (Default '1.0').
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
     *
     * @example facturxVersion('1.0')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('facturx_version'))]
    public function facturxVersion(string $version): self
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The facturxVersion option is not available.');

        $this->getBodyBag()->set('facturxVersion', $version);

        return $this;
    }

    /**
     * `facturxXml` and `facturxConformanceLevel` are required together: providing only
     * one of them is rejected by Gotenberg.
     */
    protected function validateFacturX(): void
    {
        $hasXml = $this->getBodyBag()->get('facturxXml') !== null;
        $hasConformanceLevel = $this->getBodyBag()->get('facturxConformanceLevel') !== null;

        if ($hasXml && !$hasConformanceLevel) {
            throw new MissingRequiredFieldException('"facturxConformanceLevel" must be provided when "facturxXml" is set.');
        }

        if ($hasConformanceLevel && !$hasXml) {
            throw new MissingRequiredFieldException('"facturxXml" must be provided when "facturxConformanceLevel" is set.');
        }
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFacturX(): \Generator
    {
        yield 'facturxXml' => NormalizerFactory::facturxXml();
        yield 'facturxConformanceLevel' => NormalizerFactory::enum();
        yield 'facturxDocumentType' => NormalizerFactory::enum();
    }
}
