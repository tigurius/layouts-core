<?php

namespace Netgen\BlockManager\Tests\Layout\Form;

use Netgen\BlockManager\API\Values\Layout\LayoutCopyStruct;
use Netgen\BlockManager\Core\Values\Layout\Layout;
use Netgen\BlockManager\Layout\Form\CopyType;
use Netgen\BlockManager\Tests\TestCase\FormTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CopyTypeTest extends FormTestCase
{
    /**
     * @var \Netgen\BlockManager\API\Values\Layout\Layout
     */
    protected $layout;

    public function setUp()
    {
        parent::setUp();

        $this->layout = new Layout();
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function getMainType()
    {
        return new CopyType();
    }

    /**
     * @covers \Netgen\BlockManager\Layout\Form\CopyType::buildForm
     */
    public function testSubmitValidData()
    {
        $submittedData = array(
            'name' => 'New name',
            'description' => 'New description',
        );

        $updatedStruct = new LayoutCopyStruct();
        $updatedStruct->name = 'New name';
        $updatedStruct->description = 'New description';

        $form = $this->factory->create(
            CopyType::class,
            new LayoutCopyStruct(),
            array(
                'layout' => $this->layout,
            )
        );

        $form->submit($submittedData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($updatedStruct, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($submittedData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * @covers \Netgen\BlockManager\Layout\Form\CopyType::configureOptions
     */
    public function testConfigureOptions()
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefined('data');

        $this->formType->configureOptions($optionsResolver);

        $options = $optionsResolver->resolve(
            array(
                'layout' => $this->layout,
                'data' => new LayoutCopyStruct(),
            )
        );

        $this->assertEquals($this->layout, $options['layout']);
        $this->assertEquals(new LayoutCopyStruct(), $options['data']);
    }

    /**
     * @covers \Netgen\BlockManager\Layout\Form\CopyType::configureOptions
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function testConfigureOptionsWithMissingLayoutDefinition()
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefined('data');

        $this->formType->configureOptions($optionsResolver);

        $optionsResolver->resolve();
    }

    /**
     * @covers \Netgen\BlockManager\Layout\Form\CopyType::configureOptions
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function testConfigureOptionsWithInvalidLayoutDefinition()
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefined('data');

        $this->formType->configureOptions($optionsResolver);

        $optionsResolver->resolve(
            array(
                'layout' => '',
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Layout\Form\CopyType::configureOptions
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function testConfigureOptionsWithInvalidData()
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefined('data');

        $this->formType->configureOptions($optionsResolver);

        $optionsResolver->resolve(
            array(
                'layout' => $this->layout,
                'data' => '',
            )
        );
    }
}
