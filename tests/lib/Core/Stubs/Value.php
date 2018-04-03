<?php

namespace Netgen\BlockManager\Tests\Core\Stubs;

use Netgen\BlockManager\API\Values\Value as APIValue;
use Netgen\BlockManager\Value as BaseValue;

final class Value extends BaseValue implements APIValue
{
    /**
     * @var int
     */
    public $status;

    /**
     * @var mixed
     */
    public $someProperty;

    /**
     * @var mixed
     */
    public $someOtherProperty;

    public function getStatus()
    {
        return $this->status;
    }

    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }
}
