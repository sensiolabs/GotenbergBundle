<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\ExposeSemantic;
use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\NodeBuilder\ArrayNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
 * @see https://gotenberg.dev/docs/routes#download-from
 */
trait DownloadFromTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Sets download from to download each entry (file) in parallel (URLs MUST return a Content-Disposition header with a filename parameter.).
     *
     * @param list<array{url: string, extraHttpHeaders?: array<string, string>}> $downloadFrom
     *
     * @see https://gotenberg.dev/docs/routes#download-from
     */
    #[ExposeSemantic(new ArrayNodeBuilder('download_from', prototype: 'array', children: [
        new ScalarNodeBuilder('url', required: true, restrictTo: 'string'),
        new ArrayNodeBuilder('extraHttpHeaders', useAttributeAsKey: 'name', prototype: 'array', children: [
            new ScalarNodeBuilder('name', required: true),
            new ScalarNodeBuilder('value', required: true),
        ]),
    ]))]
    public function downloadFrom(array $downloadFrom): static
    {
        ValidatorFactory::download($downloadFrom);
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
        yield 'downloadFrom' => NormalizerFactory::json(false);
    }
}
