<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Interface for "conveying" error context to clients.
 *
 * The mechanism is used to generate coherent error information for the clients.
 */
interface SupportsPublicConveying
{
    /**
     * Conveys error message and details,
     * to be passed to the client.
     *
     * Please NOTE:
     *   The implementations MAY choose to use the exception's internal message as the default conveyed message
     *   when no message is explicitly provided, potentially EXPOSING unintended data.
     *
     * Calls to this method SHOULD create and pass a public error context object (an `ErrorContainer` instance )
     * using the `SupportsPublicContext::pass` method.
     * @see ErrorContainer
     * @see SupportsPublicContext
     */
    public function convey(
        ?string $message = null,
        ?string $source = null,
        ?string $detail = null,
        mixed $meta = null,
        ?int $status = null,
        ?string $code = null,
    ): self;
}
