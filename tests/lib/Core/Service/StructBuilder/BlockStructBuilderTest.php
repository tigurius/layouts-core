<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Core\Service\StructBuilder;

use Netgen\BlockManager\API\Values\Block\BlockCreateStruct;
use Netgen\BlockManager\API\Values\Block\BlockUpdateStruct;
use Netgen\BlockManager\API\Values\Config\ConfigStruct;
use Netgen\BlockManager\Core\Service\StructBuilder\BlockStructBuilder;
use Netgen\BlockManager\Core\Service\StructBuilder\ConfigStructBuilder;
use Netgen\BlockManager\Tests\Core\Service\ServiceTestCase;

abstract class BlockStructBuilderTest extends ServiceTestCase
{
    /**
     * @var \Netgen\BlockManager\Core\Service\StructBuilder\BlockStructBuilder
     */
    private $structBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->blockService = $this->createBlockService();

        $this->structBuilder = new BlockStructBuilder(
            new ConfigStructBuilder()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\BlockStructBuilder::__construct
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\BlockStructBuilder::newBlockCreateStruct
     */
    public function testNewBlockCreateStruct(): void
    {
        $blockDefinition = $this->blockDefinitionRegistry->getBlockDefinition('title');

        $this->assertEquals(
            new BlockCreateStruct(
                [
                    'isTranslatable' => true,
                    'alwaysAvailable' => true,
                    'definition' => $blockDefinition,
                    'viewType' => 'small',
                    'itemViewType' => 'standard',
                    'parameterValues' => [
                        'css_class' => 'some-class',
                        'css_id' => null,
                    ],
                ]
            ),
            $this->structBuilder->newBlockCreateStruct($blockDefinition)
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\BlockStructBuilder::newBlockUpdateStruct
     */
    public function testNewBlockUpdateStruct(): void
    {
        $blockUpdateStruct = new BlockUpdateStruct();
        $blockUpdateStruct->locale = 'en';

        $this->assertEquals(
            $blockUpdateStruct,
            $this->structBuilder->newBlockUpdateStruct('en')
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\StructBuilder\BlockStructBuilder::newBlockUpdateStruct
     */
    public function testNewBlockUpdateStructFromBlock(): void
    {
        $block = $this->blockService->loadBlockDraft(36);

        $this->assertEquals(
            new BlockUpdateStruct(
                [
                    'locale' => 'en',
                    'alwaysAvailable' => true,
                    'viewType' => $block->getViewType(),
                    'itemViewType' => $block->getItemViewType(),
                    'name' => $block->getName(),
                    'parameterValues' => [
                        'css_class' => 'CSS class',
                        'css_id' => null,
                    ],
                    'configStructs' => [
                        'http_cache' => new ConfigStruct(
                            [
                                'parameterValues' => [
                                    'use_http_cache' => null,
                                    'shared_max_age' => null,
                                ],
                            ]
                        ),
                    ],
                ]
            ),
            $this->structBuilder->newBlockUpdateStruct('en', $block)
        );
    }
}
