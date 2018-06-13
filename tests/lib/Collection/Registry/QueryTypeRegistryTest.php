<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Collection\Registry;

use ArrayIterator;
use Netgen\BlockManager\Collection\Registry\QueryTypeRegistry;
use Netgen\BlockManager\Tests\Collection\Stubs\QueryType;
use PHPUnit\Framework\TestCase;

final class QueryTypeRegistryTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Collection\QueryType\QueryTypeInterface
     */
    private $queryType1;

    /**
     * @var \Netgen\BlockManager\Collection\QueryType\QueryTypeInterface
     */
    private $queryType2;

    /**
     * @var \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry
     */
    private $registry;

    public function setUp()
    {
        $this->registry = new QueryTypeRegistry();

        $this->queryType1 = new QueryType('query_type1');
        $this->queryType2 = new QueryType('query_type2', [], null, false, false);

        $this->registry->addQueryType('query_type1', $this->queryType1);
        $this->registry->addQueryType('query_type2', $this->queryType2);
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::getQueryTypes
     */
    public function testGetQueryTypes()
    {
        $this->assertEquals(
            [
                'query_type1' => $this->queryType1,
                'query_type2' => $this->queryType2,
            ],
            $this->registry->getQueryTypes()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::getQueryTypes
     */
    public function testGetEnabledQueryTypes()
    {
        $this->assertEquals(
            [
                'query_type1' => $this->queryType1,
            ],
            $this->registry->getQueryTypes(true)
        );
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::addQueryType
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::getQueryTypes
     */
    public function testAddQueryType()
    {
        $this->assertEquals(['query_type1' => $this->queryType1, 'query_type2' => $this->queryType2], $this->registry->getQueryTypes());
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::getQueryType
     */
    public function testGetQueryType()
    {
        $this->assertEquals($this->queryType1, $this->registry->getQueryType('query_type1'));
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::getQueryType
     * @expectedException \Netgen\BlockManager\Exception\Collection\QueryTypeException
     * @expectedExceptionMessage Query type with "other_query_type" identifier does not exist.
     */
    public function testGetQueryTypeThrowsQueryTypeException()
    {
        $this->registry->getQueryType('other_query_type');
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::hasQueryType
     */
    public function testHasQueryType()
    {
        $this->assertTrue($this->registry->hasQueryType('query_type1'));
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::hasQueryType
     */
    public function testHasQueryTypeWithNoQueryType()
    {
        $this->assertFalse($this->registry->hasQueryType('other_query_type'));
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::getIterator
     */
    public function testGetIterator()
    {
        $this->assertInstanceOf(ArrayIterator::class, $this->registry->getIterator());

        $queryTypes = [];
        foreach ($this->registry as $identifier => $queryType) {
            $queryTypes[$identifier] = $queryType;
        }

        $this->assertEquals($this->registry->getQueryTypes(), $queryTypes);
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::count
     */
    public function testCount()
    {
        $this->assertCount(2, $this->registry);
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::offsetExists
     */
    public function testOffsetExists()
    {
        $this->assertArrayHasKey('query_type1', $this->registry);
        $this->assertArrayNotHasKey('other', $this->registry);
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::offsetGet
     */
    public function testOffsetGet()
    {
        $this->assertEquals($this->queryType1, $this->registry['query_type1']);
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::offsetSet
     * @expectedException \Netgen\BlockManager\Exception\RuntimeException
     * @expectedExceptionMessage Method call not supported.
     */
    public function testOffsetSet()
    {
        $this->registry['query_type1'] = $this->queryType1;
    }

    /**
     * @covers \Netgen\BlockManager\Collection\Registry\QueryTypeRegistry::offsetUnset
     * @expectedException \Netgen\BlockManager\Exception\RuntimeException
     * @expectedExceptionMessage Method call not supported.
     */
    public function testOffsetUnset()
    {
        unset($this->registry['query_type1']);
    }
}
