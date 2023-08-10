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
 * A generic server-side exception indicating broken application logic.
 */
class LogicException extends \LogicException implements
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
