<?php

namespace Netgen\BlockManager\View\Matcher\Block;

use Netgen\BlockManager\View\Matcher\MatcherInterface;
use Netgen\BlockManager\View\View\BlockViewInterface;
use Netgen\BlockManager\View\ViewInterface;

class ViewType implements MatcherInterface
{
    /**
     * Returns if the view matches the config.
     *
     * @param \Netgen\BlockManager\View\ViewInterface $view
     * @param array $config
     *
     * @return bool
     */
    public function match(ViewInterface $view, array $config)
    {
        if (!$view instanceof BlockViewInterface) {
            return false;
        }

        return in_array($view->getBlock()->getViewType(), $config, true);
    }
}
