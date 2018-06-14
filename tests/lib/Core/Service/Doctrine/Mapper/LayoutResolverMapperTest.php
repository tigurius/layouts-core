<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Core\Service\Doctrine\Mapper;

use Netgen\BlockManager\Tests\Core\Service\Mapper\LayoutResolverMapperTest as BaseLayoutResolverMapperTest;
use Netgen\BlockManager\Tests\Persistence\Doctrine\TestCaseTrait;

final class LayoutResolverMapperTest extends BaseLayoutResolverMapperTest
{
    use TestCaseTrait;

    public function tearDown(): void
    {
        $this->closeDatabase();
    }

    /**
     * Prepares the prerequisites for using services in tests.
     */
    public function preparePersistence(): void
    {
        $this->persistenceHandler = $this->createPersistenceHandler();
    }
}
