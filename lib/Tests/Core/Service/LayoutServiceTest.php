<?php

namespace Netgen\BlockManager\Tests\Core\Service;

use Netgen\BlockManager\Exception\NotFoundException;
use Netgen\BlockManager\Configuration\LayoutType\LayoutType;
use Netgen\BlockManager\Configuration\LayoutType\Zone as LayoutTypeZone;
use Netgen\BlockManager\Configuration\Registry\LayoutTypeRegistry;
use Netgen\BlockManager\Core\Service\Validator\LayoutValidator;
use Netgen\BlockManager\API\Values\LayoutCreateStruct;
use Netgen\BlockManager\API\Values\Page\Layout;
use Netgen\BlockManager\API\Values\Page\Zone;

abstract class LayoutServiceTest extends ServiceTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $layoutValidatorMock;

    /**
     * @var \Netgen\BlockManager\Configuration\Registry\LayoutTypeRegistryInterface
     */
    protected $layoutTypeRegistry;

    /**
     * @var \Netgen\BlockManager\API\Service\LayoutService
     */
    protected $layoutService;

    /**
     * Sets up the tests.
     */
    public function setUp()
    {
        $this->layoutValidatorMock = $this->getMockBuilder(LayoutValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $layoutType = new LayoutType(
            '3_zones_a',
            true,
            '3 zones A',
            array(
                new LayoutTypeZone('left', 'Left', array()),
                new LayoutTypeZone('right', 'Right', array()),
                new LayoutTypeZone('bottom', 'Bottom', array()),
            )
        );

        $this->layoutTypeRegistry = new LayoutTypeRegistry();
        $this->layoutTypeRegistry->addLayoutType('3_zones_a', $layoutType);

        $this->layoutService = $this->createLayoutService(
            $this->layoutValidatorMock,
            $this->layoutTypeRegistry
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::__construct
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::loadLayout
     */
    public function testLoadLayout()
    {
        $layout = $this->layoutService->loadLayout(1);

        self::assertInstanceOf(Layout::class, $layout);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::loadLayout
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     */
    public function testLoadLayoutThrowsNotFoundException()
    {
        $this->layoutService->loadLayout(999999);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::loadZone
     */
    public function testLoadZone()
    {
        $zone = $this->layoutService->loadZone(1, 'top_left');

        self::assertInstanceOf(Zone::class, $zone);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::loadZone
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     */
    public function testLoadZoneThrowsNotFoundExceptionOnNonExistingLayout()
    {
        $this->layoutService->loadZone(999999, 'bottom');
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::loadZone
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     */
    public function testLoadZoneThrowsNotFoundExceptionOnNonExistingZone()
    {
        $this->layoutService->loadZone(1, 'not_existing');
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::createLayout
     */
    public function testCreateLayout()
    {
        $layoutCreateStruct = $this->layoutService->newLayoutCreateStruct(
            '3_zones_a',
            'My layout'
        );

        $createdLayout = $this->layoutService->createLayout($layoutCreateStruct);

        self::assertInstanceOf(Layout::class, $createdLayout);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::createLayout
     * @expectedException \Netgen\BlockManager\Exception\InvalidArgumentException
     */
    public function testCreateLayoutThrowsInvalidArgumentException()
    {
        $layoutCreateStruct = $this->layoutService->newLayoutCreateStruct(
            'non_existing',
            'My layout'
        );

        $this->layoutService->createLayout($layoutCreateStruct);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::copyLayout
     */
    public function testCopyLayout()
    {
        $layout = $this->layoutService->loadLayout(1);
        $copiedLayout = $this->layoutService->copyLayout($layout);

        self::assertInstanceOf(Layout::class, $copiedLayout);

        self::assertEquals(4, $copiedLayout->getId());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::createDraft
     */
    public function testCreateDraft()
    {
        $layout = $this->layoutService->loadLayout(3);
        $draftLayout = $this->layoutService->createDraft($layout);

        self::assertInstanceOf(Layout::class, $draftLayout);
        self::assertEquals(Layout::STATUS_DRAFT, $draftLayout->getStatus());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::createDraft
     * @expectedException \Netgen\BlockManager\Exception\BadStateException
     */
    public function testCreateDraftThrowsBadStateExceptionIfLayoutIsNotPublished()
    {
        $layout = $this->layoutService->loadLayout(1, Layout::STATUS_DRAFT);
        $this->layoutService->createDraft($layout);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::createDraft
     * @expectedException \Netgen\BlockManager\Exception\BadStateException
     */
    public function testCreateDraftThrowsBadStateExceptionIfDraftAlreadyExists()
    {
        $layout = $this->layoutService->loadLayout(1, Layout::STATUS_PUBLISHED);
        $this->layoutService->createDraft($layout);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::discardDraft
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     */
    public function testDiscardDraft()
    {
        $layout = $this->layoutService->loadLayout(1, Layout::STATUS_DRAFT);
        $this->layoutService->discardDraft($layout);

        $this->layoutService->loadLayout($layout->getId(), Layout::STATUS_DRAFT);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::discardDraft
     * @expectedException \Netgen\BlockManager\Exception\BadStateException
     */
    public function testDiscardDraftThrowsBadStateException()
    {
        $layout = $this->layoutService->loadLayout(1);
        $this->layoutService->discardDraft($layout);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::publishLayout
     */
    public function testPublishLayout()
    {
        $layout = $this->layoutService->loadLayout(1, Layout::STATUS_DRAFT);
        $publishedLayout = $this->layoutService->publishLayout($layout);

        self::assertInstanceOf(Layout::class, $publishedLayout);
        self::assertEquals(Layout::STATUS_PUBLISHED, $publishedLayout->getStatus());

        $archivedLayout = $this->layoutService->loadLayout($layout->getId(), Layout::STATUS_ARCHIVED);
        self::assertInstanceOf(Layout::class, $archivedLayout);

        try {
            $this->layoutService->loadLayout($layout->getId(), Layout::STATUS_DRAFT);
            self::fail('Draft layout still exists after publishing.');
        } catch (NotFoundException $e) {
            // Do nothing
        }
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::publishLayout
     * @expectedException \Netgen\BlockManager\Exception\BadStateException
     */
    public function testPublishLayoutThrowsBadStateException()
    {
        $layout = $this->layoutService->loadLayout(1, Layout::STATUS_PUBLISHED);
        $this->layoutService->publishLayout($layout);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::deleteLayout
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     */
    public function testDeleteLayout()
    {
        $layout = $this->layoutService->loadLayout(1);
        $this->layoutService->deleteLayout($layout);

        $this->layoutService->loadLayout($layout->getId());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::newLayoutCreateStruct
     */
    public function testNewLayoutCreateStruct()
    {
        self::assertEquals(
            new LayoutCreateStruct(
                array(
                    'type' => '3_zones_a',
                    'name' => 'New layout',
                )
            ),
            $this->layoutService->newLayoutCreateStruct('3_zones_a', 'New layout')
        );
    }
}
