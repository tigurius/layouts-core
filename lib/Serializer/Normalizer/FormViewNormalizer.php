<?php

namespace Netgen\BlockManager\Serializer\Normalizer;

use Netgen\BlockManager\Serializer\Values\FormView;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\View\ViewBuilderInterface;
use Netgen\BlockManager\View\ViewInterface;
use Netgen\BlockManager\View\RendererInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class FormViewNormalizer extends SerializerAwareNormalizer implements NormalizerInterface
{
    /**
     * @var \Netgen\BlockManager\View\ViewBuilderInterface
     */
    protected $viewBuilder;

    /**
     * @var \Netgen\BlockManager\View\RendererInterface
     */
    protected $viewRenderer;

    /**
     * Constructor.
     *
     * @param \Netgen\BlockManager\View\ViewBuilderInterface $viewBuilder
     * @param \Netgen\BlockManager\View\RendererInterface $viewRenderer
     */
    public function __construct(ViewBuilderInterface $viewBuilder, RendererInterface $viewRenderer)
    {
        $this->viewBuilder = $viewBuilder;
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param \Netgen\BlockManager\Serializer\Values\FormView $object
     * @param string $format
     * @param array $context
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $normalizedData = $this->serializer->normalize(
            new VersionedValue(
                $object->getValue(),
                $object->getVersion(),
                $object->getStatusCode()
            )
        );

        $view = $this->viewBuilder->buildView(
            $object->getValue(),
            ViewInterface::CONTEXT_API_EDIT,
            array(
                'form' => $object->getForm()->createView(),
                'api_version' => $object->getVersion(),
            ) + $object->getViewParameters()
        );

        $normalizedData['form'] = $this->viewRenderer->renderView($view);

        return $normalizedData;
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed $data
     * @param string $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FormView;
    }
}
