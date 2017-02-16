<?php

namespace Netgen\BlockManager\Tests;

use Netgen\BlockManager\Tests\Stubs\ValueObject;
use PHPUnit\Framework\TestCase;

class ValueObjectTest extends TestCase
{
    /**
     * @covers \Netgen\BlockManager\ValueObject::__construct
     */
    public function testSetProperties()
    {
        $value = new ValueObject(
            array(
                'someProperty' => 42,
                'someOtherProperty' => 84,
            )
        );

        $this->assertEquals(42, $value->someProperty);
        $this->assertEquals(84, $value->someOtherProperty);
    }

    /**
     * @covers \Netgen\BlockManager\ValueObject::__construct
     * @expectedException \Netgen\BlockManager\Exception\InvalidArgumentException
     * @expectedExceptionMessage Property "someNonExistingProperty" does not exist in "Netgen\BlockManager\Tests\Stubs\ValueObject" class.
     */
    public function testSetNonExistingProperties()
    {
        new ValueObject(
            array(
                'someNonExistingProperty' => 42,
            )
        );
    }
}
