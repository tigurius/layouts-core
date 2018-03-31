<?php

namespace Netgen\BlockManager\Serializer\Normalizer\V1;

use Netgen\BlockManager\Collection\Result\ManualItem;
use Netgen\BlockManager\Collection\Result\Result;
use Netgen\BlockManager\Collection\Result\Slot;
use Netgen\BlockManager\Item\NullItem;
use Netgen\BlockManager\Item\UrlBuilderInterface;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\Serializer\Version;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CollectionResultNormalizer implements NormalizerInterface
{
    /**
     * @var \Netgen\BlockManager\Item\UrlBuilderInterface
     */
    private $urlBuilder;

    public function __construct(UrlBuilderInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    public function normalize($object, $format = null, array $context = array())
    {
        /** @var \Netgen\BlockManager\Collection\Result\Result $result */
        $result = $object->getValue();
        $cmsItem = $result->getItem();
        $collectionItem = $cmsItem instanceof ManualItem ? $cmsItem->getCollectionItem() : null;

        $itemUrl = null;
        if (!$cmsItem instanceof NullItem && !$cmsItem instanceof Slot) {
            $itemUrl = $this->urlBuilder->getUrl($cmsItem);
        }

        return array(
            'id' => $collectionItem !== null ? $collectionItem->getId() : null,
            'collection_id' => $collectionItem !== null ? $collectionItem->getCollectionId() : null,
            'position' => $result->getPosition(),
            'type' => $result->getItem() instanceof ManualItem ? Result::TYPE_MANUAL : Result::TYPE_DYNAMIC,
            'value' => $cmsItem->getValue(),
            'value_type' => $cmsItem->getValueType(),
            'visible' => $collectionItem !== null ? $collectionItem->isVisible() : true,
            'scheduled' => $collectionItem !== null ? $collectionItem->isScheduled() : false,
            'name' => $cmsItem->getName(),
            'cms_url' => $itemUrl,
            'cms_visible' => $cmsItem->isVisible(),
        );
    }

    public function supportsNormalization($data, $format = null)
    {
        if (!$data instanceof VersionedValue) {
            return false;
        }

        return $data->getValue() instanceof Result && $data->getVersion() === Version::API_V1;
    }
}
