<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Collection\Registry;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Netgen\BlockManager\Collection\QueryType\QueryTypeInterface;

interface QueryTypeRegistryInterface extends IteratorAggregate, Countable, ArrayAccess
{
    /**
     * Adds a query type to registry.
     *
     * @param string $type
     * @param \Netgen\BlockManager\Collection\QueryType\QueryTypeInterface $queryType
     */
    public function addQueryType($type, QueryTypeInterface $queryType);

    /**
     * Returns if registry has a query type.
     *
     * @param string $type
     *
     * @return bool
     */
    public function hasQueryType($type);

    /**
     * Returns a query type with provided identifier.
     *
     * @param string $type
     *
     * @throws \Netgen\BlockManager\Exception\Collection\QueryTypeException If query type does not exist
     *
     * @return \Netgen\BlockManager\Collection\QueryType\QueryTypeInterface
     */
    public function getQueryType($type);

    /**
     * Returns all query types.
     *
     * @param bool $onlyEnabled
     *
     * @return \Netgen\BlockManager\Collection\QueryType\QueryTypeInterface[]
     */
    public function getQueryTypes($onlyEnabled = false);
}
