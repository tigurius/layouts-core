<?php

namespace Netgen\BlockManager\Tests\Core\Service\StructBuilder;

use Netgen\BlockManager\API\Values\Collection\Collection;
use Netgen\BlockManager\API\Values\Collection\CollectionCreateStruct;
use Netgen\BlockManager\API\Values\Collection\Item;
use Netgen\BlockManager\API\Values\Collection\ItemCreateStruct;
use Netgen\BlockManager\API\Values\Collection\QueryCreateStruct;
use Netgen\BlockManager\API\Values\Collection\QueryUpdateStruct;
use Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder;
use Netgen\BlockManager\Core\Service\Validator\CollectionValidator;
use Netgen\BlockManager\Tests\Collection\Stubs\QueryType;
use Netgen\BlockManager\Tests\Core\Service\ServiceTestCase;

abstract class CollectionStructBuilderTest extends ServiceTestCase
{
    /**
     * @var \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder
     */
    protected $structBuilder;

    /**
     * Sets up the tests.
     */
    public function setUp()
    {
        parent::setUp();

        $this->collectionService = $this->createCollectionService(
            $this->createMock(CollectionValidator::class)
        );

        $this->structBuilder = new CollectionStructBuilder();
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newCollectionCreateStruct
     */
    public function testNewCollectionCreateStruct()
    {
        $this->assertEquals(
            new CollectionCreateStruct(
                array(
                    'type' => Collection::TYPE_DYNAMIC,
                )
            ),
            $this->structBuilder->newCollectionCreateStruct(Collection::TYPE_DYNAMIC)
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newItemCreateStruct
     */
    public function testNewItemCreateStruct()
    {
        $this->assertEquals(
            new ItemCreateStruct(
                array(
                    'type' => Item::TYPE_OVERRIDE,
                    'valueId' => '42',
                    'valueType' => 'ezcontent',
                )
            ),
            $this->structBuilder->newItemCreateStruct(Item::TYPE_OVERRIDE, '42', 'ezcontent')
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\CollectionStructBuilder::newQueryCreateStruct
     */
    public function testNewQueryCreateStruct()
    {
        $queryCreateStruct = $this->structBuilder->newQueryCreateStruct(
            new QueryType('ezcontent_search')
        );

        $this->assertEquals(
            new QueryCreateStruct(
                array(
                    'queryType' => new QueryType('ezcontent_search'),
                    'parameterValues' => array(
                        'offset' => null,
                        'param' => null,
                    ),
                )
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
            new QueryUpdateStruct(),
            $this->structBuilder->newQueryUpdateStruct()
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
                array(
                    'parameterValues' => array(
                        'offset' => 0,
                        'param' => null,
                    ),
                )
            ),
            $this->structBuilder->newQueryUpdateStruct($query)
        );
    }
}
