<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Compound trait that implements the `SupportsInternals` interface:
 * @see SupportsInternals
 */
trait SupportInternals
{
    use SupportInternalContext, SupportInternalExplanation, SupportTagging;
}
