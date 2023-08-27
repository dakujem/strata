<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * This is a compound interface for exceptions supporting both
 * internal meta-information intended for reporting, debugging or analysis
 * (internal context, explanation, tagging),
 * and public-facing meta-information to be conveyed to client consumers
 * (public context).
 *
 * This is the counterpart of `ContextStrata` trait:
 * @see ContextStrata
 *
 * This compound interface comprises the following interfaces:
 * @see SupportsInternalContext
 * @see SupportsInternalExplanation
 * @see SupportsTagging
 * @see SupportsPublicContext
 * @see SupportsPublicConveying
 */
interface SupportsContextStrata extends
    SupportsInternals,
    SupportsPublicContext,
    SupportsPublicConveying
{
}
