<?php

namespace Sensiolabs\GotenbergBundle\Builder;

use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Client\PdfResponse;
use Symfony\Component\String\UnicodeString;

abstract class AbstractPdfBuilder implements PdfBuilderInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $formFields = [];

    public function __construct(protected readonly GotenbergClientInterface $gotenbergClient, protected readonly string $projectDir)
    {
    }

    public function generate(): PdfResponse
    {
        return $this->gotenbergClient->post($this->getEndpoint(), $this->getMultipartFormData());
    }

    /**
     * To set configurations by an array of configurations.
     *
     * @param array<string, mixed> $configurations
     */
    public function setConfigurations(array $configurations): static
    {
        foreach ($configurations as $property => $value) {
            $method = (new UnicodeString($property))->camel()->toString();
            if (!method_exists($this, $method)) {
                throw new \InvalidArgumentException(sprintf('Invalid option "%s": the method "%s" does not exist in class "%s".', $property, $method, static::class));
            }

            $this->{$method}($value);
        }

        return $this;
    }
}
