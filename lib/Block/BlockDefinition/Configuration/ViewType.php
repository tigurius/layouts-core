<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Block\BlockDefinition\Configuration;

use Netgen\BlockManager\Exception\Block\BlockDefinitionException;
use Netgen\BlockManager\Value;

final class ViewType extends Value
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Netgen\BlockManager\Block\BlockDefinition\Configuration\ItemViewType[]
     */
    protected $itemViewTypes = [];

    /**
     * @var array|null
     */
    protected $validParameters;

    /**
     * Returns the view type identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Returns the view type name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the item view types.
     *
     * @return \Netgen\BlockManager\Block\BlockDefinition\Configuration\ItemViewType[]
     */
    public function getItemViewTypes()
    {
        return $this->itemViewTypes;
    }

    /**
     * Returns the item view type identifiers.
     *
     * @return string[]
     */
    public function getItemViewTypeIdentifiers()
    {
        return array_keys($this->itemViewTypes);
    }

    /**
     * Returns the valid parameters.
     *
     * If null is returned, all parameters are valid.
     *
     * @return array|null
     */
    public function getValidParameters()
    {
        return $this->validParameters;
    }

    /**
     * Returns if the view type has an item view type with provided identifier.
     *
     * @param string $itemViewType
     *
     * @return bool
     */
    public function hasItemViewType($itemViewType)
    {
        return isset($this->itemViewTypes[$itemViewType]);
    }

    /**
     * Returns the item view type with provided identifier.
     *
     * @param string $itemViewType
     *
     * @throws \Netgen\BlockManager\Exception\Block\BlockDefinitionException If item view type does not exist
     *
     * @return \Netgen\BlockManager\Block\BlockDefinition\Configuration\ItemViewType
     */
    public function getItemViewType($itemViewType)
    {
        if (!$this->hasItemViewType($itemViewType)) {
            throw BlockDefinitionException::noItemViewType($this->identifier, $itemViewType);
        }

        return $this->itemViewTypes[$itemViewType];
    }
}
