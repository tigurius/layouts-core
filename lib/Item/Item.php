<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Item;

use Netgen\BlockManager\Value;

final class Item extends Value implements ItemInterface
{
    /**
     * @var int|string
     */
    protected $value;

    /**
     * @var int|string
     */
    protected $remoteId;

    /**
     * @var string
     */
    protected $valueType;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isVisible;

    /**
     * @var mixed
     */
    protected $object;

    public function getValue()
    {
        return $this->value;
    }

    public function getRemoteId()
    {
        return $this->remoteId;
    }

    public function getValueType()
    {
        return $this->valueType;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isVisible()
    {
        return $this->isVisible;
    }

    public function getObject()
    {
        return $this->object;
    }
}
