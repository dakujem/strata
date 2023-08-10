<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * This is a compound interface for exceptions supporting internal meta-information
 * intended for debugging and analysis:
 * - context
 * - explanation
 * - tagging
 */
interface SupportsInternals extends
    SupportsInternalContext,
    SupportsInternalExplanation,
    SupportsTagging
{
}
