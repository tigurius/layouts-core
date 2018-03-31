<?php

namespace Netgen\BlockManager\Transfer\Output\Visitor;

use Netgen\BlockManager\API\Values\Collection\Item as ItemValue;
use Netgen\BlockManager\Exception\RuntimeException;
use Netgen\BlockManager\Item\ItemLoaderInterface;
use Netgen\BlockManager\Transfer\Output\Visitor;
use Netgen\BlockManager\Transfer\Output\VisitorInterface;

/**
 * Item value visitor.
 *
 * @see \Netgen\BlockManager\API\Values\Collection\Item
 */
final class Item extends Visitor
{
    /**
     * @var \Netgen\BlockManager\Item\ItemLoaderInterface
     */
    private $itemLoader;

    public function __construct(ItemLoaderInterface $itemLoader)
    {
        $this->itemLoader = $itemLoader;
    }

    public function accept($value)
    {
        return $value instanceof ItemValue;
    }

    public function visit($collectionItem, VisitorInterface $subVisitor = null)
    {
        if ($subVisitor === null) {
            throw new RuntimeException('Implementation requires sub-visitor');
        }

        /* @var \Netgen\BlockManager\API\Values\Collection\Item $collectionItem */

        $item = $this->itemLoader->load(
            $collectionItem->getValue(),
            $collectionItem->getValueType()
        );

        return array(
            'id' => $collectionItem->getId(),
            'type' => $this->getTypeString($collectionItem),
            'position' => $collectionItem->getPosition(),
            'value' => $item->getRemoteId(),
            'value_type' => $collectionItem->getValueType(),
            'configuration' => $this->visitConfiguration($collectionItem, $subVisitor),
        );
    }

    /**
     * Visit the given $item configuration into hash representation.
     *
     * @param \Netgen\BlockManager\API\Values\Collection\Item $item
     * @param \Netgen\BlockManager\Transfer\Output\VisitorInterface $subVisitor
     *
     * @return array
     */
    private function visitConfiguration(ItemValue $item, VisitorInterface $subVisitor)
    {
        $configs = $item->getConfigs();
        if (empty($configs)) {
            return null;
        }

        $hash = array();

        foreach ($configs as $config) {
            $hash[$config->getConfigKey()] = $subVisitor->visit($config);
        }

        return $hash;
    }

    /**
     * Return type string representation for the given $item.
     *
     * @param \Netgen\BlockManager\API\Values\Collection\Item $item
     *
     * @throws \Netgen\BlockManager\Exception\RuntimeException If status is not recognized
     *
     * @return string
     */
    private function getTypeString(ItemValue $item)
    {
        switch ($item->getType()) {
            case ItemValue::TYPE_MANUAL:
                return 'MANUAL';
            case ItemValue::TYPE_OVERRIDE:
                return 'OVERRIDE';
        }

        $typeString = var_export($item->getType(), true);

        throw new RuntimeException(sprintf("Unknown type '%s'", $typeString));
    }
}
