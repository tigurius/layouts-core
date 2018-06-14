<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Core\Values\LayoutResolver;

use Netgen\BlockManager\API\Values\LayoutResolver\RuleCreateStruct;
use PHPUnit\Framework\TestCase;

final class RuleCreateStructTest extends TestCase
{
    public function testDefaultProperties(): void
    {
        $ruleCreateStruct = new RuleCreateStruct();

        $this->assertFalse($ruleCreateStruct->enabled);
    }

    public function testSetProperties(): void
    {
        $ruleCreateStruct = new RuleCreateStruct(
            [
                'layoutId' => 42,
                'priority' => 13,
                'enabled' => true,
                'comment' => 'Comment',
            ]
        );

        $this->assertEquals(42, $ruleCreateStruct->layoutId);
        $this->assertEquals(13, $ruleCreateStruct->priority);
        $this->assertTrue($ruleCreateStruct->enabled);
        $this->assertEquals('Comment', $ruleCreateStruct->comment);
    }
}
