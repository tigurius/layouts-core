<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Persistence\Values\LayoutResolver;

use Netgen\BlockManager\Persistence\Values\Value;

final class Rule extends Value
{
    /**
     * Rule ID.
     *
     * @var int|string
     */
    public $id;

    /**
     * Rule status. One of self::STATUS_* flags.
     *
     * @var int
     */
    public $status;

    /**
     * ID of the layout mapped to this rule. Can be null if there's no mapped layout.
     *
     * @var int|string|null
     */
    public $layoutId;

    /**
     * A flag indicating if the rule is enabled or not.
     *
     * @var bool
     */
    public $enabled;

    /**
     * Rule priority.
     *
     * @var int
     */
    public $priority;

    /**
     * Human readable comment of the rule.
     *
     * @var string|null
     */
    public $comment;
}
