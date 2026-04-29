<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Builder\ValueObject;

final class EmbeddedFile extends \SplFileInfo
{
    private string|null $relationship;
    private string|null $mimeType;

    public function __construct(string $path, string|null $relationship = null, string|null $mimeType = null)
    {
        parent::__construct($path);
        $this->relationship = $relationship;
        $this->mimeType = $mimeType;
    }

    public function getRelationship(): string|null
    {
        return $this->relationship;
    }

    public function getMimeType(): string|null
    {
        return $this->mimeType;
    }
}
