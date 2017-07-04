<?php

namespace Netgen\BlockManager\Block;

use Netgen\BlockManager\API\Values\Block\Block;
use Netgen\BlockManager\Parameters\ParameterCollectionInterface;

interface BlockDefinitionInterface extends ParameterCollectionInterface
{
    /**
     * Returns block definition identifier.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Returns the array of dynamic parameters provided by this block definition.
     *
     * @param \Netgen\BlockManager\API\Values\Block\Block $block
     *
     * @return array
     */
    public function getDynamicParameters(Block $block);

    /**
     * Returns if the provided block is dependent on a context, i.e. current request.
     *
     * @param \Netgen\BlockManager\API\Values\Block\Block $block
     *
     * @return bool
     */
    public function isContextual(Block $block);

    /**
     * Returns the block definition configuration.
     *
     * @return \Netgen\BlockManager\Block\BlockDefinition\Configuration\Configuration
     */
    public function getConfig();

    /**
     * Returns the available config definitions.
     *
     * @return \Netgen\BlockManager\Config\ConfigDefinitionInterface[]
     */
    public function getConfigDefinitions();
}
