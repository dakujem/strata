<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * A compound trait for exceptions able to transfer both
 * client-facing public context and developer-facing internal context.
 *
 * This is the counterpart of the `SupportsContextStrata` compound interface:
 * @see SupportsContextStrata
 *
 * Implements the following interfaces:
 * @see SupportsInternalContext
 * @see SupportsInternalExplanation
 * @see SupportsTagging
 * @see SupportsPublicContext
 * @see SupportsPublicConveying
 */
trait ContextStrata
{
    use SupportPublicConveying;
    use SupportPublicContext;
    use SupportInternals;
}
