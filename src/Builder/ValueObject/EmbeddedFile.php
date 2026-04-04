<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Builder\ValueObject;

final class EmbeddedFile extends \SplFileInfo
{
    private string|null $relationship;
    private string|null $mimeType;

    public function __construct(string $path, ?string $relationship = null, ?string $mimeType = null)
    {
        parent::__construct($path);
        $this->relationship = $relationship;
        $this->mimeType = $mimeType;
    }

    public function getRelationship(): ?string
    {
        return $this->relationship;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }
}
