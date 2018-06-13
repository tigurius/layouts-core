<?php

declare(strict_types=1);

namespace Netgen\BlockManager\HttpCache;

final class NullClient implements ClientInterface
{
    public function invalidateLayouts(array $layoutIds)
    {
    }

    public function invalidateAllLayouts()
    {
    }

    public function invalidateBlocks(array $blockIds)
    {
    }

    public function invalidateLayoutBlocks(array $layoutIds)
    {
    }

    public function invalidateAllBlocks()
    {
    }

    public function commit()
    {
        return true;
    }
}
