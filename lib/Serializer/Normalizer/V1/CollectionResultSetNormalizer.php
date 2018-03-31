<?php

namespace Netgen\BlockManager\Serializer\Normalizer\V1;

use Netgen\BlockManager\API\Values\Collection\Item;
use Netgen\BlockManager\Collection\Result\ManualItem;
use Netgen\BlockManager\Collection\Result\Result;
use Netgen\BlockManager\Collection\Result\ResultSet;
use Netgen\BlockManager\Serializer\SerializerAwareTrait;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\Serializer\Version;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;

final class CollectionResultSetNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($object, $format = null, array $context = array())
    {
        /** @var \Netgen\BlockManager\Collection\Result\ResultSet $resultSet */
        $resultSet = $object->getValue();

        $results = array();
        foreach ($resultSet as $result) {
            $results[] = new VersionedValue($result, $object->getVersion());
        }

        $overflowItems = array();
        foreach ($this->getoverflowItems($resultSet) as $overflowItem) {
            $overflowItems[] = new VersionedValue($overflowItem, $object->getVersion());
        }

        return array(
            'items' => $this->serializer->normalize($results, $format, $context),
            'overflow_items' => $this->serializer->normalize($overflowItems, $format, $context),
        );
    }

    public function supportsNormalization($data, $format = null)
    {
        if (!$data instanceof VersionedValue) {
            return false;
        }

        return $data->getValue() instanceof ResultSet && $data->getVersion() === Version::API_V1;
    }

    /**
     * Returns all items from the collection which are overflown. Overflown items
     * are those NOT included in the provided result set, as defined by collection
     * offset and limit.
     *
     * @param \Netgen\BlockManager\Collection\Result\ResultSet $resultSet
     *
     * @return \Netgen\BlockManager\Collection\Result\Result[]
     */
    private function getOverflowItems(ResultSet $resultSet)
    {
        $includedPositions = array_map(
            function (Result $result) {
                $manualItem = $result->getItem();
                if (!$manualItem instanceof ManualItem) {
                    return null;
                }

                return $manualItem->getCollectionItem()->getPosition();
            },
            $resultSet->getResults()
        );

        return array_filter(
            $resultSet->getCollection()->getItems(),
            function (Item $item) use ($includedPositions) {
                return !in_array($item->getPosition(), $includedPositions, true);
            }
        );
    }
}
