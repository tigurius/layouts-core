<?php

namespace Netgen\BlockManager\Collection\Result;

use Iterator;
use Netgen\BlockManager\API\Values\Collection\Collection;
use Netgen\BlockManager\API\Values\Collection\Item as CollectionItem;
use Netgen\BlockManager\Item\ItemInterface as CmsItem;
use Netgen\BlockManager\Item\ItemLoaderInterface;
use Netgen\BlockManager\Item\NullItem;

final class DynamicCollectionRunner implements CollectionRunnerInterface
{
    /**
     * @var \Netgen\BlockManager\Item\ItemLoaderInterface
     */
    private $itemLoader;

    /**
     * @var \Netgen\BlockManager\Collection\Result\QueryRunnerInterface
     */
    private $queryRunner;

    public function __construct(
        ItemLoaderInterface $itemLoader,
        QueryRunnerInterface $queryRunner
    ) {
        $this->itemLoader = $itemLoader;
        $this->queryRunner = $queryRunner;
    }

    public function __invoke(Collection $collection, $offset, $limit)
    {
        $queryIterator = $this->getQueryIterator($collection, $offset, $limit);

        for ($i = $offset, $max = $offset + $limit; $i < $max; ++$i) {
            $result = null;

            if ($collection->hasOverrideItem($i)) {
                $result = $this->buildOverrideResult($collection->getOverrideItem($i), $queryIterator);
            } elseif ($collection->hasManualItem($i)) {
                $result = $this->buildManualResult($collection->getManualItem($i), $queryIterator);
            } elseif ($queryIterator->valid()) {
                $result = new Result($i, $this->getQueryValue($queryIterator));
            }

            if (!$result instanceof Result) {
                return;
            }

            yield $result;
        }
    }

    public function count(Collection $collection)
    {
        $totalCount = $this->queryRunner->count($collection->getQuery());

        foreach ($collection->getItems() as $item) {
            if ($item->getPosition() > $totalCount) {
                break;
            }

            $manualItem = $this->getManualItem($item);
            if ($item->getType() === CollectionItem::TYPE_MANUAL || $item->getPosition() === $totalCount) {
                if ($item->isVisible() && $manualItem->isVisible() && !$manualItem instanceof NullItem) {
                    ++$totalCount;
                }
            }
        }

        return $totalCount;
    }

    private function buildOverrideResult(CollectionItem $collectionItem, Iterator $queryIterator)
    {
        // Override items always cover the dynamic item, even when invisible or invalid
        $manualItem = $this->getManualItem($collectionItem);
        $queryValue = $this->getQueryValue($queryIterator);

        if (!$collectionItem->isVisible() || !$manualItem->isVisible() || $manualItem instanceof NullItem) {
            if (!$queryValue instanceof CmsItem) {
                return null;
            }

            return new Result($collectionItem->getPosition(), $queryValue, $manualItem);
        }

        return new Result($collectionItem->getPosition(), $manualItem, $queryValue);
    }

    private function buildManualResult(CollectionItem $collectionItem, Iterator $queryIterator)
    {
        $manualItem = $this->getManualItem($collectionItem);

        if (!$collectionItem->isVisible() || !$manualItem->isVisible() || $manualItem instanceof NullItem) {
            // Manual items are replaced by dynamic ones only when invisible or invalid
            $queryValue = $this->getQueryValue($queryIterator);
            if (!$queryValue instanceof CmsItem) {
                return null;
            }

            return new Result($collectionItem->getPosition(), $queryValue, $manualItem);
        }

        return new Result($collectionItem->getPosition(), $manualItem);
    }

    private function getManualItem(CollectionItem $collectionItem)
    {
        $cmsItem = $this->itemLoader->load(
            $collectionItem->getValue(),
            $collectionItem->getValueType()
        );

        if ($cmsItem instanceof NullItem) {
            return $cmsItem;
        }

        return new ManualItem($cmsItem, $collectionItem);
    }

    private function getQueryValue(Iterator $queryIterator)
    {
        if (!$queryIterator->valid()) {
            return null;
        }

        $queryValue = $queryIterator->current();
        $queryIterator->next();

        return $queryValue;
    }

    private function getQueryIterator(Collection $collection, $offset, $limit)
    {
        $queryOffset = $offset - $this->getManualItemsCount($collection, 0, $offset);
        $queryLimit = $limit - $this->getManualItemsCount($collection, $offset, $offset + $limit);

        return call_user_func($this->queryRunner, $collection->getQuery(), $queryOffset, $queryLimit);
    }

    /**
     * Returns the count of valid manual items in a collection between $startOffset and $endOffset.
     *
     * @param \Netgen\BlockManager\API\Values\Collection\Collection $collection
     * @param int $startOffset
     * @param int $endOffset
     *
     * @return int
     */
    private function getManualItemsCount(Collection $collection, $startOffset, $endOffset)
    {
        return count(
            array_filter(
                $collection->getManualItems(),
                function (CollectionItem $item) use ($startOffset, $endOffset) {
                    $manualItem = $this->getManualItem($item);
                    if (!$item->isVisible() || !$manualItem->isVisible() || $manualItem instanceof NullItem) {
                        return false;
                    }

                    return $item->getPosition() >= $startOffset && $item->getPosition() < $endOffset;
                }
            )
        );
    }
}
