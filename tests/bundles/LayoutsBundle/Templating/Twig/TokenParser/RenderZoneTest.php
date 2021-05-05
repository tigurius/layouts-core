<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsBundle\Tests\Templating\Twig\TokenParser;

use Netgen\Bundle\LayoutsBundle\Templating\Twig\Node\RenderZone as RenderZoneNode;
use Netgen\Bundle\LayoutsBundle\Templating\Twig\TokenParser\RenderZone;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Error\SyntaxError;
use Twig\Loader\LoaderInterface;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Parser;
use Twig\Source;

final class RenderZoneTest extends TestCase
{
    private Environment $environment;

    private Parser $parser;

    protected function setUp(): void
    {
        $this->environment = new Environment(
            $this->createMock(LoaderInterface::class),
            [
                'cache' => false,
                'autoescape' => false,
                'optimizations' => 0,
            ],
        );

        $this->environment->addTokenParser(new RenderZone());
        $this->parser = new Parser($this->environment);
    }

    /**
     * @covers \Netgen\Bundle\LayoutsBundle\Templating\Twig\TokenParser\RenderZone::getTag
     * @covers \Netgen\Bundle\LayoutsBundle\Templating\Twig\TokenParser\RenderZone::parse
     *
     * @dataProvider compileDataProvider
     */
    public function testCompile(string $source, RenderZoneNode $node): void
    {
        $stream = $this->environment->tokenize(new Source($source, ''));

        self::assertSame((string) $node, (string) $this->parser->parse($stream)->getNode('body')->getNode('0'));
    }

    /**
     * @covers \Netgen\Bundle\LayoutsBundle\Templating\Twig\TokenParser\RenderZone::getTag
     * @covers \Netgen\Bundle\LayoutsBundle\Templating\Twig\TokenParser\RenderZone::parse
     */
    public function testCompileThrowsTwigErrorSyntaxException(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unexpected token "name" of value "foo" at line 1.');

        $stream = $this->environment->tokenize(
            new Source('{% nglayouts_render_zone zone foo=\'bar\' %}', ''),
        );

        $this->parser->parse($stream);
    }

    public function compileDataProvider(): array
    {
        return [
            [
                '{% nglayouts_render_zone zone %}',
                new RenderZoneNode(
                    new NameExpression('zone', 1),
                    null,
                    1,
                    'nglayouts_render_zone',
                ),
            ],
            [
                '{% nglayouts_render_zone zone context="json" %}',
                new RenderZoneNode(
                    new NameExpression('zone', 1),
                    new ConstantExpression('json', 1),
                    1,
                    'nglayouts_render_zone',
                ),
            ],
        ];
    }
}
