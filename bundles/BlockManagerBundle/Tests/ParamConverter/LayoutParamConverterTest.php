<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\ParamConverter;

use Netgen\Bundle\BlockManagerBundle\ParamConverter\LayoutParamConverter;
use Netgen\BlockManager\Core\Values\Page\Layout;
use Netgen\BlockManager\API\Values\Page\Layout as APILayout;
use Netgen\BlockManager\API\Service\LayoutService;

class LayoutParamConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $layoutServiceMock;

    /**
     * @var \Netgen\Bundle\BlockManagerBundle\ParamConverter\LayoutParamConverter
     */
    protected $paramConverter;

    public function setUp()
    {
        $this->layoutServiceMock = $this->getMock(LayoutService::class);

        $this->paramConverter = new LayoutParamConverter($this->layoutServiceMock);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\ParamConverter\LayoutParamConverter::getSourceAttributeName
     */
    public function testGetSourceAttributeName()
    {
        self::assertEquals('layout_id', $this->paramConverter->getSourceAttributeName());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\ParamConverter\LayoutParamConverter::getDestinationAttributeName
     */
    public function testGetDestinationAttributeName()
    {
        self::assertEquals('layout', $this->paramConverter->getDestinationAttributeName());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\ParamConverter\LayoutParamConverter::getSupportedClass
     */
    public function testGetSupportedClass()
    {
        self::assertEquals(APILayout::class, $this->paramConverter->getSupportedClass());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\ParamConverter\LayoutParamConverter::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\ParamConverter\LayoutParamConverter::loadValueObject
     */
    public function testLoadValueObject()
    {
        $layout = new Layout();

        $this->layoutServiceMock
            ->expects($this->once())
            ->method('loadLayout')
            ->with($this->equalTo(42), $this->equalTo(APILayout::STATUS_DRAFT))
            ->will($this->returnValue($layout));

        self::assertEquals($layout, $this->paramConverter->loadValueObject(42, APILayout::STATUS_DRAFT));
    }
}
