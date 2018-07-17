<?php

declare(strict_types=1);

namespace Netgen\BlockManager\API\Values\Block;

use Netgen\BlockManager\API\Values\Collection\CollectionCreateStruct;
use Netgen\BlockManager\API\Values\Config\ConfigAwareStruct;
use Netgen\BlockManager\API\Values\Config\ConfigAwareStructTrait;
use Netgen\BlockManager\API\Values\ParameterStruct;
use Netgen\BlockManager\API\Values\ParameterStructTrait;
use Netgen\BlockManager\Block\BlockDefinitionInterface;
use Netgen\BlockManager\Value;

final class BlockCreateStruct extends Value implements ParameterStruct, ConfigAwareStruct
{
    use ParameterStructTrait;
    use ConfigAwareStructTrait;

    /**
     * Block definition to create the new block from.
     *
     * Required.
     *
     * @var \Netgen\BlockManager\Block\BlockDefinitionInterface
     */
    public $definition;

    /**
     * View type of the new block.
     *
     * Required.
     *
     * @var string
     */
    public $viewType;

    /**
     * Item view type of the new block.
     *
     * Required.
     *
     * @var string
     */
    public $itemViewType;

    /**
     * Human readable name of the block.
     *
     * @var string|null
     */
    public $name;

    /**
     * Specifies if the block will be translatable.
     *
     * Required.
     *
     * @var bool
     */
    public $isTranslatable;

    /**
     * Specifies if the block will be always available.
     *
     * Required.
     *
     * @var bool
     */
    public $alwaysAvailable;

    /**
     * The list of collections to create in the block.
     *
     * The keys are collection identifiers, while the values are instances of CollectionCreateStruct objects.
     *
     * @var \Netgen\BlockManager\API\Values\Collection\CollectionCreateStruct[]
     */
    private $collectionCreateStructs = [];

    /**
     * Adds a collection create struct with specified identifier to the struct.
     */
    public function addCollectionCreateStruct(string $identifier, CollectionCreateStruct $collectionCreateStruct): void
    {
        $this->collectionCreateStructs[$identifier] = $collectionCreateStruct;
    }

    /**
     * Returns all collection create structs from this struct.
     *
     * @return \Netgen\BlockManager\API\Values\Collection\CollectionCreateStruct[]
     */
    public function getCollectionCreateStructs(): array
    {
        return $this->collectionCreateStructs;
    }

    /**
     * Fills the struct with the default parameter values as defined in provided
     * block definition.
     */
    public function fillDefaultParameters(BlockDefinitionInterface $blockDefinition): void
    {
        $this->fillDefault($blockDefinition);
    }

    /**
     * Fills the parameter values based on provided block.
     */
    public function fillParametersFromBlock(Block $block): void
    {
        $this->fillFromCollection($block->getDefinition(), $block);
    }

    /**
     * Fills the parameter values based on provided array of values.
     *
     * If any of the parameters is missing from the input array, the default value
     * based on parameter definition from the block definition will be used.
     *
     * The values in the array need to be in hash format of the value
     * i.e. the format acceptable by the ParameterTypeInterface::fromHash method.
     *
     * If $doImport is set to true, the values will be considered as coming from an import,
     * meaning it will be processed using ParameterTypeInterface::import method instead of
     * ParameterTypeInterface::fromHash method.
     */
    public function fillParametersFromHash(BlockDefinitionInterface $blockDefinition, array $values = [], bool $doImport = false): void
    {
        $this->fillFromHash($blockDefinition, $values, $doImport);
    }
}
