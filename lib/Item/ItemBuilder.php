<?php

namespace Netgen\BlockManager\Item;

use Netgen\BlockManager\Exception\Item\ValueException;
use Netgen\BlockManager\Exception\RuntimeException;

class ItemBuilder implements ItemBuilderInterface
{
    /**
     * @var \Netgen\BlockManager\Item\ValueConverterInterface[]
     */
    protected $valueConverters = array();

    /**
     * Constructor.
     *
     * @param \Netgen\BlockManager\Item\ValueConverterInterface[] $valueConverters
     */
    public function __construct(array $valueConverters = array())
    {
        foreach ($valueConverters as $valueConverter) {
            if (!$valueConverter instanceof ValueConverterInterface) {
                throw new RuntimeException(
                    sprintf(
                        'Value converter "%s" needs to implement ValueConverterInterface.',
                        get_class($valueConverter)
                    )
                );
            }
        }

        $this->valueConverters = $valueConverters;
    }

    /**
     * Builds the item from provided object.
     *
     * @param mixed $object
     *
     * @throws \Netgen\BlockManager\Exception\Item\ValueException if value converter does not exist
     *
     * @return \Netgen\BlockManager\Item\ItemInterface
     */
    public function build($object)
    {
        foreach ($this->valueConverters as $valueConverter) {
            if (!$valueConverter->supports($object)) {
                continue;
            }

            $value = new Item(
                array(
                    'valueId' => $valueConverter->getId($object),
                    'valueType' => $valueConverter->getValueType($object),
                    'name' => $valueConverter->getName($object),
                    'isVisible' => $valueConverter->getIsVisible($object),
                    'object' => $object,
                )
            );

            return $value;
        }

        throw ValueException::noValueConverter(
            is_object($object) ? get_class($object) : gettype($object)
        );
    }
}
