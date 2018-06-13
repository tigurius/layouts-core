<?php

declare(strict_types=1);

namespace Netgen\Bundle\BlockManagerAdminBundle\Controller\Admin\LayoutResolver;

use Exception;
use Netgen\BlockManager\API\Service\LayoutResolverService;
use Netgen\BlockManager\Exception\BadStateException;
use Netgen\BlockManager\Exception\NotFoundException;
use Netgen\Bundle\BlockManagerAdminBundle\Controller\Admin\Controller;
use Netgen\Bundle\BlockManagerAdminBundle\Controller\Admin\LayoutResolver\Utils\PrioritiesValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UpdateRulePriorities extends Controller
{
    /**
     * @var \Netgen\BlockManager\API\Service\LayoutResolverService
     */
    private $layoutResolverService;

    /**
     * @var \Netgen\Bundle\BlockManagerAdminBundle\Controller\Admin\LayoutResolver\Utils\PrioritiesValidator
     */
    private $prioritiesValidator;

    public function __construct(
        LayoutResolverService $layoutResolverService,
        PrioritiesValidator $prioritiesValidator
    ) {
        $this->layoutResolverService = $layoutResolverService;
        $this->prioritiesValidator = $prioritiesValidator;
    }

    /**
     * Updates rule priorities.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Netgen\BlockManager\Exception\BadStateException If an error occurred
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $this->prioritiesValidator->validatePriorities($request);

        try {
            $this->layoutResolverService->transaction(
                function () use ($request) {
                    // Rules are ordered by descending priority
                    // in the request variable, we reverse the list here
                    // as it is way easier to increment priorities
                    // then decrement them (especially when we need to
                    // make sure to skip rules which do not exist)
                    $ruleIds = array_reverse(
                        array_unique(
                            $request->request->get('rule_ids')
                        )
                    );

                    $ruleUpdateStruct = $this->layoutResolverService->newRuleMetadataUpdateStruct();
                    $ruleUpdateStruct->priority = 10;

                    foreach (array_values($ruleIds) as $ruleId) {
                        try {
                            $rule = $this->layoutResolverService->loadRule($ruleId);
                        } catch (NotFoundException $e) {
                            continue;
                        }

                        $this->layoutResolverService->updateRuleMetadata(
                            $rule,
                            $ruleUpdateStruct
                        );

                        $ruleUpdateStruct->priority += 10;
                    }
                }
            );
        } catch (Exception $e) {
            throw new BadStateException('rule', $e->getMessage());
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
