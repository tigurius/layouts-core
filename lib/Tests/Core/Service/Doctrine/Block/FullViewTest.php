<?php

namespace Netgen\BlockManager\Tests\Core\Service\Doctrine\Block;

use Netgen\BlockManager\Tests\Core\Service\Block\FullViewTest as BaseFullViewTest;
use Netgen\BlockManager\Tests\Persistence\Doctrine\TestCaseTrait;

/**
 * @covers \Netgen\BlockManager\Block\BlockDefinition\Handler\FullViewHandler::__construct
 * @covers \Netgen\BlockManager\Block\BlockDefinition\Handler\FullViewHandler::buildParameters
 */
class FullViewTest extends BaseFullViewTest
{
    use TestCaseTrait;

    /**
     * Prepares the persistence handler used in tests.
     */
    public function preparePersistence()
    {
        $this->persistenceHandler = $this->createPersistenceHandler();
    }

    public function tearDown()
    {
        $this->closeDatabase();
    }
}
