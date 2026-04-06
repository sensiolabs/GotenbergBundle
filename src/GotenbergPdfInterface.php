<?php

namespace Sensiolabs\GotenbergBundle;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\EmbedPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\EncryptPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\FlattenPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\StampPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\WatermarkPdfBuilder;

interface GotenbergPdfInterface
{
    /**
     * @template T of BuilderInterface
     *
     * @param string|class-string<T> $builder
     *
     * @return ($builder is class-string ? T : BuilderInterface)
     */
    public function get(string $builder): BuilderInterface;

    /**
     * @return HtmlPdfBuilder
     */
    public function html(): BuilderInterface;

    /**
     * @return UrlPdfBuilder
     */
    public function url(): BuilderInterface;

    /**
     * @return MarkdownPdfBuilder
     */
    public function markdown(): BuilderInterface;

    /**
     * @return LibreOfficePdfBuilder
     */
    public function office(): BuilderInterface;

    /**
     * @return MergePdfBuilder
     */
    public function merge(): BuilderInterface;

    /**
     * @return ConvertPdfBuilder
     */
    public function convert(): BuilderInterface;

    /**
     * @return SplitPdfBuilder
     */
    public function split(): BuilderInterface;

    /**
     * @return FlattenPdfBuilder
     */
    public function flatten(): BuilderInterface;

    /**
     * @return EncryptPdfBuilder
     */
    public function encrypt(): BuilderInterface;

    /**
     * @return EmbedPdfBuilder
     */
    public function embed(): BuilderInterface;

    /**
     * @return StampPdfBuilder
     */
    public function stamp(): BuilderInterface;

    /**
     * @return WatermarkPdfBuilder
     */
    public function watermark(): BuilderInterface;
}
