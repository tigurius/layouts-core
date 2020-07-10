<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsAdminBundle\Controller\Admin\Transfer;

use Netgen\Bundle\LayoutsBundle\Controller\AbstractController;
use Netgen\Layouts\Transfer\Output\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Kernel;
use function array_unique;
use function date;
use function json_encode;
use function sprintf;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

final class Export extends AbstractController
{
    /**
     * @var \Netgen\Layouts\Transfer\Output\SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Exports the provided list of entities.
     */
    public function __invoke(string $type, Request $request): Response
    {
        $this->denyAccessUnlessGranted('nglayouts:ui:access');

        $entityIds = Kernel::VERSION_ID >= 50100 ?
            $request->request->all('entity_ids') :
            (array) ($request->request->get('entity_ids') ?? []);

        $serializedEntities = $this->serializer->serialize($type, array_unique($entityIds));
        $json = json_encode($serializedEntities, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        $response = new Response($json);

        $fileName = sprintf('netgen_layouts_export_%s_%s.json', $type, date('Y-m-d_H-i-s'));
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Content-Disposition', $disposition);
        // X-Filename header is needed for AJAX file download support
        $response->headers->set('X-Filename', $fileName);

        return $response;
    }
}
