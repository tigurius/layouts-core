<?php

namespace Netgen\BlockManager\Tests\Persistence\Doctrine\Mapper;

use Netgen\BlockManager\Persistence\Doctrine\Mapper\BlockMapper;
use Netgen\BlockManager\Persistence\Values\Page\Block;
use Netgen\BlockManager\Persistence\Values\Page\CollectionReference;
use Netgen\BlockManager\Persistence\Values\Value;
use PHPUnit\Framework\TestCase;

class BlockMapperTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Persistence\Doctrine\Mapper\BlockMapper
     */
    protected $mapper;

    public function setUp()
    {
        $this->mapper = new BlockMapper();
    }

    /**
     * @covers \Netgen\BlockManager\Persistence\Doctrine\Mapper\BlockMapper::mapBlocks
     */
    public function testMapBlocks()
    {
        $data = array(
            array(
                'id' => 42,
                'layout_id' => 24,
                'depth' => 1,
                'path' => '/22/42/',
                'parent_id' => 22,
                'placeholder' => 'root',
                'position' => 4,
                'definition_identifier' => 'text',
                'placeholder_parameters' => '{"some": "value"}',
                'parameters' => '{"param1": "param2"}',
                'view_type' => 'default',
                'item_view_type' => 'standard',
                'name' => 'My block',
                'status' => Value::STATUS_PUBLISHED,
            ),
            array(
                'id' => 84,
                'layout_id' => 48,
                'depth' => 1,
                'path' => '/23/84/',
                'parent_id' => 23,
                'placeholder' => 'root',
                'position' => 3,
                'definition_identifier' => 'title',
                'placeholder_parameters' => '{"other": "value"}',
                'parameters' => '{"param1": 42}',
                'view_type' => 'small',
                'item_view_type' => 'standard',
                'name' => 'My other block',
                'status' => Value::STATUS_PUBLISHED,
            ),
        );

        $expectedData = array(
            new Block(
                array(
                    'id' => 42,
                    'layoutId' => 24,
                    'depth' => 1,
                    'path' => '/22/42/',
                    'parentId' => 22,
                    'placeholder' => 'root',
                    'position' => 4,
                    'definitionIdentifier' => 'text',
                    'viewType' => 'default',
                    'itemViewType' => 'standard',
                    'name' => 'My block',
                    'status' => Value::STATUS_PUBLISHED,
                    'placeholderParameters' => array(
                        'some' => 'value',
                    ),
                    'parameters' => array(
                        'param1' => 'param2',
                    ),
                )
            ),
            new Block(
                array(
                    'id' => 84,
                    'layoutId' => 48,
                    'depth' => 1,
                    'path' => '/23/84/',
                    'parentId' => 23,
                    'placeholder' => 'root',
                    'position' => 3,
                    'definitionIdentifier' => 'title',
                    'viewType' => 'small',
                    'itemViewType' => 'standard',
                    'name' => 'My other block',
                    'status' => Value::STATUS_PUBLISHED,
                    'placeholderParameters' => array(
                        'other' => 'value',
                    ),
                    'parameters' => array(
                        'param1' => 42,
                    ),
                )
            ),
        );

        $this->assertEquals($expectedData, $this->mapper->mapBlocks($data));
    }

    /**
     * @covers \Netgen\BlockManager\Persistence\Doctrine\Mapper\BlockMapper::mapCollectionReferences
     */
    public function testMapCollectionReferences()
    {
        $data = array(
            array(
                'block_id' => 1,
                'block_status' => Value::STATUS_PUBLISHED,
                'collection_id' => 42,
                'collection_status' => Value::STATUS_PUBLISHED,
                'identifier' => 'default',
                'start' => 5,
                'length' => 10,
            ),
            array(
                'block_id' => 2,
                'block_status' => Value::STATUS_PUBLISHED,
                'collection_id' => 43,
                'collection_status' => Value::STATUS_PUBLISHED,
                'identifier' => 'featured',
                'start' => 10,
                'length' => 15,
            ),
        );

        $expectedData = array(
            new CollectionReference(
                array(
                    'blockId' => 1,
                    'blockStatus' => Value::STATUS_PUBLISHED,
                    'collectionId' => 42,
                    'collectionStatus' => Value::STATUS_PUBLISHED,
                    'identifier' => 'default',
                    'offset' => 5,
                    'limit' => 10,
                )
            ),
            new CollectionReference(
                array(
                    'blockId' => 2,
                    'blockStatus' => Value::STATUS_PUBLISHED,
                    'collectionId' => 43,
                    'collectionStatus' => Value::STATUS_PUBLISHED,
                    'identifier' => 'featured',
                    'offset' => 10,
                    'limit' => 15,
                )
            ),
        );

        $this->assertEquals($expectedData, $this->mapper->mapCollectionReferences($data));
    }
}
