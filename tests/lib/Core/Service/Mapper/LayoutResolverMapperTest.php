<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Core\Service\Mapper;

use Netgen\BlockManager\API\Values\Layout\Layout;
use Netgen\BlockManager\API\Values\LayoutResolver\Condition as APICondition;
use Netgen\BlockManager\API\Values\LayoutResolver\Rule as APIRule;
use Netgen\BlockManager\API\Values\LayoutResolver\Target as APITarget;
use Netgen\BlockManager\API\Values\Value;
use Netgen\BlockManager\Layout\Resolver\ConditionType\NullConditionType;
use Netgen\BlockManager\Layout\Resolver\TargetType\NullTargetType;
use Netgen\BlockManager\Persistence\Values\LayoutResolver\Condition;
use Netgen\BlockManager\Persistence\Values\LayoutResolver\Rule;
use Netgen\BlockManager\Persistence\Values\LayoutResolver\Target;
use Netgen\BlockManager\Tests\Core\Service\ServiceTestCase;

abstract class LayoutResolverMapperTest extends ServiceTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->layoutResolverMapper = $this->createLayoutResolverMapper();
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\LayoutResolverMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\LayoutResolverMapper::mapRule
     */
    public function testMapRule(): void
    {
        $persistenceRule = new Rule(
            [
                'id' => 3,
                'status' => Value::STATUS_PUBLISHED,
                'layoutId' => 1,
                'enabled' => true,
                'priority' => 12,
                'comment' => 'Comment',
            ]
        );

        $rule = $this->layoutResolverMapper->mapRule($persistenceRule);

        $this->assertInstanceOf(APIRule::class, $rule);
        $this->assertEquals(3, $rule->getId());
        $this->assertInstanceOf(Layout::class, $rule->getLayout());
        $this->assertEquals(1, $rule->getLayout()->getId());
        $this->assertTrue($rule->isPublished());
        $this->assertTrue($rule->isEnabled());
        $this->assertEquals(12, $rule->getPriority());
        $this->assertEquals('Comment', $rule->getComment());

        $this->assertNotEmpty($rule->getTargets());

        foreach ($rule->getTargets() as $target) {
            $this->assertInstanceOf(APITarget::class, $target);
        }

        $this->assertNotEmpty($rule->getConditions());

        foreach ($rule->getConditions() as $condition) {
            $this->assertInstanceOf(APICondition::class, $condition);
        }
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\LayoutResolverMapper::mapRule
     */
    public function testMapRuleWithNonExistingLayout(): void
    {
        $persistenceRule = new Rule(
            [
                'layoutId' => 99999,
            ]
        );

        $rule = $this->layoutResolverMapper->mapRule($persistenceRule);

        $this->assertInstanceOf(APIRule::class, $rule);
        $this->assertNull($rule->getLayout());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\LayoutResolverMapper::mapTarget
     */
    public function testMapTarget(): void
    {
        $persistenceTarget = new Target(
            [
                'id' => 1,
                'status' => Value::STATUS_PUBLISHED,
                'ruleId' => 42,
                'type' => 'target',
                'value' => 42,
            ]
        );

        $target = $this->layoutResolverMapper->mapTarget($persistenceTarget);

        $this->assertEquals(
            $this->targetTypeRegistry->getTargetType('target'),
            $target->getTargetType()
        );

        $this->assertInstanceOf(APITarget::class, $target);
        $this->assertEquals(1, $target->getId());
        $this->assertTrue($target->isPublished());
        $this->assertEquals(42, $target->getRuleId());
        $this->assertEquals(42, $target->getValue());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\LayoutResolverMapper::mapTarget
     */
    public function testMapTargetWithInvalidTargetType(): void
    {
        $persistenceTarget = new Target(
            [
                'id' => 1,
                'status' => Value::STATUS_PUBLISHED,
                'ruleId' => 42,
                'type' => 'unknown',
                'value' => 42,
            ]
        );

        $target = $this->layoutResolverMapper->mapTarget($persistenceTarget);

        $this->assertInstanceOf(NullTargetType::class, $target->getTargetType());

        $this->assertInstanceOf(APITarget::class, $target);
        $this->assertEquals(1, $target->getId());
        $this->assertTrue($target->isPublished());
        $this->assertEquals(42, $target->getRuleId());
        $this->assertEquals(42, $target->getValue());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\LayoutResolverMapper::mapCondition
     */
    public function testMapCondition(): void
    {
        $persistenceCondition = new Condition(
            [
                'id' => 1,
                'status' => Value::STATUS_PUBLISHED,
                'ruleId' => 42,
                'type' => 'my_condition',
                'value' => 42,
            ]
        );

        $condition = $this->layoutResolverMapper->mapCondition($persistenceCondition);

        $this->assertEquals(
            $this->conditionTypeRegistry->getConditionType('my_condition'),
            $condition->getConditionType()
        );

        $this->assertInstanceOf(APICondition::class, $condition);
        $this->assertEquals(1, $condition->getId());
        $this->assertTrue($condition->isPublished());
        $this->assertEquals(42, $condition->getRuleId());
        $this->assertEquals(42, $condition->getValue());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\LayoutResolverMapper::mapCondition
     */
    public function testMapConditionWithInvalidConditionType(): void
    {
        $persistenceCondition = new Condition(
            [
                'id' => 1,
                'status' => Value::STATUS_PUBLISHED,
                'ruleId' => 42,
                'type' => 'unknown',
                'value' => 42,
            ]
        );

        $condition = $this->layoutResolverMapper->mapCondition($persistenceCondition);

        $this->assertInstanceOf(NullConditionType::class, $condition->getConditionType());

        $this->assertInstanceOf(APICondition::class, $condition);
        $this->assertEquals(1, $condition->getId());
        $this->assertTrue($condition->isPublished());
        $this->assertEquals(42, $condition->getRuleId());
        $this->assertEquals(42, $condition->getValue());
    }
}
