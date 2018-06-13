<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Core\Values\Layout;

use Netgen\BlockManager\API\Values\Layout\LayoutUpdateStruct;
use PHPUnit\Framework\TestCase;

final class LayoutUpdateStructTest extends TestCase
{
    public function testSetProperties()
    {
        $layoutUpdateStruct = new LayoutUpdateStruct(
            [
                'name' => 'My layout',
                'description' => 'My description',
            ]
        );

        $this->assertEquals('My layout', $layoutUpdateStruct->name);
        $this->assertEquals('My description', $layoutUpdateStruct->description);
    }
}
