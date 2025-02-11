<?php

namespace Sensiolabs\GotenbergBundle\NodeBuilder;

use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Symfony\Component\Config\Definition\Builder\EnumNodeDefinition;

final class EnumNodeBuilder extends NodeBuilder implements NodeBuilderInterface
{
    /** @var callable|string|null */
    public $callback;

    public function __construct(
        protected string $name,

        public string|null $defaultValue = null,

        /** @var class-string|null */
        public string|null $className = null,

        public array $values = [],

        string|callable|null $callback = null,
    ) {
        parent::__construct($name);
        $this->callback = $callback;
    }

    public function create(): EnumNodeDefinition
    {
        $node = new EnumNodeDefinition($this->name);

        if (count($this->values) > 0 && null !== $this->callback) {
            throw new InvalidBuilderConfiguration(\sprintf('You must choose between "values" or "callback" to provide any choice for "%s".', $this->name));
        }

        if (\count($this->values) > 0) {
            $node->values($this->values);
        }

        if (null !== $this->callback) {
            if (!\is_callable($this->callback)) {
                throw new InvalidBuilderConfiguration(\sprintf('The Builder constraint expects a valid callback for "%s".', $this->name));
            }

            $node->values(\call_user_func($this->callback));
        }

        if (null !== $this->className) {
            $classImplements = class_implements($this->className);
            if (false === $classImplements) {
                throw new InvalidBuilderConfiguration(\sprintf('The "class" option expects a valid class "\BackedEnum" for "%s".', $this->className));
            }

            if (!\in_array('BackedEnum', $classImplements, true)) {
                throw new InvalidBuilderConfiguration(\sprintf('The "class" option expects a valid class "\BackedEnum" for "%s".', $this->name));
            }

            $className = $this->className;

            $node->beforeNormalization()->ifString()->then(static function (string $value) use ($className): \BackedEnum {
                return $className::from($value);
            });
        }

        $node->defaultValue($this->defaultValue);

        return $node;
    }
}
