<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies;

use Psr\Log\LoggerInterface;
use Sensiolabs\GotenbergBundle\Version\Version;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;

trait LoggerAwareTrait
{
    use ServiceMethodsSubscriberTrait;

    abstract protected function getVersion(): Version;

    #[SubscribedService('logger', nullable: true)]
    protected function getLogger(): LoggerInterface|null
    {
        if (
            !$this->container->has('logger')
            || !($logger = $this->container->get('logger')) instanceof LoggerInterface) {
            return null;
        }

        return $logger;
    }

    /**
     * @param '>'|'<'|'>='|'<='|'=' $operator
     */
    protected function logWarningIfVersionIs(string $operator, string|Version $version, string|\Stringable $message): void
    {
        if ($this->getVersion()->compare($operator, $version)) {
            $inversedOperator = match ($operator) {
                '>' => '<=',
                '<' => '>=',
                '>=' => '<',
                '<=' => '>',
                '=' => '!=',
            };

            $this->getLogger()?->warning('Gotenberg {$operator} {$version} required: {$message}', [
                'operator' => $inversedOperator,
                'version' => $version,
                'message' => $message,
            ]);
        }
    }
}
