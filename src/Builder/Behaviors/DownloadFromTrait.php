<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\NodeType;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;

/**
 * @see https://gotenberg.dev/docs/routes#download-from
 */
trait DownloadFromTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Sets download from to download each entry (file) in parallel (default None).
     * (URLs MUST return a Content-Disposition header with a filename parameter.).
     *
     * @param list<array{url: string, extraHttpHeaders?: array<string, string>}> $downloadFrom
     */
    #[ExposeSemantic('download_from', NodeType::Array, ['default_value' => [], 'prototype' => 'array', 'children' => [
        ['name' => 'url', 'options' => ['required' => true]],
        ['name' => 'extraHttpHeaders', 'node_type' => NodeType::Array, 'options' => ['prototype' => 'array', 'use_attribute_as_key' => 'name', 'children' => [
            ['name' => 'name'],
            ['name' => 'value'],
        ]]],
    ]])]
    public function downloadFrom(array $downloadFrom): static
    {
        if (!ValidatorFactory::download($downloadFrom)) {
            throw new InvalidBuilderConfiguration('"url" is mandatory into "downloadFrom" array field.');
        }

        if ([] === $downloadFrom) {
            $this->getBodyBag()->unset('downloadFrom');

            return $this;
        }

        $value = $this->getBodyBag()->get('downloadFrom', []);

        foreach ($downloadFrom as $file) {
            $value[$file['url']] = $file;
        }

        $this->getBodyBag()->set('downloadFrom', $value);

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeDownloadFrom(): \Generator
    {
        yield 'downloadFrom' => NormalizerFactory::json();
    }
}
