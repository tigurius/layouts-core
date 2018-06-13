<?php

declare(strict_types=1);

namespace Netgen\Bundle\BlockManagerBundle\Tests\DependencyInjection\CompilerPass\HttpCache;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\HttpCache\ConfigureHttpCachePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

final class ConfigureHttpCachePassTest extends AbstractCompilerPassTestCase
{
    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\HttpCache\ConfigureHttpCachePass::process
     */
    public function testProcess()
    {
        $this->setDefinition('netgen_block_manager.http_cache.client', new Definition('class'));
        $this->setParameter('session.storage.options', []);

        $this->setParameter(
            'netgen_block_manager.http_cache',
            [
                'invalidation' => [
                    'enabled' => true,
                ],
            ]
        );

        $this->compile();

        $this->assertContainerBuilderHasService(
            'netgen_block_manager.http_cache.client',
            'class'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\HttpCache\ConfigureHttpCachePass::process
     */
    public function testProcessWithDisabledInvalidation()
    {
        $this->setDefinition('netgen_block_manager.http_cache.client', new Definition('class'));
        $this->setParameter('session.storage.options', []);

        $this->setParameter(
            'netgen_block_manager.http_cache',
            [
                'invalidation' => [
                    'enabled' => false,
                ],
            ]
        );

        $this->compile();

        $this->assertContainerBuilderHasAlias(
            'netgen_block_manager.http_cache.client',
            'netgen_block_manager.http_cache.client.null'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\HttpCache\ConfigureHttpCachePass::process
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
        $container->addCompilerPass(new ConfigureHttpCachePass());
    }
}
