<?php

namespace Netgen\BlockManager\Persistence\Doctrine\QueryHandler;

use Doctrine\DBAL\Types\Type;
use Netgen\BlockManager\Persistence\Values\Collection\CollectionCreateStruct;
use Netgen\BlockManager\Persistence\Values\Collection\CollectionUpdateStruct;
use Netgen\BlockManager\Persistence\Values\Collection\ItemCreateStruct;
use Netgen\BlockManager\Persistence\Values\Collection\QueryCreateStruct;
use Netgen\BlockManager\Persistence\Values\Collection\QueryUpdateStruct;

class CollectionQueryHandler extends QueryHandler
{
    /**
     * Loads all collection data for collection with specified ID.
     *
     * @param int|string $collectionId
     * @param int $status
     *
     * @return array
     */
    public function loadCollectionData($collectionId, $status = null)
    {
        $query = $this->getCollectionSelectQuery();
        $query->where(
            $query->expr()->eq('id', ':id')
        )
        ->setParameter('id', $collectionId, Type::INTEGER);

        if ($status !== null) {
            $this->applyStatusCondition($query, $status);
            $query->addOrderBy('status', 'ASC');
        }

        return $query->execute()->fetchAll();
    }

    /**
     * Loads all data for an item.
     *
     * @param int|string $itemId
     * @param int $status
     *
     * @return array
     */
    public function loadItemData($itemId, $status = null)
    {
        $query = $this->getItemSelectQuery();
        $query->where(
            $query->expr()->eq('id', ':id')
        )
        ->setParameter('id', $itemId, Type::INTEGER);

        if ($status !== null) {
            $this->applyStatusCondition($query, $status);
        }

        return $query->execute()->fetchAll();
    }

    /**
     * Loads all data for shared collections.
     *
     * @param int $status
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function loadSharedCollectionsData($status, $offset = 0, $limit = null)
    {
        $query = $this->getCollectionSelectQuery();
        $query->where(
            $query->expr()->eq('shared', ':shared')
        )
        ->setParameter('shared', true, Type::BOOLEAN);

        $this->applyStatusCondition($query, $status);
        $this->applyOffsetAndLimit($query, $offset, $limit);

        return $query->execute()->fetchAll();
    }

    /**
     * Loads all data for a query.
     *
     * @param int|string $queryId
     * @param int $status
     *
     * @return array
     */
    public function loadQueryData($queryId, $status = null)
    {
        $query = $this->getQuerySelectQuery();
        $query->where(
            $query->expr()->eq('id', ':id')
        )
        ->setParameter('id', $queryId, Type::INTEGER);

        if ($status !== null) {
            $this->applyStatusCondition($query, $status);
        }

        return $query->execute()->fetchAll();
    }

    /**
     * Loads all data for items that belong to collection with specified ID.
     *
     * @param int|string $collectionId
     * @param int $status
     *
     * @return array
     */
    public function loadCollectionItemsData($collectionId, $status = null)
    {
        $query = $this->getItemSelectQuery();
        $query->where(
            $query->expr()->eq('collection_id', ':collection_id')
        )
        ->setParameter('collection_id', $collectionId, Type::INTEGER);

        if ($status !== null) {
            $this->applyStatusCondition($query, $status);
            $query->addOrderBy('status', 'ASC');
        }

        $query->addOrderBy('position', 'ASC');

        return $query->execute()->fetchAll();
    }

    /**
     * Loads all data for queries that belong to collection with specified ID.
     *
     * @param int|string $collectionId
     * @param int $status
     *
     * @return array
     */
    public function loadCollectionQueriesData($collectionId, $status = null)
    {
        $query = $this->getQuerySelectQuery();
        $query->where(
            $query->expr()->eq('collection_id', ':collection_id')
        )
        ->setParameter('collection_id', $collectionId, Type::INTEGER);

        if ($status !== null) {
            $this->applyStatusCondition($query, $status);
            $query->addOrderBy('status', 'ASC');
        }

        $query->addOrderBy('position', 'ASC');

        return $query->execute()->fetchAll();
    }

