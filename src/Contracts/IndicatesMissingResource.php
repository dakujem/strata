<?php

declare(strict_types=1);

namespace Dakujem\Strata\Contracts;

/**
 * Interface for exceptions indicating a missing resource.
 *
 * Typical causes:
 * - requesting a resource that does not exist
 *
 * In HTTP, these MAY be communicated to the client with 404 status code.
 */
interface IndicatesMissingResource extends IndicatesClientFault
{
}
