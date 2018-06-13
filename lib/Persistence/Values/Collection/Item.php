<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Persistence\Values\Collection;

use Netgen\BlockManager\Persistence\Values\Value;

final class Item extends Value
{
    /**
     * @const int
     */
    const TYPE_MANUAL = 0;

    /**
     * @const int
     */
    const TYPE_OVERRIDE = 1;

    /**
     * Item ID.
     *
     * @var int|string
     */
    public $id;

    /**
     * ID of the collection to which this item belongs.
     *
     * @var int|string
     */
    public $collectionId;

    /**
     * Position of item within the collection.
     *
     * @var int
     */
    public $position;

    /**
     * Type of the item. One of self::TYPE_* flags.
     *
     * @var int
     */
    public $type;

    /**
     * Value from CMS this item wraps. This is usually the ID of the CMS entity.
     *
     * @var int|string
     */
    public $value;

    /**
     * Type of value from CMS this item wraps.
     *
     * @var string
     */
    public $valueType;

    /**
     * Item status. One of self::STATUS_* flags.
     *
     * @var int
     */
    public $status;

    /**
     * Item configuration.
     *
     * @var array
     */
    public $config;
}
