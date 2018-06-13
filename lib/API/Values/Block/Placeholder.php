<?php

declare(strict_types=1);

namespace Netgen\BlockManager\API\Values\Block;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface Placeholder extends ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Returns the placeholder identifier.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Returns all blocks in this placeholder.
     *
     * @return \Netgen\BlockManager\API\Values\Block\Block[]
     */
    public function getBlocks();
}
