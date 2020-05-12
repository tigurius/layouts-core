<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsAdminBundle\Controller\Admin\LayoutResolver;

use Netgen\Bundle\LayoutsBundle\Controller\AbstractController;
use Netgen\Layouts\API\Service\LayoutResolverService;
use Netgen\Layouts\Exception\BadStateException;
use Netgen\Layouts\Exception\NotFoundException;
use Netgen\Layouts\Validator\ValidatorTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Validator\Constraints;
use Throwable;
use function array_reverse;
use function array_unique;
use function array_values;

final class UpdateRulePriorities extends AbstractController
{
    use ValidatorTrait;

    /**
     * @var \Netgen\Layouts\API\Service\LayoutResolverService
     */
    private $layoutResolverService;

    public function __construct(LayoutResolverService $layoutResolverService)
    {
        $this->layoutResolverService = $layoutResolverService;
    }

    /**
     * Updates rule priorities.
     *
     * @throws \Netgen\Layouts\Exception\BadStateException If an error occurred
     */
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted('nglayouts:mapping:reorder');

        $ruleIds = Kernel::VERSION_ID >= 50100 ?
            $request->request->all('rule_ids') :
            (array) ($request->request->get('rule_ids') ?? []);

        $this->validatePriorities($ruleIds);

        try {
            $this->layoutResolverService->transaction(
                function () use ($ruleIds): void {
                    // Rules are ordered by descending priority
                    // in the request variable, we reverse the list here
                    // as it is way easier to increment priorities
                    // then decrement them (especially when we need to
                    // make sure to skip rules which do not exist)
                    $ruleIds = array_reverse(array_unique($ruleIds));

                    $ruleUpdateStruct = $this->layoutResolverService->newRuleMetadataUpdateStruct();
                    $ruleUpdateStruct->priority = 10;

                    foreach (array_values($ruleIds) as $ruleId) {
                        try {
                            $rule = $this->layoutResolverService->loadRule(Uuid::fromString($ruleId));
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
        } catch (Throwable $t) {
            throw new BadStateException('rule', $t->getMessage());
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Validates list of rules from the request when updating priorities.
     *
     * @param string[] $ruleIds
     *
     * @throws \Netgen\Layouts\Exception\Validation\ValidationException If validation failed
     */
    private function validatePriorities(array $ruleIds): void
    {
        $this->validate(
            $ruleIds,
            [
                new Constraints\NotBlank(),
                new Constraints\All(
                    [
                        'constraints' => [
                            new Constraints\NotBlank(),
                            new Constraints\Type(['type' => 'string']),
                        ],
                    ]
                ),
            ],
            'rule_ids'
        );
    }
}
