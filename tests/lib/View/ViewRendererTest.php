<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\View;

use Netgen\BlockManager\Tests\Core\Stubs\Value;
use Netgen\BlockManager\Tests\View\Stubs\View;
use Netgen\BlockManager\View\ViewRenderer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

final class ViewRendererTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $eventDispatcherMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $twigEnvironmentMock;

    /**
     * @var \Netgen\BlockManager\View\ViewRenderer
     */
    private $viewRenderer;

    public function setUp()
    {
        $this->eventDispatcherMock = $this
            ->createMock(EventDispatcherInterface::class);

        $this->twigEnvironmentMock = $this
            ->createMock(Environment::class);

        $this->viewRenderer = new ViewRenderer(
            $this->eventDispatcherMock,
            $this->twigEnvironmentMock
        );
    }

    /**
     * @covers \Netgen\BlockManager\View\ViewRenderer::__construct
     * @covers \Netgen\BlockManager\View\ViewRenderer::renderView
     */
    public function testRenderView()
    {
        $view = new View(['value' => new Value()]);
        $view->setTemplate('some_template.html.twig');
        $view->addParameter('some_param', 'some_value');

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch');

        $this->twigEnvironmentMock
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('some_template.html.twig'),
                $this->equalTo(
                    [
                        'some_param' => 'some_value',
                        'value' => new Value(),
                    ]
                )
            )
            ->will($this->returnValue('rendered template'));

        $renderedTemplate = $this->viewRenderer->renderView($view);

        $this->assertEquals('rendered template', $renderedTemplate);
    }
}
