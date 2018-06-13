<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Block\BlockDefinition\Handler;

use Netgen\BlockManager\API\Values\Block\Block;
use Netgen\BlockManager\Block\DynamicParameters;
use Netgen\BlockManager\Parameters\ParameterBuilderInterface;

interface PluginInterface
{
    /**
     * Use this group in the parameters you wish to show
     * in the Content part of the block edit interface.
     */
    const GROUP_CONTENT = 'content';

    /**
     * Use this group in the parameters you wish to show
     * in the Design part of the block edit interface.
     */
    const GROUP_DESIGN = 'design';

    /**
     * Returns the fully qualified class name of the block definition handler
     * which this plugin extends. If you wish to extend every existing handler,
     * return the FQCN of the block handler interface. You can also return
     * the list of FQCNs to make the plugin work on a set of blocks.
     *
     * @return string|string[]
     */
    public static function getExtendedHandler();

    /**
     * Builds the parameters by using provided parameter builder.
     *
     * @param \Netgen\BlockManager\Parameters\ParameterBuilderInterface $builder
     */
    public function buildParameters(ParameterBuilderInterface $builder);

    /**
     * Adds the dynamic parameters to the $params object for the provided block.
     *
     * @param \Netgen\BlockManager\Block\DynamicParameters $params
     * @param \Netgen\BlockManager\API\Values\Block\Block $block
     */
    public function getDynamicParameters(DynamicParameters $params, Block $block);
}
