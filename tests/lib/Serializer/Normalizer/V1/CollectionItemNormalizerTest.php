<?php

namespace Netgen\BlockManager\Tests\Serializer\Normalizer\V1;

use Netgen\BlockManager\Core\Values\Collection\Item as CollectionItem;
use Netgen\BlockManager\Core\Values\Config\Config;
use Netgen\BlockManager\Item\Item;
use Netgen\BlockManager\Item\ItemLoaderInterface;
use Netgen\BlockManager\Item\NullItem;
use Netgen\BlockManager\Item\UrlBuilderInterface;
use Netgen\BlockManager\Parameters\Parameter;
use Netgen\BlockManager\Serializer\Normalizer\V1\CollectionItemNormalizer;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\Tests\Core\Stubs\Value as APIValue;
use PHPUnit\Framework\TestCase;

final class CollectionItemNormalizerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $itemLoaderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $urlBuilderMock;

    /**
     * @var \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionItemNormalizer
     */
    private $normalizer;

    public function setUp()
    {
        $this->itemLoaderMock = $this->createMock(ItemLoaderInterface::class);
        $this->urlBuilderMock = $this->createMock(UrlBuilderInterface::class);

        $this->normalizer = new CollectionItemNormalizer(
            $this->itemLoaderMock,
            $this->urlBuilderMock
        );
    }

    /**
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionItemNormalizer::__construct
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionItemNormalizer::normalize
     */
    public function testNormalize()
    {
        $item = new CollectionItem(
            array(
                'id' => 42,
                'collectionId' => 24,
                'position' => 3,
                'type' => CollectionItem::TYPE_OVERRIDE,
                'value' => 12,
                'valueType' => 'ezcontent',
                'configs' => array(
                    'visibility' => new Config(
                        array(
                            'parameters' => array(
                                'visibility_status' => new Parameter(
                                    array(
                                        'value' => CollectionItem::VISIBILITY_VISIBLE,
                                    )
                                ),
                            ),
                        )
                    ),
                ),
            )
        );

        $value = new Item(
            array(
                'name' => 'Value name',
                'isVisible' => true,
            )
        );

        $this->itemLoaderMock
            ->expects($this->any())
            ->method('load')
            ->with($this->equalTo(12), $this->equalTo('ezcontent'))
            ->will($this->returnValue($value));

        $this->urlBuilderMock
            ->expects($this->any())
            ->method('getUrl')
            ->with($this->equalTo($value))
            ->will($this->returnValue('/some/url'));

        $this->assertEquals(
            array(
                'id' => $item->getId(),
                'collection_id' => $item->getCollectionId(),
                'position' => $item->getPosition(),
                'type' => $item->getType(),
                'value' => $item->getValue(),
                'value_type' => $item->getValueType(),
                'visible' => $item->isVisible(),
                'scheduled' => $item->isScheduled(),
                'name' => 'Value name',
                'cms_url' => '/some/url',
                'cms_visible' => true,
            ),
            $this->normalizer->normalize(new VersionedValue($item, 1))
        );
    }

    /**
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionItemNormalizer::__construct
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionItemNormalizer::normalize
     */
    public function testNormalizeWithNoValue()
    {
        $item = new CollectionItem(
            array(
                'id' => 42,
                'collectionId' => 24,
                'position' => 3,
                'type' => CollectionItem::TYPE_OVERRIDE,
                'value' => 12,
                'valueType' => 'ezcontent',
                'configs' => array(
                    'visibility' => new Config(
                        array(
                            'parameters' => array(
                                'visibility_status' => new Parameter(
                                    array(
                                        'value' => CollectionItem::VISIBILITY_VISIBLE,
                                    )
                                ),
                            ),
                        )
                    ),
                ),
            )
        );

        $this->itemLoaderMock
            ->expects($this->any())
            ->method('load')
            ->with($this->equalTo(12), $this->equalTo('ezcontent'))
            ->will($this->returnValue(new NullItem(12)));

        $this->urlBuilderMock
            ->expects($this->never())
            ->method('getUrl');

        $this->assertEquals(
            array(
                'id' => $item->getId(),
                'collection_id' => $item->getCollectionId(),
                'position' => $item->getPosition(),
                'type' => $item->getType(),
                'value' => $item->getValue(),
                'value_type' => $item->getValueType(),
                'visible' => $item->isVisible(),
                'scheduled' => $item->isScheduled(),
                'name' => null,
                'cms_url' => null,
                'cms_visible' => true,
            ),
            $this->normalizer->normalize(new VersionedValue($item, 1))
        );
    }

    /**
     * @param mixed $data
     * @param bool $expected
     *
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionItemNormalizer::supportsNormalization
     * @dataProvider supportsNormalizationProvider
     */
    public function testSupportsNormalization($data, $expected)
    {
        $this->assertEquals($expected, $this->normalizer->supportsNormalization($data));
    }

    /**
     * Provider for {@link self::testSupportsNormalization}.
     *
     * @return array
     */
    public function supportsNormalizationProvider()
    {
        return array(
            array(null, false),
            array(true, false),
            array(false, false),
            array('block', false),
            array(array(), false),
            array(42, false),
            array(42.12, false),
            array(new APIValue(), false),
            array(new CollectionItem(), false),
            array(new VersionedValue(new APIValue(), 1), false),
            array(new VersionedValue(new CollectionItem(), 2), false),
            array(new VersionedValue(new CollectionItem(), 1), true),
        );
    }
}
