<?php

declare(strict_types=1);

namespace Dakujem\Strata;

use Dakujem\Strata\Contracts\IndicatesServerFault;
use Dakujem\Strata\Support\SupportsContextStrata;
use Dakujem\Strata\Support\ContextStrata;
use Throwable;

/**
 * A generic server-side exception indicating broken application logic.
 */
class LogicException extends \LogicException implements
    IndicatesServerFault,
    SupportsContextStrata
{
    use ContextStrata;

    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?? '', $code ?? 0, $previous);
    }
}
