<?php

declare(strict_types=1);

namespace Netgen\BlockManager\View\Provider;

use Netgen\BlockManager\API\Values\LayoutResolver\Target;
use Netgen\BlockManager\View\View\RuleTargetView;

final class RuleTargetViewProvider implements ViewProviderInterface
{
    public function provideView($value, array $parameters = [])
    {
        return new RuleTargetView(
            [
                'target' => $value,
            ]
        );
    }

    public function supports($value)
    {
        return $value instanceof Target;
    }
}
