<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

use Throwable;

/**
 * Interface for exceptions able to "suggest" public client-facing error messages
 * in a generic non-technical way.
 *
 * This is mostly used in automatic error handling,
 * as exception messages SHOULD be considered internal information.
 *
 * ! WARNING !
 * DO NOT use this mechanism to convey technical information to clients, as this may lead to leaks and security issues.
 * Use the "conveying" mechanism to explicitly pass data to clients:
 * @see SupportsPublicContext
 * @see SupportsPublicConveying
 */
interface SuggestsErrorMessage extends Throwable
{
    /**
     * @return string suggested error message
     */
    public function suggestErrorMessage(): string;
}
