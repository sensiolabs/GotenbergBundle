<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Config\Definition\Builder\EnumNodeDefinition;

class EnumNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    /** @var callable|string|null */
    public $callback;

    public function __construct(
        protected string $name,

        public string|null $defaultValue = null,

        /**
         * @var array<array-key, mixed>
         */
        public array $values = [],

        string|callable|null $callback = null,
    ) {
        parent::__construct($name);
        $this->callback = $callback;
    }

    public function create(): EnumNodeDefinition
    {
        $node = new EnumNodeDefinition($this->name);

        if (\count($this->values) === 0 && null === $this->callback) {
            throw new InvalidBuilderConfiguration(\sprintf('You must choose between "values" or "callback" to provide any choice for "%s".', $this->name));
        }

        if (\count($this->values) > 0 && null !== $this->callback) {
            throw new InvalidBuilderConfiguration(\sprintf('You must choose between "values" or "callback" to provide any choice for "%s".', $this->name));
        }

        $values = [];

        if (\count($this->values) > 0) {
            $values = $this->values;
        } elseif (null !== $this->callback) {
            if (\is_string($this->callback)) {
                if (is_a($this->callback, \BackedEnum::class, true) === false) {
                    throw new InvalidBuilderConfiguration('The class from the "callback" option is not a valid class "\BackedEnum"');
                }

                $this->callback = [$this->callback, 'cases'];
            }

            $values = array_map(static function (mixed $value): int|string|float|bool|null {
                if ($value instanceof \BackedEnum) {
                    return $value->value;
                }

                return $value;
            }, ($this->callback)());
        }

        $node->values($values);

        if (null !== $this->defaultValue) {
            if (!\in_array($this->defaultValue, $values, true)) {
                throw new InvalidBuilderConfiguration(\sprintf('The default value "%s" is not part of the configured values "%s".', $this->defaultValue, implode(', ', $values)));
            }
        }

        $node->defaultValue($this->defaultValue);

        return $node;
    }
}
