<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\DependencyInjection\CompilerPass\Configuration;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Configuration\LayoutTypeRegistryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class LayoutTypeRegistryPassTest extends AbstractCompilerPassTestCase
{
    /**
     * Register the compiler pass under test.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new LayoutTypeRegistryPass());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Configuration\LayoutTypeRegistryPass::process
     */
    public function testProcess()
    {
        $this->setDefinition('netgen_block_manager.configuration.registry.layout_type', new Definition());

        $layoutType = new Definition();
        $layoutType->addTag('netgen_block_manager.configuration.layout_type', array('identifier' => 'layout_type'));
        $this->setDefinition('netgen_block_manager.configuration.layout_type.test', $layoutType);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'netgen_block_manager.configuration.registry.layout_type',
            'addLayoutType',
            array(
                'layout_type',
                new Reference('netgen_block_manager.configuration.layout_type.test'),
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Configuration\LayoutTypeRegistryPass::process
     * @expectedException \RuntimeException
     */
    public function testProcessThrowsExceptionWithNoTagIdentifier()
    {
        $this->setDefinition('netgen_block_manager.configuration.registry.layout_type', new Definition());

        $layoutType = new Definition();
        $layoutType->addTag('netgen_block_manager.configuration.layout_type');
        $this->setDefinition('netgen_block_manager.configuration.layout_type.test', $layoutType);

        $this->compile();
    }
}
