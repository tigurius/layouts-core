<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Core\Service\StructBuilder;

use Netgen\BlockManager\API\Values\Config\ConfigAwareStruct;
use Netgen\BlockManager\API\Values\Config\ConfigAwareValue;
use Netgen\BlockManager\API\Values\Config\ConfigStruct;

final class ConfigStructBuilder
{
    /**
     * Fills the provided config aware struct with config structs, according to the provided value.
     *
     * @param \Netgen\BlockManager\API\Values\Config\ConfigAwareValue $configAwareValue
     * @param \Netgen\BlockManager\API\Values\Config\ConfigAwareStruct $configAwareStruct
     */
    public function buildConfigUpdateStructs(ConfigAwareValue $configAwareValue, ConfigAwareStruct $configAwareStruct): void
    {
        foreach ($configAwareValue->getConfigs() as $configIdentifier => $config) {
            $configStruct = new ConfigStruct();
            $configStruct->fillParametersFromConfig($config);
            $configAwareStruct->setConfigStruct($configIdentifier, $configStruct);
        }
    }
}