    /**
     * Returns if the collection exists.
     *
     * @param int|string $collectionId
     * @param int $status
     *
     * @return bool
     */
    public function collectionExists($collectionId, $status)
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('count(*) AS count')
            ->from('ngbm_collection')
            ->where(
                $query->expr()->eq('id', ':id')
            )
            ->setParameter('id', $collectionId, Type::INTEGER);

        $this->applyStatusCondition($query, $status);

        $data = $query->execute()->fetchAll();

        return isset($data[0]['count']) && $data[0]['count'] > 0;
    }

    /**
     * Returns if the collection name exists.
     *
     * @param string $name
     * @param int|string $excludedCollectionId
     * @param int $status
     *
     * @return bool
     */
    public function collectionNameExists($name, $excludedCollectionId = null, $status = null)
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('count(*) AS count')
            ->from('ngbm_collection')
            ->where(
                $query->expr()->eq('name', ':name')
            )
            ->setParameter('name', trim($name), Type::STRING);

        if ($excludedCollectionId !== null) {
            $query->andWhere($query->expr()->neq('id', ':collection_id'))
                ->setParameter('collection_id', $excludedCollectionId, Type::INTEGER);
        }

        if ($status !== null) {
            $this->applyStatusCondition($query, $status);
        }

        $data = $query->execute()->fetchAll();

        return isset($data[0]['count']) && $data[0]['count'] > 0;
    }

    /**
     * Creates a collection.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Collection\CollectionCreateStruct $collectionCreateStruct
     * @param int|string $collectionId
     *
     * @return int
     */
    public function createCollection(CollectionCreateStruct $collectionCreateStruct, $collectionId = null)
    {
        $query = $this->connection->createQueryBuilder()
            ->insert('ngbm_collection')
            ->values(
                array(
                    'id' => ':id',
                    'status' => ':status',
                    'type' => ':type',
                    'shared' => ':shared',
                    'name' => ':name',
                )
            )
            ->setValue(
                'id',
                $collectionId !== null ? (int) $collectionId : $this->connectionHelper->getAutoIncrementValue('ngbm_collection')
            )
            ->setParameter('status', $collectionCreateStruct->status, Type::INTEGER)
            ->setParameter('type', $collectionCreateStruct->type, Type::INTEGER)
            ->setParameter('shared', $collectionCreateStruct->shared, Type::BOOLEAN)
            ->setParameter('name', $collectionCreateStruct->name, Type::STRING);

        $query->execute();

        return (int) $this->connectionHelper->lastInsertId('ngbm_collection');
    }

    /**
     * Updates a collection.
     *
     * @param int|string $collectionId
     * @param int $status
     * @param \Netgen\BlockManager\Persistence\Values\Collection\CollectionUpdateStruct $collectionUpdateStruct
     */
    public function updateCollection($collectionId, $status, CollectionUpdateStruct $collectionUpdateStruct)
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->update('ngbm_collection')
            ->set('type', ':type')
            ->set('name', ':name')
            ->where(
                $query->expr()->eq('id', ':id')
            )
            ->setParameter('id', $collectionId, Type::INTEGER)
            ->setParameter('type', $collectionUpdateStruct->type, Type::INTEGER)
            ->setParameter('name', $collectionUpdateStruct->name, Type::STRING);

        $this->applyStatusCondition($query, $status);

        $query->execute();
    }

    /**
     * Deletes a collection.
     *
     * @param int|string $collectionId
     * @param int $status
     */
    public function deleteCollection($collectionId, $status = null)
    {
        // Delete all connections between blocks and collections

        $query = $this->connection->createQueryBuilder();
        $query
            ->delete('ngbm_block_collection')
            ->where(
                $query->expr()->eq('collection_id', ':collection_id')
            )
            ->setParameter('collection_id', $collectionId, Type::INTEGER);

        if ($status !== null) {
            $this->applyStatusCondition($query, $status, 'collection_status');
        }

        $query->execute();

        // Then delete the collection itself

        $query = $this->connection->createQueryBuilder();
        $query->delete('ngbm_collection')
            ->where(
                $query->expr()->eq('id', ':id')
            )
            ->setParameter('id', $collectionId, Type::INTEGER);

        if ($status !== null) {
            $this->applyStatusCondition($query, $status);
        }

        $query->execute();
    }

    /**
     * Adds an item.
     *
     * @param int|string $collectionId
     * @param int $status
     * @param \Netgen\BlockManager\Persistence\Values\Collection\ItemCreateStruct $itemCreateStruct
     * @param int|string $itemId
     *
     * @return int
     */
    public function addItem($collectionId, $status, ItemCreateStruct $itemCreateStruct, $itemId = null)
    {
        $query = $this->connection->createQueryBuilder()
            ->insert('ngbm_collection_item')
            ->values(
                array(
                    'id' => ':id',
                    'status' => ':status',
                    'collection_id' => ':collection_id',
                    'position' => ':position',
                    'type' => ':type',
                    'value_id' => ':value_id',
                    'value_type' => ':value_type',
                )
            )
            ->setValue(
                'id',
                $itemId !== null ? (int) $itemId : $this->connectionHelper->getAutoIncrementValue('ngbm_collection_item')
            )
            ->setParameter('status', $status, Type::INTEGER)
            ->setParameter('collection_id', $collectionId, Type::INTEGER)
            ->setParameter('position', $itemCreateStruct->position, Type::INTEGER)
            ->setParameter('type', $itemCreateStruct->type, Type::INTEGER)
            ->setParameter('value_id', $itemCreateStruct->valueId, Type::STRING)
            ->setParameter('value_type', $itemCreateStruct->valueType, Type::STRING);

        $query->execute();

        return (int) $this->connectionHelper->lastInsertId('ngbm_collection_item');
    }

    /**
     * Moves an item.
     *
     * @param int|string $itemId
     * @param int $status
     * @param int $position
     */
    public function moveItem($itemId, $status, $position)
    {
        $query = $this->connection->createQueryBuilder();

        $query
            ->update('ngbm_collection_item')
            ->set('position', ':position')
            ->where(
                $query->expr()->eq('id', ':id')
            )
            ->setParameter('id', $itemId, Type::INTEGER)
            ->setParameter('position', $position, Type::INTEGER);

        $this->applyStatusCondition($query, $status);

        $query->execute();
    }

    /**
     * Deletes an item.
     *
     * @param int|string $itemId
     * @param int $status
     */
    public function deleteItem($itemId, $status)
    {
        $query = $this->connection->createQueryBuilder();

        $query->delete('ngbm_collection_item')
            ->where(
                $query->expr()->eq('id', ':id')
            )
            ->setParameter('id', $itemId, Type::INTEGER);

        $this->applyStatusCondition($query, $status);

        $query->execute();
    }

    /**
     * Deletes all collection items.
     *
     * @param int|string $collectionId
     * @param int $status
     */
    public function deleteCollectionItems($collectionId, $status = null)
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->delete('ngbm_collection_item')
            ->where(
                $query->expr()->eq('collection_id', ':collection_id')
            )
            ->setParameter('collection_id', $collectionId, Type::INTEGER);

        if ($status !== null) {
            $this->applyStatusCondition($query, $status);
        }

        $query->execute();
    }

    /**
     * Returns if the query with specified identifier exists.
     *
     * @param int|string $collectionId
     * @param int $status
     * @param string $identifier
     *
     * @return bool
     */
    public function queryExists($collectionId, $status, $identifier)
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('count(*) AS count')
            ->from('ngbm_collection_query')
            ->where(
                $query->expr()->andX(
                    $query->expr()->eq('collection_id', ':collection_id'),
                    $query->expr()->eq('identifier', ':identifier')
                )
            )
            ->setParameter('collection_id', $collectionId, Type::INTEGER)
            ->setParameter('identifier', $identifier, Type::STRING);

        $this->applyStatusCondition($query, $status);

        $data = $query->execute()->fetchAll();

        return isset($data[0]['count']) && $data[0]['count'] > 0;
    }

    /**
     * Adds a query.
     *
     * @param int|string $collectionId
     * @param int $status
     * @param \Netgen\BlockManager\Persistence\Values\Collection\QueryCreateStruct $queryCreateStruct
     * @param int|string $queryId
     *
     * @return int
     */
    public function addQuery($collectionId, $status, QueryCreateStruct $queryCreateStruct, $queryId = null)
    {
        $query = $this->connection->createQueryBuilder()
            ->insert('ngbm_collection_query')
            ->values(
                array(
                    'id' => ':id',
                    'status' => ':status',
                    'collection_id' => ':collection_id',
                    'position' => ':position',
                    'identifier' => ':identifier',
                    'type' => ':type',
                    'parameters' => ':parameters',
                )
            )
            ->setValue(
                'id',
                $queryId !== null ? (int) $queryId : $this->connectionHelper->getAutoIncrementValue('ngbm_collection_query')
            )
            ->setParameter('status', $status, Type::INTEGER)
            ->setParameter('collection_id', $collectionId, Type::INTEGER)
            ->setParameter('position', $queryCreateStruct->position, Type::INTEGER)
            ->setParameter('identifier', $queryCreateStruct->identifier, Type::STRING)
            ->setParameter('type', $queryCreateStruct->type, Type::STRING)
            ->setParameter('parameters', $queryCreateStruct->parameters, Type::JSON_ARRAY);

        $query->execute();

        return (int) $this->connectionHelper->lastInsertId('ngbm_collection_query');
    }

    /**
     * Updates a query.
     *
     * @param int|string $queryId
     * @param int $status
     * @param \Netgen\BlockManager\Persistence\Values\Collection\QueryUpdateStruct $queryUpdateStruct
     */
    public function updateQuery($queryId, $status, QueryUpdateStruct $queryUpdateStruct)
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->update('ngbm_collection_query')
            ->set('identifier', ':identifier')
            ->set('parameters', ':parameters')
            ->where(
                $query->expr()->eq('id', ':id')
            )
            ->setParameter('id', $queryId, Type::INTEGER)
            ->setParameter('identifier', $queryUpdateStruct->identifier, Type::STRING)
            ->setParameter('parameters', $queryUpdateStruct->parameters, Type::JSON_ARRAY);

        $this->applyStatusCondition($query, $status);

        $query->execute();
    }

    /**
     * Moves a query.
     *
     * @param int|string $queryId
     * @param int $status
     * @param int $position
     */
    public function moveQuery($queryId, $status, $position)
    {
        $query = $this->connection->createQueryBuilder();

        $query
            ->update('ngbm_collection_query')
            ->set('position', ':position')
            ->where(
                $query->expr()->eq('id', ':id')
            )
            ->setParameter('id', $queryId, Type::INTEGER)
            ->setParameter('position', $position, Type::INTEGER);

        $this->applyStatusCondition($query, $status);

        $query->execute();
    }

    /**
     * Deletes a query.
     *
     * @param int|string $queryId
     * @param int $status
     */
    public function deleteQuery($queryId, $status)
    {
        $query = $this->connection->createQueryBuilder();

        $query->delete('ngbm_collection_query')
            ->where(
                $query->expr()->eq('id', ':id')
            )
            ->setParameter('id', $queryId, Type::INTEGER);

        $this->applyStatusCondition($query, $status);

        $query->execute();
    }

    /**
     * Deletes all collection queries.
     *
     * @param int|string $collectionId
     * @param int $status
     */
    public function deleteCollectionQueries($collectionId, $status = null)
    {
        $query = $this->connection->createQueryBuilder();

        $query->delete('ngbm_collection_query')
            ->where(
                $query->expr()->eq('collection_id', ':collection_id')
            )
            ->setParameter('collection_id', $collectionId, Type::INTEGER);

        if ($status !== null) {
            $this->applyStatusCondition($query, $status);
        }

        $query->execute();
    }

    /**
     * Builds and returns a collection database SELECT query.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    protected function getCollectionSelectQuery()
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('DISTINCT ngbm_collection.*')
            ->from('ngbm_collection');

        return $query;
    }

    /**
     * Builds and returns an item database SELECT query.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    protected function getItemSelectQuery()
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('DISTINCT ngbm_collection_item.*')
            ->from('ngbm_collection_item');

        return $query;
    }

    /**
     * Builds and returns a query database SELECT query.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    protected function getQuerySelectQuery()
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('DISTINCT ngbm_collection_query.*')
            ->from('ngbm_collection_query');

        return $query;
    }
}
