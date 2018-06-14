<?php

declare(strict_types=1);

namespace Netgen\Bundle\BlockManagerBundle\EventListener;

use Netgen\BlockManager\View\ViewInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ViewListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::VIEW => 'onView'];
    }

    /**
     * Adds the caching headers for the view provided by the event.
     */
    public function onView(GetResponseForControllerResultEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $controllerResult = $event->getControllerResult();
        if (!$controllerResult instanceof ViewInterface) {
            return;
        }

        $event->getRequest()->attributes->set('ngbmView', $controllerResult);
    }
}
