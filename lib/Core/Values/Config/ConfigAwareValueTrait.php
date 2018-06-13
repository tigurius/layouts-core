<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Core\Values\Config;

use Netgen\BlockManager\Exception\Core\ConfigException;

trait ConfigAwareValueTrait
{
    /**
     * @var \Netgen\BlockManager\API\Values\Config\Config[]
     */
    protected $configs = [];

    /**
     * Returns all available configs.
     *
     * @return \Netgen\BlockManager\API\Values\Config\Config[]
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * Returns the config with specified config key.
     *
     * @param string $configKey
     *
     * @throws \Netgen\BlockManager\Exception\Core\ConfigException If the config does not exist
     *
     * @return \Netgen\BlockManager\API\Values\Config\Config
     */
    public function getConfig($configKey)
    {
        if ($this->hasConfig($configKey)) {
            return $this->configs[$configKey];
        }

        throw ConfigException::noConfig($configKey);
    }

    /**
     * Returns if the config with specified config key exists.
     *
     * @param string $configKey
     *
     * @return bool
     */
    public function hasConfig($configKey)
    {
        return array_key_exists($configKey, $this->configs);
    }
}
