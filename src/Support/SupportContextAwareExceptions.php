<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * A compound trait for rich context-aware exceptions able to transfer both
 * client-facing context and developer-facing internal context.
 *
 * Implements the following interfaces:
 * @see SupportsInternals
 * @see SupportsPublicContext
 * @see SupportsPublicConveying
 */
trait SupportContextAwareExceptions
{
    use SupportPublicConveying;
    use SupportPublicContext;
    use SupportInternals;
}
