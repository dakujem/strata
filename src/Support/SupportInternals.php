<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Implements interface:
 * @see SupportsInternals
 */
trait SupportInternals
{
    use SupportInternalContext, SupportInternalExplanation, SupportTagging;
}
