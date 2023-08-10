<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

use Throwable;

/**
 * Interface for exceptions able to "suggest" HTTP status.
 * This is mostly used in automatic error processing in APIs.
 */
interface SuggestsHttpStatus extends Throwable
{
    /**
     * @return int suggested HTTP status code
     */
    public function suggestStatusCode(): int;
}
