<?php

declare(strict_types=1);

namespace Dakujem\Strata\Contracts;

/**
 * Indicates client's authorization faults.
 *
 * Typical cases:
 * - permission denied for given user
 * - forbidden access
 *
 * To indicate actions unavailable for reasons different from authorization logic, prefer to use `IndicatesConflict`:
 * @see IndicatesConflict
 *
 * In HTTP, these MAY be communicated to the client with "403 Forbidden" status code.
 */
interface IndicatesAuthorizationFault extends IndicatesClientFault
{
}
