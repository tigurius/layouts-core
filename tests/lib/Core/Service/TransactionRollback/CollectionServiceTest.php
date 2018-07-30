<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Core\Service\TransactionRollback;

use Exception;
use Netgen\BlockManager\API\Values\Collection\ItemCreateStruct;
use Netgen\BlockManager\API\Values\Collection\ItemUpdateStruct;
use Netgen\BlockManager\API\Values\Collection\QueryUpdateStruct;
use Netgen\BlockManager\API\Values\Value;
use Netgen\BlockManager\Collection\Item\ItemDefinition;
use Netgen\BlockManager\Core\Values\Collection\Collection;
use Netgen\BlockManager\Core\Values\Collection\Item;
use Netgen\BlockManager\Core\Values\Collection\Query;
use Netgen\BlockManager\Persistence\Values\Collection\Collection as PersistenceCollection;
use Netgen\BlockManager\Persistence\Values\Collection\Item as PersistenceItem;
use Netgen\BlockManager\Persistence\Values\Collection\Query as PersistenceQuery;
use Netgen\BlockManager\Tests\Collection\Stubs\QueryType;

final class CollectionServiceTest extends ServiceTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->collectionService = $this->createCollectionService();
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::changeCollectionType
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testChangeCollectionType(): void
    {
        $this->collectionHandlerMock
            ->expects($this->at(0))
            ->method('loadCollection')
            ->will(
                $this->returnValue(
                    new PersistenceCollection()
                )
            );

        $this->collectionHandlerMock
            ->expects($this->at(1))
            ->method('deleteCollectionQuery')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->changeCollectionType(
            Collection::fromArray(['status' => Value::STATUS_DRAFT, 'query' => new Query()]),
            Collection::TYPE_MANUAL
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::addItem
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testAddItem(): void
    {
        $this->collectionHandlerMock
            ->expects($this->at(0))
            ->method('loadCollection')
            ->will(
                $this->returnValue(
                    new PersistenceCollection()
                )
            );

        $this->collectionHandlerMock
            ->expects($this->at(1))
            ->method('addItem')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $itemCreateStruct = new ItemCreateStruct();
        $itemCreateStruct->definition = ItemDefinition::fromArray(['valueType' => 'value_type']);

        $this->collectionService->addItem(
            Collection::fromArray(['status' => Value::STATUS_DRAFT]),
            $itemCreateStruct
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::addItem
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testUpdateItem(): void
    {
        $this->collectionHandlerMock
            ->expects($this->at(0))
            ->method('loadItem')
            ->will(
                $this->returnValue(
                    PersistenceItem::fromArray(['config' => []])
                )
            );

        $this->collectionHandlerMock
            ->expects($this->at(1))
            ->method('updateItem')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->updateItem(
            Item::fromArray(['status' => Value::STATUS_DRAFT, 'definition' => new ItemDefinition()]),
            new ItemUpdateStruct()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::moveItem
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testMoveItem(): void
    {
        $this->collectionHandlerMock
            ->expects($this->at(0))
            ->method('loadItem')
            ->will($this->returnValue(new PersistenceItem()));

        $this->collectionHandlerMock
            ->expects($this->at(1))
            ->method('moveItem')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->moveItem(Item::fromArray(['status' => Value::STATUS_DRAFT]), 0);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::deleteItem
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testDeleteItem(): void
    {
        $this->collectionHandlerMock
            ->expects($this->at(0))
            ->method('loadItem')
            ->will($this->returnValue(new PersistenceItem()));

        $this->collectionHandlerMock
            ->expects($this->at(1))
            ->method('deleteItem')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->deleteItem(Item::fromArray(['status' => Value::STATUS_DRAFT]));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::deleteItem
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testDeleteItems(): void
    {
        $this->collectionHandlerMock
            ->expects($this->at(0))
            ->method('loadCollection')
            ->will($this->returnValue(new PersistenceCollection()));

        $this->collectionHandlerMock
            ->expects($this->at(1))
            ->method('deleteItems')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->deleteItems(Collection::fromArray(['status' => Value::STATUS_DRAFT]));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::updateQuery
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testUpdateQuery(): void
    {
        $persistenceQuery = PersistenceQuery::fromArray(
            [
                'mainLocale' => 'en',
                'availableLocales' => ['en'],
                'parameters' => ['en' => []],
            ]
        );

        $this->collectionHandlerMock
            ->expects($this->at(0))
            ->method('loadQuery')
            ->will($this->returnValue($persistenceQuery));

        $this->collectionHandlerMock
            ->expects($this->at(1))
            ->method('updateQueryTranslation')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $struct = new QueryUpdateStruct();
        $struct->locale = 'en';

        $this->collectionService->updateQuery(
            Query::fromArray(
                [
                    'status' => Value::STATUS_DRAFT,
                    'queryType' => new QueryType('type'),
                ]
            ),
            $struct
        );
    }
}
