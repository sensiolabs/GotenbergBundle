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
     * uploaded filename.
     *
     * Gotenberg requires the conformance level alongside the XML, recorded in the XMP
     * metadata. See the `FacturXConformanceLevel` enum for what each level covers.
     *
     * As an asset file, by default the file is fetched in the assets folder
     * of your application. For more information about path resolution go to
     * assets documentation. Pass a `\SplFileInfo` to bypass that resolution.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
     *
     * @example facturxXml('invoice.xml', FacturXConformanceLevel::EN16931)
     * @example facturxXml(new \SplFileInfo('/var/invoices/2026-001.xml'), FacturXConformanceLevel::EN16931)
     */
    public function facturxXml(string|\Stringable|\SplFileInfo $path, FacturXConformanceLevel $conformanceLevel): self
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The facturxXml option is not available.');

        if ($path instanceof \SplFileInfo) {
            $filename = $path->getFilename();
            $info = $path;
        } else {
            $filename = (string) $path;
            $info = new \SplFileInfo($this->getAssetBaseDirFormatter()->resolve($filename));
        }

        $this->getBodyBag()->set('facturxXml', [$filename => $info]);
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
    #[WithConfigurationNode(new ScalarNodeBuilder('facturx_version', restrictTo: 'string'))]
    public function facturxVersion(string $version): self
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The facturxVersion option is not available.');

        $this->getBodyBag()->set('facturxVersion', $version);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeFacturX(): \Generator
    {
        yield 'facturxXml' => NormalizerFactory::facturxXml();
        yield 'facturxConformanceLevel' => NormalizerFactory::enum();
        yield 'facturxDocumentType' => NormalizerFactory::enum();
    }
}
