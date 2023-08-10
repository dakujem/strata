<?php

declare(strict_types=1);

namespace Dakujem\Strata\Contracts;

/**
 * Authentication or authorization client's error.
 * Indicates client's authentication faults.
 *
 * Typical cases:
 * - invalid username/password
 * - authentication cookie/token not present
 *
 * In HTTP, these MAY be communicated to the client with "401 Unauthorized" status code.
 */
interface IndicatesAuthenticationFault extends IndicatesClientFault
{
}
