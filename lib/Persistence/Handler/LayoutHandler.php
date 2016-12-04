<?php

namespace Netgen\BlockManager\Persistence\Handler;

use Netgen\BlockManager\Persistence\Values\Page\Layout;
use Netgen\BlockManager\Persistence\Values\Page\LayoutCreateStruct;
use Netgen\BlockManager\Persistence\Values\Page\LayoutUpdateStruct;
use Netgen\BlockManager\Persistence\Values\Page\Zone;
use Netgen\BlockManager\Persistence\Values\Page\ZoneCreateStruct;
use Netgen\BlockManager\Persistence\Values\Page\ZoneUpdateStruct;

interface LayoutHandler
{
    /**
     * Loads a layout with specified ID.
     *
     * @param int|string $layoutId
     * @param int $status
     *
     * @throws \Netgen\BlockManager\Exception\NotFoundException If layout with specified ID does not exist
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Layout
     */
    public function loadLayout($layoutId, $status);

    /**
     * Loads a zone with specified identifier.
     *
     * @param int|string $layoutId
     * @param int $status
     * @param string $identifier
     *
     * @throws \Netgen\BlockManager\Exception\NotFoundException If layout with specified ID or zone with specified identifier do not exist
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Zone
     */
    public function loadZone($layoutId, $status, $identifier);

    /**
     * Loads all layouts. If $includeDrafts is set to true, drafts which have no
     * published status will also be included.
     *
     * @param bool $includeDrafts
     * @param int $offset
     * @param int $limit
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Layout[]
     */
    public function loadLayouts($includeDrafts = false, $offset = 0, $limit = null);

    /**
     * Loads all shared layouts. If $includeDrafts is set to true, drafts which have no
     * published status will also be included.
     *
     * @param bool $includeDrafts
     * @param int $offset
     * @param int $limit
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Layout[]
     */
    public function loadSharedLayouts($includeDrafts = false, $offset = 0, $limit = null);

    /**
     * Returns if layout with specified ID exists.
     *
     * @param int|string $layoutId
     * @param int $status
     *
     * @return bool
     */
    public function layoutExists($layoutId, $status);

    /**
     * Returns if zone with specified identifier exists in the layout.
     *
     * @param int|string $layoutId
     * @param int $status
     * @param string $identifier
     *
     * @return bool
     */
    public function zoneExists($layoutId, $status, $identifier);

    /**
     * Loads all zones that belong to layout with specified ID.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Page\Layout $layout
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Zone[]
     */
    public function loadLayoutZones(Layout $layout);

    /**
     * Returns if layout with provided name exists.
     *
     * @param string $name
     * @param int|string $excludedLayoutId
     * @param int $status
     *
     * @return bool
     */
    public function layoutNameExists($name, $excludedLayoutId = null, $status = null);

    /**
     * Creates a layout.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Page\LayoutCreateStruct $layoutCreateStruct
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Layout
     */
    public function createLayout(LayoutCreateStruct $layoutCreateStruct);

    /**
     * Creates a zone in provided layout.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Page\Layout $layout
     * @param \Netgen\BlockManager\Persistence\Values\Page\ZoneCreateStruct $zoneCreateStruct
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Zone
     */
    public function createZone(Layout $layout, ZoneCreateStruct $zoneCreateStruct);

    /**
     * Updates a layout with specified ID.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Page\Layout $layout
     * @param \Netgen\BlockManager\Persistence\Values\Page\LayoutUpdateStruct $layoutUpdateStruct
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Layout
     */
    public function updateLayout(Layout $layout, LayoutUpdateStruct $layoutUpdateStruct);

    /**
     * Updates a specified zone.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Page\Zone $zone
     * @param \Netgen\BlockManager\Persistence\Values\Page\ZoneUpdateStruct $zoneUpdateStruct
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Zone
     */
    public function updateZone(Zone $zone, ZoneUpdateStruct $zoneUpdateStruct);

    /**
     * Copies the layout.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Page\Layout $layout
     * @param string $newName
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Layout
     */
    public function copyLayout(Layout $layout, $newName);

    /**
     * Creates a new layout status.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Page\Layout $layout $layout
     * @param int $newStatus
     *
     * @return \Netgen\BlockManager\Persistence\Values\Page\Layout
     */
    public function createLayoutStatus(Layout $layout, $newStatus);

    /**
     * Deletes a layout with specified ID.
     *
     * @param int|string $layoutId
     * @param int $status
     */
    public function deleteLayout($layoutId, $status = null);
}
