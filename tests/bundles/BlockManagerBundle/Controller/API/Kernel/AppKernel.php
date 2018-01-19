<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\Controller\API\Kernel;

use Netgen\BlockManager\Tests\MockerContainer;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            // Symfony

            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // Netgen Layouts

            new \Netgen\Bundle\BlockManagerBundle\NetgenBlockManagerBundle(),
            new \Netgen\Bundle\ContentBrowserBundle\NetgenContentBrowserBundle(),
            new \Netgen\Bundle\BlockManagerFixturesBundle\NetgenBlockManagerFixturesBundle(),
            new \Netgen\Bundle\BlockManagerStandardBundle\NetgenBlockManagerStandardBundle(),
        );
    }

    public function getProjectDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir() . '/sfcache';
    }

    public function getLogDir()
    {
        return sys_get_temp_dir() . '/sflogs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir() . '/config/config.yml');
    }

    protected function getContainerBaseClass()
    {
        return '\\' . MockerContainer::class;
    }
}
