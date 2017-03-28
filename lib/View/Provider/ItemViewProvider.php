<?php

namespace Netgen\BlockManager\View\Provider;

use Netgen\BlockManager\Exception\View\ViewProviderException;
use Netgen\BlockManager\Item\ItemInterface;
use Netgen\BlockManager\View\View\ItemView;

class ItemViewProvider implements ViewProviderInterface
{
    /**
     * Provides the view.
     *
     * @param mixed $valueObject
     * @param array $parameters
     *
     * @return \Netgen\BlockManager\View\ViewInterface
     */
    public function provideView($valueObject, array $parameters = array())
    {
        if (!isset($parameters['view_type'])) {
            throw ViewProviderException::noParameter('item', 'view_type');
        }

        if (!is_string($parameters['view_type'])) {
            throw ViewProviderException::invalidParameter('item', 'view_type', 'string');
        }

        return new ItemView(
            array(
                'item' => $valueObject,
                'view_type' => $parameters['view_type'],
            )
        );
    }

    /**
     * Returns if this view provider supports the given value object.
     *
     * @param mixed $valueObject
     *
     * @return bool
     */
    public function supports($valueObject)
    {
        return $valueObject instanceof ItemInterface;
    }
}
