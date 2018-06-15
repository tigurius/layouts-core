<?php

declare(strict_types=1);

namespace Netgen\BlockManager\API\Values\Layout;

use Netgen\BlockManager\API\Values\Value;

interface Zone extends Value
{
    /**
     * Returns the zone identifier.
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Returns the ID of the layout to which this zone belongs.
     *
     * @return int|string
     */
    public function getLayoutId();

    /**
     * Returns if the zone has a linked zone.
     *
     * @return bool
     */
    public function hasLinkedZone(): bool;

    /**
     * Returns the linked zone or null if no linked zone exists.
     *
     * @return \Netgen\BlockManager\API\Values\Layout\Zone|null
     */
    public function getLinkedZone(): ?self;
}
