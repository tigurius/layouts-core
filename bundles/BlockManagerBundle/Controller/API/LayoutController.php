<?php

namespace Netgen\Bundle\BlockManagerBundle\Controller\API;

use Netgen\BlockManager\API\Values\Page\Layout;
use Symfony\Component\HttpFoundation\JsonResponse;

class LayoutController extends Controller
{
    /**
     * Serializes the layout object.
     *
     * @param \Netgen\BlockManager\API\Values\Page\Layout $layout
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function view(Layout $layout)
    {
        $response = new JsonResponse();
        $response->setContent(
            $this->serializeValueObject($layout)
        );

        return $response;
    }

    /**
     * Serializes the blocks from provided layout object.
     *
     * @param \Netgen\BlockManager\API\Values\Page\Layout $layout
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function viewLayoutBlocks(Layout $layout)
    {
        $blocks = array();
        foreach ($layout->getZones() as $zone) {
            foreach ($zone->getBlocks() as $block) {
                $blocks[] = $this->normalizeValueObject($block);
            }
        }

        $response = new JsonResponse();
        $response->setContent($this->serializeData($blocks));

        return $response;
    }
}
