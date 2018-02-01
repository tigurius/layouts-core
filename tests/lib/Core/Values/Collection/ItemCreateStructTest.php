<?php

namespace Netgen\BlockManager\Tests\Core\Values\Collection;

use Netgen\BlockManager\API\Values\Collection\Item;
use Netgen\BlockManager\API\Values\Collection\ItemCreateStruct;
use Netgen\BlockManager\Tests\Collection\Stubs\ItemDefinition;
use PHPUnit\Framework\TestCase;

final class ItemCreateStructTest extends TestCase
{
    public function testDefaultProperties()
    {
        $itemCreateStruct = new ItemCreateStruct();

        $this->assertNull($itemCreateStruct->definition);
        $this->assertNull($itemCreateStruct->value);
        $this->assertEquals(Item::TYPE_MANUAL, $itemCreateStruct->type);
    }

    public function testSetProperties()
    {
        $itemCreateStruct = new ItemCreateStruct(
            array(
                'value' => 3,
                'definition' => new ItemDefinition('type'),
                'type' => Item::TYPE_OVERRIDE,
            )
        );

        $this->assertEquals(new ItemDefinition('type'), $itemCreateStruct->definition);
        $this->assertEquals(3, $itemCreateStruct->value);
        $this->assertEquals(Item::TYPE_OVERRIDE, $itemCreateStruct->type);
    }
}
