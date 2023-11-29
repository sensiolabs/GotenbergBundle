<?php

namespace Sensiolabs\GotenbergBundle\Builder;

/**
 * @phpstan-type ConfigBuilder list<array{
 *      'paperWidth'?: float,
 *      'paperHeight'?: float,
 *      'marginTop'?: float,
 *      'marginBottom'?: float,
 *      'marginLeft'?: float,
 *      'marginRight'?: float,
 *      'preferCssPageSize'?: bool,
 *      'printBackground'?: bool,
 *      'omitBackground'?: bool,
 *      'landscape'?: bool,
 *      'scale'?: float,
 *      'nativePageRanges'?: string,
 *      'waitDelay'?: string,
 *      'waitForExpression'?: string,
 *      'emulatedMediaType'?: string,
 *      'userAgent'?: string,
 *      'extraHttpHeaders'?: string,
 *      'failOnConsoleExceptions'?: bool,
 *      'pdfa'?: string,
 *      'pdfua'?: bool,
 * }>
 */
interface BuilderInterface
{
    public function getEndpoint(): string;

    /**
     * @return ConfigBuilder
     */
    public function getMultipartFormData(): array;
}
