<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;
use Symfony\Component\Mime\Part\DataPart;

trait DownloadFromTrait
{
    /**
     * Sets download from to download each entry (file) in parallel (default None).
     * (URLs MUST return a Content-Disposition header with a filename parameter.).
     *
     * @see https://gotenberg.dev/docs/routes#download-from
     *
     * @param list<array{url: string, extraHttpHeaders?: array<string, string>}> $downloadFrom
     */
    abstract public function downloadFrom(array $downloadFrom): static;

    /**
     * @param array{downloadFrom?: array<string, array{url: string, extraHttpHeaders?: array<string, string>}>} $formFields
     * @param list<array{url: string, extraHttpHeaders?: array<string, string>}>                                $downloadFrom
     */
    private function withDownloadFrom(array &$formFields, array $downloadFrom): static
    {
        if ([] === $downloadFrom) {
            unset($formFields['downloadFrom']);

            return $this;
        }

        $formFields['downloadFrom'] = [];

        foreach ($downloadFrom as $file) {
            if (!\array_key_exists('url', $file)) {
                throw new MissingRequiredFieldException('Missing field "url"');
            }

            $formFields['downloadFrom'][$file['url']] = $file;
        }

        return $this;
    }

    /**
     * @param array<string, array{url: string, extraHttpHeaders?: array<string, string>}> $value
     *
     * @return array<string, array<string|int, mixed>|string|\Stringable|int|float|bool|\BackedEnum|DataPart>
     */
    private function downloadFromNormalizer(array $value, callable $encoder): array
    {
        return $encoder('downloadFrom', array_values($value));
    }
}
