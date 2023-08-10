<?php

declare(strict_types=1);

namespace Dakujem\Strata\Contracts;

use Throwable;

/**
 * Exceptions caused by third party software.
 * These exceptions should be handled by people responsible for the problematic service.
 *
 * Typically, the server needs to be able to recover from these exceptions.
 *
 * The errors should be fixed on the third-party-side or a workaround must be implemented.
 * Consider contacting the third party.
 *
 * These faults should be logged, reported, and communicated to the client with a sensible error message.
 */
interface IndicatesThirdPartyFault extends Throwable
{
}
