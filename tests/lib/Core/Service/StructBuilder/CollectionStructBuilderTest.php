<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Core\Service\StructBuilder;

use Netgen\BlockManager\API\Values\Collection\CollectionCreateStruct;
use Netgen\BlockManager\API\Values\Collection\CollectionUpdateStruct;
use Netgen\BlockManager\API\Values\Collection\Item;
use Netgen\BlockManager\API\Values\Collection\ItemCreateStruct;
use Netgen\BlockManager\API\Values\Collection\ItemUpdateStruct;
use Netgen\BlockManager\API\Values\Collection\QueryCreateStruct;
use Netgen\BlockManager\API\Values\Collection\QueryUpdateStruct;
use Netgen\BlockManager\API\Values\Config\ConfigStruct;
use Netgen\BlockManager\Collection\Item\ItemDefinition;
use Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder;
use Netgen\BlockManager\Core\Service\StructBuilder\ConfigStructBuilder;
use Netgen\BlockManager\Tests\Collection\Stubs\QueryType;
use Netgen\BlockManager\Tests\Core\Service\ServiceTestCase;

abstract class CollectionStructBuilderTest extends ServiceTestCase
{
    /**
     * @var \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder
     */
    private $structBuilder;

    public function setUp()
    {
        parent::setUp();

        $this->collectionService = $this->createCollectionService();

        $this->structBuilder = new CollectionStructBuilder(new ConfigStructBuilder());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::__construct
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newCollectionCreateStruct
     */
    public function testNewCollectionCreateStruct()
    {
        $this->assertEquals(
            new CollectionCreateStruct(
                [
                    'offset' => 0,
                    'limit' => null,
                    'queryCreateStruct' => new QueryCreateStruct(),
                ]
            ),
            $this->structBuilder->newCollectionCreateStruct(new QueryCreateStruct())
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newCollectionUpdateStruct
     */
    public function testNewCollectionUpdateStruct()
    {
        $this->assertEquals(
            new CollectionUpdateStruct(
                [
                    'offset' => null,
                    'limit' => null,
                ]
            ),
            $this->structBuilder->newCollectionUpdateStruct()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newCollectionUpdateStruct
     */
    public function testNewCollectionUpdateStructWithCollection()
    {
        $this->assertEquals(
            new CollectionUpdateStruct(
                [
                    'offset' => 4,
                    'limit' => 2,
                ]
            ),
            $this->structBuilder->newCollectionUpdateStruct(
                $this->collectionService->loadCollectionDraft(3)
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newCollectionUpdateStruct
     */
    public function testNewCollectionUpdateStructWithUnlimitedCollection()
    {
        $this->assertEquals(
            new CollectionUpdateStruct(
                [
                    'offset' => 0,
                    'limit' => 0,
                ]
            ),
            $this->structBuilder->newCollectionUpdateStruct(
                $this->collectionService->loadCollectionDraft(1)
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newItemCreateStruct
     */
    public function testNewItemCreateStruct()
    {
        $this->assertEquals(
            new ItemCreateStruct(
                [
                    'definition' => new ItemDefinition(),
                    'type' => Item::TYPE_OVERRIDE,
                    'value' => '42',
                ]
            ),
            $this->structBuilder->newItemCreateStruct(new ItemDefinition(), Item::TYPE_OVERRIDE, '42')
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newItemUpdateStruct
     */
    public function testNewItemUpdateStruct()
    {
        $itemUpdateStruct = new ItemUpdateStruct();

        $this->assertEquals(
            $itemUpdateStruct,
            $this->structBuilder->newItemUpdateStruct()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newItemUpdateStruct
     */
    public function testNewItemUpdateStructFromItem()
    {
        $item = $this->collectionService->loadItemDraft(1);

        $this->assertEquals(
            new ItemUpdateStruct(
                [
                    'configStructs' => [
                        'visibility' => new ConfigStruct(
                            [
                                'parameterValues' => [
                                    'visibility_status' => null,
                                    'visible_from' => null,
                                    'visible_to' => null,
                                ],
                            ]
                        ),
                    ],
                ]
            ),
            $this->structBuilder->newItemUpdateStruct($item)
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newQueryCreateStruct
     */
    public function testNewQueryCreateStruct()
    {
        $queryCreateStruct = $this->structBuilder->newQueryCreateStruct(
            new QueryType('my_query_type')
        );

        $this->assertEquals(
            new QueryCreateStruct(
                [
                    'queryType' => new QueryType('my_query_type'),
                    'parameterValues' => [
                        'param' => null,
                        'param2' => null,
                    ],
                ]
            ),
            $queryCreateStruct
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newQueryUpdateStruct
     */
    public function testNewQueryUpdateStruct()
    {
        $this->assertEquals(
            new QueryUpdateStruct(
                [
                    'locale' => 'en',
                ]
            ),
            $this->structBuilder->newQueryUpdateStruct('en')
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newQueryUpdateStruct
     */
    public function testNewQueryUpdateStructFromQuery()
    {
        $query = $this->collectionService->loadQueryDraft(4);

        $this->assertEquals(
            new QueryUpdateStruct(
                [
                    'locale' => 'en',
                    'parameterValues' => [
                        'param' => null,
                        'param2' => 0,
                    ],
                ]
            ),
            $this->structBuilder->newQueryUpdateStruct('en', $query)
        );
    }
}
