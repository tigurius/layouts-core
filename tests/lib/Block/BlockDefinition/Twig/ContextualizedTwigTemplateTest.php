<?php

namespace Netgen\BlockManager\Tests\Block\BlockDefinition\Twig;

use Exception;
use Netgen\BlockManager\Block\BlockDefinition\Twig\ContextualizedTwigTemplate;
use PHPUnit\Framework\TestCase;
use Twig_Template;

class ContextualizedTwigTemplateTest extends TestCase
{
    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Twig\ContextualizedTwigTemplate::__construct
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Twig\ContextualizedTwigTemplate::renderBlock
     */
    public function testRenderBlock()
    {
        $templateMock = $this->createMock(Twig_Template::class);

        $templateMock
            ->expects($this->any())
            ->method('displayBlock')
            ->with($this->equalTo('block_name'))
            ->will($this->returnCallback(
                function ($blockName) {
                    echo 'rendered';
                }
            )
        );

        $template = new ContextualizedTwigTemplate($templateMock);

        $this->assertEquals('rendered', $template->renderBlock('block_name'));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Twig\ContextualizedTwigTemplate::renderBlock
     * @expectedException \Exception
     */
    public function testRenderBlockWithException()
    {
        $templateMock = $this->createMock(Twig_Template::class);

        $templateMock
            ->expects($this->any())
            ->method('displayBlock')
            ->with($this->equalTo('block_name'))
            ->will($this->throwException(new Exception()));

        $template = new ContextualizedTwigTemplate($templateMock);
        $template->renderBlock('block_name');
    }
}
