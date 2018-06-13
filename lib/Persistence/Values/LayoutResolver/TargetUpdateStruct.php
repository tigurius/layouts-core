<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Persistence\Values\LayoutResolver;

use Netgen\BlockManager\Value;

final class TargetUpdateStruct extends Value
{
    /**
     * New value of the target.
     *
     * @var int|string
     */
    public $value;
}
