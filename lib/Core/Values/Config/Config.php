<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Core\Values\Config;

use Netgen\BlockManager\API\Values\Config\Config as APIConfig;
use Netgen\BlockManager\Core\Values\ParameterBasedValueTrait;
use Netgen\BlockManager\Value;

final class Config extends Value implements APIConfig
{
    use ParameterBasedValueTrait;

    /**
     * @var string
     */
    protected $configKey;

    /**
     * @var \Netgen\BlockManager\Config\ConfigDefinitionInterface
     */
    protected $definition;

    public function getConfigKey()
    {
        return $this->configKey;
    }

    public function getDefinition()
    {
        return $this->definition;
    }
}
