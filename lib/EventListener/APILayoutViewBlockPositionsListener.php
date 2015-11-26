<?php

namespace Netgen\BlockManager\EventListener;

use Netgen\BlockManager\Event\View\CollectViewParametersEvent;
use Netgen\BlockManager\Event\View\ViewEvents;
use Netgen\BlockManager\View\LayoutViewInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class APILayoutViewBlockPositionsListener implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(ViewEvents::BUILD_VIEW => 'onBuildView');
    }

    /**
     * Adds a list of zones and allowed blocks to API layout views.
     *
     * Used in layout view serialization process.
     *
     * @param \Netgen\BlockManager\Event\View\CollectViewParametersEvent $event
     */
    public function onBuildView(CollectViewParametersEvent $event)
    {
        $view = $event->getView();
        if (!$view instanceof LayoutViewInterface || $view->getContext() !== 'api') {
            return;
        }

        $positions = array();
        $layout = $view->getLayout();

        foreach ($layout->getZones() as $zone) {
            $blocksInZone = array();
            $zoneIdentifier = $zone->getIdentifier();
            $layoutBlocks = $view->getParameter('blocks');

            if (!empty($layoutBlocks[$zoneIdentifier])) {
                foreach ($layoutBlocks[$zoneIdentifier] as $block) {
                    /** @var \Netgen\BlockManager\API\Values\Page\Block $block */
                    $blocksInZone[] = array('block_id' => $block->getId());
                }
            }

            $positions[] = array(
                'zone' => $zoneIdentifier,
                'blocks' => $blocksInZone,
            );
        }

        $event->getParameterBag()->set('positions', $positions);
    }
}
