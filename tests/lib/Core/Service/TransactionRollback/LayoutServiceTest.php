<?php

namespace Netgen\BlockManager\Tests\Core\Service\TransactionRollback;

use Exception;
use Netgen\BlockManager\API\Values\Layout\LayoutCopyStruct;
use Netgen\BlockManager\API\Values\Layout\LayoutCreateStruct;
use Netgen\BlockManager\API\Values\Layout\LayoutUpdateStruct;
use Netgen\BlockManager\API\Values\Value;
use Netgen\BlockManager\Core\Values\Layout\Layout;
use Netgen\BlockManager\Core\Values\Layout\Zone;
use Netgen\BlockManager\Layout\Type\LayoutType;
use Netgen\BlockManager\Persistence\Values\Layout\Layout as PersistenceLayout;
use Netgen\BlockManager\Persistence\Values\Layout\Zone as PersistenceZone;

final class LayoutServiceTest extends ServiceTestCase
{
    /**
     * Sets up the tests.
     */
    public function setUp()
    {
        parent::setUp();

        $this->layoutService = $this->createLayoutService();
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::linkZone
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testLinkZone()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadLayout')
            ->will($this->returnValue(new PersistenceLayout(array('shared' => false))));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('loadZone')
            ->will($this->returnValue(new PersistenceZone(array('layoutId' => 1))));

        $this->layoutHandlerMock
            ->expects($this->at(2))
            ->method('loadLayout')
            ->will($this->returnValue(new PersistenceLayout(array('shared' => true))));

        $this->layoutHandlerMock
            ->expects($this->at(3))
            ->method('loadZone')
            ->will($this->returnValue(new PersistenceZone(array('layoutId' => 2))));

        $this->layoutHandlerMock
            ->expects($this->at(4))
            ->method('updateZone')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->linkZone(
            new Zone(array('status' => Value::STATUS_DRAFT)),
            new Zone(array('status' => Value::STATUS_PUBLISHED))
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::unlinkZone
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testUnlinkZone()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadZone')
            ->will($this->returnValue(new PersistenceZone()));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('updateZone')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->unlinkZone(new Zone(array('status' => Value::STATUS_DRAFT)));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::createLayout
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testCreateLayout()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('layoutNameExists')
            ->will($this->returnValue(false));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('createLayout')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->createLayout(
            new LayoutCreateStruct(
                array('layoutType' => new LayoutType())
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::addTranslation
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testAddTranslation()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadLayout')
            ->will(
                $this->returnValue(
                    new PersistenceLayout(
                        array(
                            'mainLocale' => 'en',
                            'availableLocales' => array('en'),
                        )
                    )
                )
            );

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('createLayoutTranslation')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->addTranslation(new Layout(array('status' => Value::STATUS_DRAFT)), 'hr', 'en');
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::removeTranslation
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testRemoveTranslation()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadLayout')
            ->will(
                $this->returnValue(
                    new PersistenceLayout(
                        array(
                            'mainLocale' => 'en',
                            'availableLocales' => array('en', 'hr'),
                        )
                    )
                )
            );

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('deleteLayoutTranslation')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->removeTranslation(new Layout(array('status' => Value::STATUS_DRAFT)), 'hr');
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::updateLayout
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testUpdateLayout()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadLayout')
            ->will($this->returnValue(new PersistenceLayout()));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('layoutNameExists')
            ->will($this->returnValue(false));

        $this->layoutHandlerMock
            ->expects($this->at(2))
            ->method('updateLayout')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->updateLayout(
            new Layout(array('status' => Value::STATUS_DRAFT)),
            new LayoutUpdateStruct(array('name' => 'New name'))
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::copyLayout
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testCopyLayout()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('layoutNameExists')
            ->will($this->returnValue(false));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('loadLayout')
            ->will($this->returnValue(new PersistenceLayout()));

        $this->layoutHandlerMock
            ->expects($this->at(2))
            ->method('copyLayout')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->copyLayout(new Layout(), new LayoutCopyStruct());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::copyLayout
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testChangeLayoutType()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadLayout')
            ->will($this->returnValue(new PersistenceLayout()));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('changeLayoutType')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->changeLayoutType(new Layout(), new LayoutType(array('identifier' => 'layout_1')));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::createDraft
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testCreateDraft()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadLayout')
            ->will($this->returnValue(new PersistenceLayout()));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('layoutExists')
            ->will($this->returnValue(false));

        $this->layoutHandlerMock
            ->expects($this->at(2))
            ->method('deleteLayout')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->createDraft(new Layout(array('status' => Value::STATUS_PUBLISHED)));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::discardDraft
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testDiscardDraft()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadLayout')
            ->will($this->returnValue(new PersistenceLayout()));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('deleteLayout')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->discardDraft(new Layout(array('status' => Value::STATUS_DRAFT)));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::publishLayout
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testPublishLayout()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadLayout')
            ->will($this->returnValue(new PersistenceLayout()));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('deleteLayout')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->publishLayout(new Layout(array('status' => Value::STATUS_DRAFT)));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\LayoutService::deleteLayout
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testDeleteLayout()
    {
        $this->layoutHandlerMock
            ->expects($this->at(0))
            ->method('loadLayout')
            ->will($this->returnValue(new PersistenceLayout()));

        $this->layoutHandlerMock
            ->expects($this->at(1))
            ->method('deleteLayout')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->layoutService->deleteLayout(new Layout());
    }
}
