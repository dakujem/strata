<?php

declare(strict_types=1);

namespace Dakujem\Strata;

use Dakujem\Strata\Contracts\IndicatesServerFault;
use Dakujem\Strata\Support\SupportContextAwareExceptions;
use Dakujem\Strata\Support\SupportsInternals;
use Dakujem\Strata\Support\SupportsPublicContext;
use Dakujem\Strata\Support\SupportsPublicConveying;
use Throwable;

/**
 * A generic runtime server-side exception indicating unexpected state of things.
 */
class RuntimeException extends \RuntimeException implements
    IndicatesServerFault,
    SupportsInternals,
    SupportsPublicContext,
    SupportsPublicConveying
{
    use SupportContextAwareExceptions;

    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?? '', $code ?? 0, $previous);
    }
}
