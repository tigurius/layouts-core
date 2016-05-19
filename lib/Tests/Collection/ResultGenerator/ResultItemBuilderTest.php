<?php

namespace Netgen\BlockManager\Tests\Collection\ResultGenerator;

use Netgen\BlockManager\Collection\ResultGenerator\ResultValueBuilderInterface;
use Netgen\BlockManager\Collection\ResultGenerator\ResultItemBuilder;
use Netgen\BlockManager\Collection\ResultItem;
use Netgen\BlockManager\Collection\ResultValue;
use Netgen\BlockManager\Core\Values\Collection\Item;
use Netgen\BlockManager\Tests\Collection\Stubs\Value;

class ResultItemBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultValueBuilderMock;

    /**
     * @var \Netgen\BlockManager\Collection\ResultGenerator\ResultItemBuilder
     */
    protected $builder;

    public function setUp()
    {
        $this->resultValueBuilderMock = $this->getMock(ResultValueBuilderInterface::class);

        $this->builder = new ResultItemBuilder($this->resultValueBuilderMock);
    }

    /**
     * @covers \Netgen\BlockManager\Collection\ResultGenerator\ResultItemBuilder::__construct
     * @covers \Netgen\BlockManager\Collection\ResultGenerator\ResultItemBuilder::build
     */
    public function testBuild()
    {
        $value = new Value(42);

        $this->resultValueBuilderMock
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo(new Value(42)))
            ->will($this->returnValue(new ResultValue()));

        $resultItem = new ResultItem(
            array(
                'value' => new ResultValue(),
                'collectionItem' => null,
                'type' => ResultItem::TYPE_DYNAMIC,
                'position' => 5,
            )
        );

        self::assertEquals($resultItem, $this->builder->build($value, 5));
    }

    /**
     * @covers \Netgen\BlockManager\Collection\ResultGenerator\ResultItemBuilder::__construct
     * @covers \Netgen\BlockManager\Collection\ResultGenerator\ResultItemBuilder::buildFromItem
     */
    public function testBuildFromItem()
    {
        $item = new Item(
            array(
                'type' => Item::TYPE_MANUAL,
                'valueId' => 42,
                'valueType' => 'value',
            )
        );

        $this->resultValueBuilderMock
            ->expects($this->once())
            ->method('buildFromItem')
            ->with($this->equalTo($item))
            ->will($this->returnValue(new ResultValue()));

        $resultItem = new ResultItem(
            array(
                'value' => new ResultValue(),
                'collectionItem' => $item,
                'type' => ResultItem::TYPE_MANUAL,
                'position' => 5,
            )
        );

        self::assertEquals($resultItem, $this->builder->buildFromItem($item, 5));
    }
}
