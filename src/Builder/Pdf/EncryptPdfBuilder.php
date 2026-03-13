<?php

namespace Sensiolabs\GotenbergBundle\Builder\Pdf;

use Sensiolabs\GotenbergBundle\Builder\AbstractBuilder;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithBuilderConfiguration;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\AssetBaseDirFormatterAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\DownloadFromTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\EncryptTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\FilesTrait;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\WebhookTrait;
use Sensiolabs\GotenbergBundle\Exception\MissingRequiredFieldException;

/**
 * You may encrypt a PDF after it is created.
 *
 * You must provide at least a user password.
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs
 */
#[WithBuilderConfiguration(type: 'pdf', name: 'encrypt')]
final class EncryptPdfBuilder extends AbstractBuilder
{
    use AssetBaseDirFormatterAwareTrait;
    use DownloadFromTrait;
    use EncryptTrait;
    use FilesTrait;
    use WebhookTrait;

    public const ENDPOINT = '/forms/pdfengines/encrypt';

    private const AVAILABLE_EXTENSIONS = [
        'pdf',
    ];

    protected function getAllowedFilesExtensions(): array
    {
        return self::AVAILABLE_EXTENSIONS;
    }

    protected function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    protected function validatePayloadBody(): void
    {
        $this->introducedIn('8.25');

        if ($this->getBodyBag()->get('userPassword') === null) {
            throw new MissingRequiredFieldException('At least userPassword must be provided.');
        }

        if ($this->getBodyBag()->get('files') === null && $this->getBodyBag()->get('downloadFrom') === null) {
            throw new MissingRequiredFieldException('At least one PDF file is required.');
        }
    }
}
