<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Transfer\Output\Visitor\Integration;

use Netgen\BlockManager\API\Values\Layout\Layout as APILayout;
use Netgen\BlockManager\Core\Values\Block\Block;
use Netgen\BlockManager\Core\Values\Layout\Layout as LayoutValue;
use Netgen\BlockManager\Core\Values\Layout\Zone;
use Netgen\BlockManager\Transfer\Output\Visitor\Layout;
use Netgen\BlockManager\Transfer\Output\VisitorInterface;

abstract class LayoutTest extends VisitorTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->layoutService = $this->createLayoutService();
    }

    /**
     * @expectedException \Netgen\BlockManager\Exception\RuntimeException
     * @expectedExceptionMessage Implementation requires sub-visitor
     */
    public function testVisitThrowsRuntimeExceptionWithoutSubVisitor(): void
    {
        $this->getVisitor()->visit(new LayoutValue());
    }

    public function getVisitor(): VisitorInterface
    {
        return new Layout();
    }

    public function acceptProvider(): array
    {
        return [
            [new LayoutValue(), true],
            [new Zone(), false],
            [new Block(), false],
        ];
    }

    public function visitProvider(): array
    {
        return [
            [function (): APILayout { return $this->layoutService->loadLayout(1); }, 'layout/layout_1.json'],
            [function (): APILayout { return $this->layoutService->loadLayout(2); }, 'layout/layout_2.json'],
            [function (): APILayout { return $this->layoutService->loadLayout(5); }, 'layout/layout_5.json'],
            [function (): APILayout { return $this->layoutService->loadLayoutDraft(7); }, 'layout/layout_7.json'],
        ];
    }
}
