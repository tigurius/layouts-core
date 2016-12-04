<?php

namespace Netgen\BlockManager\Layout\Resolver\Registry;

use Netgen\BlockManager\Exception\InvalidArgumentException;
use Netgen\BlockManager\Layout\Resolver\TargetTypeInterface;

class TargetTypeRegistry implements TargetTypeRegistryInterface
{
    /**
     * @var \Netgen\BlockManager\Layout\Resolver\TargetTypeInterface[]
     */
    protected $targetTypes = array();

    /**
     * Adds a target type to registry.
     *
     * @param \Netgen\BlockManager\Layout\Resolver\TargetTypeInterface $targetType
     */
    public function addTargetType(TargetTypeInterface $targetType)
    {
        $this->targetTypes[$targetType->getType()] = $targetType;
    }

    /**
     * Returns if registry has a target type.
     *
     * @param string $type
     *
     * @return bool
     */
    public function hasTargetType($type)
    {
        return isset($this->targetTypes[$type]);
    }

    /**
     * Returns a target type with provided type.
     *
     * @param string $type
     *
     * @throws \Netgen\BlockManager\Exception\InvalidArgumentException If target type does not exist
     *
     * @return \Netgen\BlockManager\Layout\Resolver\TargetTypeInterface
     */
    public function getTargetType($type)
    {
        if (!$this->hasTargetType($type)) {
            throw new InvalidArgumentException(
                'type',
                sprintf(
                    'Target type "%s" does not exist.',
                    $type
                )
            );
        }

        return $this->targetTypes[$type];
    }

    /**
     * Returns all target types.
     *
     * @return \Netgen\BlockManager\Layout\Resolver\TargetTypeInterface[]
     */
    public function getTargetTypes()
    {
        return $this->targetTypes;
    }
}
