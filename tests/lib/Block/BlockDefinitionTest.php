<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Block;

use Netgen\BlockManager\Block\BlockDefinition;
use Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection;
use Netgen\BlockManager\Block\BlockDefinition\Configuration\Form;
use Netgen\BlockManager\Block\BlockDefinition\Configuration\ViewType;
use Netgen\BlockManager\Block\DynamicParameters;
use Netgen\BlockManager\Config\ConfigDefinition;
use Netgen\BlockManager\Core\Values\Block\Block;
use Netgen\BlockManager\HttpCache\Block\CacheableResolverInterface;
use Netgen\BlockManager\Tests\Block\Stubs\BlockDefinitionHandler;
use Netgen\BlockManager\Tests\Block\Stubs\HandlerPlugin;
use PHPUnit\Framework\TestCase;
use stdClass;

final class BlockDefinitionTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $cacheableResolverMock;

    /**
     * @var \Netgen\BlockManager\Block\BlockDefinition\BlockDefinitionHandlerInterface
     */
    private $handler;

    /**
     * @var \Netgen\BlockManager\Block\BlockDefinition
     */
    private $blockDefinition;

    public function setUp()
    {
        $this->cacheableResolverMock = $this->createMock(CacheableResolverInterface::class);
        $this->cacheableResolverMock
            ->expects($this->any())
            ->method('isCacheable')
            ->with($this->equalTo(new Block()))
            ->will($this->returnValue(false));

        $this->handler = new BlockDefinitionHandler([], true);

        $this->blockDefinition = new BlockDefinition(
            [
                'identifier' => 'block_definition',
                'handler' => $this->handler,
                'cacheableResolver' => $this->cacheableResolverMock,
                'handlerPlugins' => [HandlerPlugin::instance()],
                'name' => 'Block definition',
                'icon' => '/icon.svg',
                'isTranslatable' => true,
                'forms' => [
                    'content' => new Form(['identifier' => 'content']),
                ],
                'collections' => [
                    'collection' => new Collection(['identifier' => 'collection']),
                ],
                'viewTypes' => [
                    'large' => new ViewType(['identifier' => 'large']),
                    'small' => new ViewType(['identifier' => 'small']),
                ],
                'configDefinitions' => ['config' => new ConfigDefinition()],
            ]
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getIdentifier
     */
    public function testGetIdentifier()
    {
        $this->assertEquals('block_definition', $this->blockDefinition->getIdentifier());
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getName
     */
    public function testGetName()
    {
        $this->assertEquals('Block definition', $this->blockDefinition->getName());
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getIcon
     */
    public function testGetIcon()
    {
        $this->assertEquals('/icon.svg', $this->blockDefinition->getIcon());
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::isTranslatable
     */
    public function testIsTranslatable()
    {
        $this->assertTrue($this->blockDefinition->isTranslatable());
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getForms
     */
    public function testGetForms()
    {
        $this->assertEquals(
            [
                'content' => new Form(['identifier' => 'content']),
            ],
            $this->blockDefinition->getForms()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::hasForm
     */
    public function testHasForm()
    {
        $this->assertTrue($this->blockDefinition->hasForm('content'));
        $this->assertFalse($this->blockDefinition->hasForm('unknown'));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getForm
     */
    public function testGetForm()
    {
        $this->assertEquals(
            new Form(['identifier' => 'content']),
            $this->blockDefinition->getForm('content')
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getForm
     * @expectedException \Netgen\BlockManager\Exception\Block\BlockDefinitionException
     * @expectedExceptionMessage Form "unknown" does not exist in "block_definition" block definition.
     */
    public function testGetFormThrowsBlockDefinitionException()
    {
        $this->blockDefinition->getForm('unknown');
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getCollections
     */
    public function testGetCollections()
    {
        $this->assertEquals(
            [
                'collection' => new Collection(['identifier' => 'collection']),
            ],
            $this->blockDefinition->getCollections()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::hasCollection
     */
    public function testHasCollection()
    {
        $this->assertTrue($this->blockDefinition->hasCollection('collection'));
        $this->assertFalse($this->blockDefinition->hasCollection('unknown'));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getCollection
     */
    public function testGetCollection()
    {
        $this->assertEquals(
            new Collection(['identifier' => 'collection']),
            $this->blockDefinition->getCollection('collection')
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getCollection
     * @expectedException \Netgen\BlockManager\Exception\Block\BlockDefinitionException
     * @expectedExceptionMessage Collection "unknown" does not exist in "block_definition" block definition.
     */
    public function testGetCollectionThrowsBlockDefinitionException()
    {
        $this->blockDefinition->getCollection('unknown');
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getViewTypes
     */
    public function testGetViewTypes()
    {
        $this->assertEquals(
            [
                'large' => new ViewType(['identifier' => 'large']),
                'small' => new ViewType(['identifier' => 'small']),
            ],
            $this->blockDefinition->getViewTypes()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getViewTypeIdentifiers
     */
    public function testGetViewTypeIdentifiers()
    {
        $this->assertEquals(
            ['large', 'small'],
            $this->blockDefinition->getViewTypeIdentifiers()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::hasViewType
     */
    public function testHasViewType()
    {
        $this->assertTrue($this->blockDefinition->hasViewType('large'));
        $this->assertFalse($this->blockDefinition->hasViewType('unknown'));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getViewType
     */
    public function testGetViewType()
    {
        $this->assertEquals(
            new ViewType(['identifier' => 'large']),
            $this->blockDefinition->getViewType('large')
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getViewType
     * @expectedException \Netgen\BlockManager\Exception\Block\BlockDefinitionException
     * @expectedExceptionMessage View type "unknown" does not exist in "block_definition" block definition.
     */
    public function testGetViewTypeThrowsBlockDefinitionException()
    {
        $this->blockDefinition->getViewType('unknown');
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getDynamicParameters
     */
    public function testGetDynamicParameters()
    {
        $dynamicParameters = new DynamicParameters();
        $dynamicParameters['definition_param'] = 'definition_value';
        $dynamicParameters['closure_param'] = function () {
            return 'closure_value';
        };

        $dynamicParameters = $this->blockDefinition->getDynamicParameters(new Block());

        $this->assertCount(3, $dynamicParameters);

        $this->assertArrayHasKey('definition_param', $dynamicParameters);
        $this->assertArrayHasKey('closure_param', $dynamicParameters);
        $this->assertArrayHasKey('dynamic_param', $dynamicParameters);

        $this->assertEquals('definition_value', $dynamicParameters['definition_param']);
        $this->assertEquals('closure_value', $dynamicParameters['closure_param']);
        $this->assertEquals('dynamic_value', $dynamicParameters['dynamic_param']);
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::isContextual
     */
    public function testIsContextual()
    {
        $this->assertTrue($this->blockDefinition->isContextual(new Block()));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::isCacheable
     */
    public function testIsCacheable()
    {
        $this->assertFalse($this->blockDefinition->isCacheable(new Block()));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::getConfigDefinitions
     */
    public function testGetConfigDefinitions()
    {
        $this->assertEquals(
            ['config' => new ConfigDefinition()],
            $this->blockDefinition->getConfigDefinitions()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::hasPlugin
     */
    public function testHasPlugin()
    {
        $this->assertTrue($this->blockDefinition->hasPlugin(HandlerPlugin::class));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition::hasPlugin
     */
    public function testHasPluginWithUnknownPlugin()
    {
        $this->assertFalse($this->blockDefinition->hasPlugin(stdClass::class));
    }
}
