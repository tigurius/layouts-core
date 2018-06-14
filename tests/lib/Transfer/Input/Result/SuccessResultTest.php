<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Transfer\Input\Result;

use Netgen\BlockManager\Tests\Core\Stubs\Value;
use Netgen\BlockManager\Transfer\Input\Result\SuccessResult;
use PHPUnit\Framework\TestCase;

final class SuccessResultTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Transfer\Input\Result\SuccessResult
     */
    private $result;

    public function setUp(): void
    {
        $this->result = new SuccessResult('type', ['data'], 42, new Value());
    }

    /**
     * @covers \Netgen\BlockManager\Transfer\Input\Result\SuccessResult::__construct
     * @covers \Netgen\BlockManager\Transfer\Input\Result\SuccessResult::getEntityType
     */
    public function testGetEntityType(): void
    {
        $this->assertEquals('type', $this->result->getEntityType());
    }

    /**
     * @covers \Netgen\BlockManager\Transfer\Input\Result\SuccessResult::getData
     */
    public function testGetData(): void
    {
        $this->assertEquals(['data'], $this->result->getData());
    }

    /**
     * @covers \Netgen\BlockManager\Transfer\Input\Result\SuccessResult::getEntityId
     */
    public function testGetEntityId(): void
    {
        $this->assertEquals(42, $this->result->getEntityId());
    }

    /**
     * @covers \Netgen\BlockManager\Transfer\Input\Result\SuccessResult::getEntity
     */
    public function testGetEntity(): void
    {
        $this->assertEquals(new Value(), $this->result->getEntity());
    }
}
