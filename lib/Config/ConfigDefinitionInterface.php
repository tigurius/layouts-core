<?php

namespace Netgen\BlockManager\Config;

use Netgen\BlockManager\Parameters\ParameterDefinitionCollectionInterface;

/**
 * Config definition represents an abstract concept reusable by all
 * entities which allows specification and validation of entity configuration
 * stored in the database. For example, blocks use these definitions
 * to specify how the block HTTP cache config is stored and validated.
 */
interface ConfigDefinitionInterface extends ParameterDefinitionCollectionInterface
{
    /**
     * Returns the config key for the definition.
     *
     * @return string
     */
    public function getConfigKey();
}
