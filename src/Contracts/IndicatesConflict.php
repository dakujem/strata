<?php

declare(strict_types=1);

namespace Dakujem\Strata\Contracts;

/**
 * Interface for exceptions indicating conflicting actions.
 *
 * Typical causes:
 * - trying to perform an action not allowed or not available in a particular scenario
 *
 * For actions denied to a user because of missing permissions, use `IndicatesAuthorizationFault`.
 * @see IndicatesAuthorizationFault
 *
 * In HTTP, these MAY be communicated to the client with 409 status code.
 */
interface IndicatesConflict extends IndicatesClientFault
{
}
