<?php

declare(strict_types=1);

namespace Netgen\Bundle\BlockManagerBundle\Tests\Templating\Twig\Runtime\CollectionPager;

use Netgen\BlockManager\Context\ContextInterface;
use Netgen\BlockManager\Core\Values\Block\Block;
use Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\CollectionPager\RouteGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RouteGeneratorTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $uriSignerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $urlGeneratorMock;

    /**
     * @var \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\CollectionPager\RouteGenerator
     */
    private $routeGenerator;

    public function setUp()
    {
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->uriSignerMock = $this->createMock(UriSigner::class);
        $this->urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $this->routeGenerator = new RouteGenerator(
            $this->contextMock,
            $this->uriSignerMock,
            $this->urlGeneratorMock
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\CollectionPager\RouteGenerator::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\CollectionPager\RouteGenerator::__invoke
     */
    public function testInvoke()
    {
        $block = new Block(
            [
                'id' => 42,
                'locale' => 'en',
            ]
        );

        $this->contextMock->expects($this->once())
            ->method('all')
            ->will($this->returnValue(['var' => 'value']));

        $this->urlGeneratorMock->expects($this->once())
            ->method('generate')
            ->with(
                $this->equalTo('ngbm_ajax_block'),
                $this->equalTo(
                    [
                        'blockId' => 42,
                        'locale' => 'en',
                        'collectionIdentifier' => 'default',
                        'ngbmContext' => ['var' => 'value'],
                    ]
                )
            )
            ->will($this->returnValue('/generated/uri'));

        $this->uriSignerMock->expects($this->once())
            ->method('sign')
            ->with($this->equalTo('/generated/uri'))
            ->will($this->returnValue('/signed/uri'));

        $routeGenerator = $this->routeGenerator;
        $url = $routeGenerator($block, 'default', 5);

        $this->assertEquals('/signed/uri?page=5', $url);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\CollectionPager\RouteGenerator::__invoke
     */
    public function testInvokeWithFirstPage()
    {
        $block = new Block(
            [
                'id' => 42,
                'locale' => 'en',
            ]
        );

        $this->contextMock->expects($this->once())
            ->method('all')
            ->will($this->returnValue(['var' => 'value']));

        $this->urlGeneratorMock->expects($this->once())
            ->method('generate')
            ->with(
                $this->equalTo('ngbm_ajax_block'),
                $this->equalTo(
                    [
                        'blockId' => 42,
                        'locale' => 'en',
                        'collectionIdentifier' => 'default',
                        'ngbmContext' => ['var' => 'value'],
                    ]
                )
            )
            ->will($this->returnValue('/generated/uri'));

        $this->uriSignerMock->expects($this->once())
            ->method('sign')
            ->with($this->equalTo('/generated/uri'))
            ->will($this->returnValue('/signed/uri'));

        $routeGenerator = $this->routeGenerator;
        $url = $routeGenerator($block, 'default', 1);

        $this->assertEquals('/signed/uri', $url);
    }
}
