<?php

namespace Netgen\BlockManager\Tests\Core\Values\Page;

use Netgen\BlockManager\API\Values\Page\Layout;
use Netgen\BlockManager\Core\Values\Page\Block;
use Netgen\BlockManager\Core\Values\Page\Zone;
use PHPUnit\Framework\TestCase;

class ZoneTest extends TestCase
{
    /**
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::__construct
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getIdentifier
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getLayoutId
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getStatus
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getLinkedZone
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getBlocks
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::count
     */
    public function testSetDefaultProperties()
    {
        $zone = new Zone();

        $this->assertNull($zone->getIdentifier());
        $this->assertNull($zone->getLayoutId());
        $this->assertNull($zone->getStatus());
        $this->assertNull($zone->getLinkedZone());
        $this->assertEquals(array(), $zone->getBlocks());
        $this->assertEquals(0, count($zone));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::__construct
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getIdentifier
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getLayoutId
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getStatus
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getLinkedZone
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getBlocks
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::count
     */
    public function testSetProperties()
    {
        $zone = new Zone(
            array(
                'identifier' => 'left',
                'layoutId' => 84,
                'status' => Layout::STATUS_PUBLISHED,
                'linkedZone' => null,
                'blocks' => array(
                    new Block(),
                ),
            )
        );

        $this->assertEquals('left', $zone->getIdentifier());
        $this->assertEquals(84, $zone->getLayoutId());
        $this->assertEquals(Layout::STATUS_PUBLISHED, $zone->getStatus());
        $this->assertEquals(array(new Block()), $zone->getBlocks());
        $this->assertNull($zone->getLinkedZone());
        $this->assertEquals(1, count($zone));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::__construct
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getIdentifier
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getLayoutId
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getStatus
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getLinkedZone
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::getBlocks
     * @covers \Netgen\BlockManager\Core\Values\Page\Zone::count
     */
    public function testIsEmptyWithLinkedLayout()
    {
        $linkedZone = new Zone(
            array(
                'identifier' => 'right',
                'layoutId' => 42,
                'blocks' => array(
                    new Block(),
                ),
            )
        );

        $zone = new Zone(
            array(
                'identifier' => 'left',
                'layoutId' => 84,
                'status' => Layout::STATUS_PUBLISHED,
                'linkedZone' => $linkedZone,
                'blocks' => array(),
            )
        );

        $this->assertEquals('left', $zone->getIdentifier());
        $this->assertEquals(84, $zone->getLayoutId());
        $this->assertEquals(Layout::STATUS_PUBLISHED, $zone->getStatus());
        $this->assertEquals(array(), $zone->getBlocks());
        $this->assertEquals($linkedZone, $zone->getLinkedZone());
        $this->assertEquals(1, count($zone));
    }
}
