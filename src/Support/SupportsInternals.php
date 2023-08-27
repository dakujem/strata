<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * This is a compound interface for exceptions supporting internal meta-information
 * intended for reporting, debugging or analysis:
 * - context
 * - explanation
 * - tagging
 *
 * It is implemented by the following trait:
 * @see SupportInternals
 */
interface SupportsInternals extends
    SupportsInternalContext,
    SupportsInternalExplanation,
    SupportsTagging
{
}
