<?php

declare(strict_types=1);

namespace Netgen\Bundle\BlockManagerBundle\Tests\DependencyInjection\CompilerPass\Item;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Item\ItemBuilderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
use Symfony\Component\DependencyInjection\Reference;

final class ItemBuilderPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Item\ItemBuilderPass::process
     */
    public function testProcess()
    {
        $itemBuilder = new Definition();
        $itemBuilder->addArgument([]);
        $itemBuilder->addArgument([]);
        $this->setDefinition('netgen_block_manager.item.item_builder', $itemBuilder);

        $valueConverter = new Definition();
        $valueConverter->addTag('netgen_block_manager.item.value_converter');
        $this->setDefinition('netgen_block_manager.item.value_converter.test', $valueConverter);

        $valueConverter2 = new Definition();
        $valueConverter2->addTag('netgen_block_manager.item.value_converter');
        $this->setDefinition('netgen_block_manager.item.value_converter.test2', $valueConverter2);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'netgen_block_manager.item.item_builder',
            0,
            [
                new Reference('netgen_block_manager.item.value_converter.test'),
                new Reference('netgen_block_manager.item.value_converter.test2'),
            ]
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Item\ItemBuilderPass::process
     */
    public function testProcessWithEmptyContainer()
    {
        $this->compile();

        $this->assertInstanceOf(FrozenParameterBag::class, $this->container->getParameterBag());
    }

    /**
     * Register the compiler pass under test.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ItemBuilderPass());
    }
}
