<?php

declare(strict_types=1);

namespace Dakujem\Strata\Contracts;

use Dakujem\Strata\Support\SupportsInternalContext;
use Dakujem\Strata\Support\SupportsInternalExplanation;
use Dakujem\Strata\Support\SupportsPublicContext;
use Dakujem\Strata\Support\SupportsPublicConveying;
use Throwable;

/**
 * Generic server fault interface.
 *
 * Application logic exception resulting in faults that the client can not recover from
 * and is supposed to be handled by developers or system administrators.
 *
 * These faults SHOULD be logged and reported to the devs.
 *
 * Typical causes:
 * - bad app design
 * - data broken beyond repair
 * - broken business logic
 * - developer's poo
 * - unfinished stuff
 *
 * In HTTP, they SHOULD be communicated to the client with 5xx status codes
 * without disclosing sensitive technical information.
 *
 * To facilitate transmission of technical information for logging and analysis,
 * consider using the following mechanisms:
 * @see SupportsInternalExplanation
 * @see SupportsInternalContext
 *
 * To communicate information to clients using the exception's context, use
 * @see SupportsPublicContext
 * @see SupportsPublicConveying
 */
interface IndicatesServerFault extends Throwable
{
}
