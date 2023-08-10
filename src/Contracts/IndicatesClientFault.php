<?php

declare(strict_types=1);

namespace Dakujem\Strata\Contracts;

use Dakujem\Strata\Support\SupportsPublicContext;
use Dakujem\Strata\Support\SupportsPublicConveying;
use Throwable;

/**
 * Generic client fault interface.
 *
 * Interface for faults the client should be able to recover from.
 * These faults result from client-side errors, when either the client implementation is faulty
 * or the user made a mistake.
 *
 * Typical causes:
 * - authentication faults; missing, invalid or denied permissions
 * - authorization faults; bad or missing credentials
 * - input validation faults; invalid input
 * - invalid or conflicting actions
 *
 * In HTTP, these faults SHOULD be communicated to the client with 4xx HTTP status codes,
 * accompanied by a descriptive error message for the client.
 *
 * These exceptions may be accompanied by the following mechanisms to communicate information to clients:
 * @see SupportsPublicContext
 * @see SupportsPublicConveying
 */
interface IndicatesClientFault extends Throwable
{
}
