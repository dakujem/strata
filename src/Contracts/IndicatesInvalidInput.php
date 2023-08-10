<?php

declare(strict_types=1);

namespace Dakujem\Strata\Contracts;

/**
 * Interface for exceptions resulting from data validation.
 *
 * Typical causes:
 * - invalid input data
 * - missing input data
 *
 * In HTTP, these MAY be communicated to the client with 422 status code.
 */
interface IndicatesInvalidInput extends IndicatesClientFault
{
}
