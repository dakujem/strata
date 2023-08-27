<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Interface for carrying "public context" by exceptions.
 *
 * The mechanism is used to generate error information for the clients
 * in cases of error occurrence.
 */
interface SupportsPublicContext
{
    /**
     * Pass a piece of information through the exception's client-facing context.
     * The information is considered public and is intended to be conveyed to the client.
     *
     * Optionally specify a key.
     * Passing the same key SHOULD overwrite the previous value.
     */
    public function pass(mixed $value, ?string $key = null): self;

    /**
     * Get the internal context as an array of passed values.
     * Values passed with keys SHOULD be present under the respective indices as key-value pairs.
     */
    public function publicContext(): array;

    /**
     * Completely replace current client-facing context with given one.
     */
    public function replacePublicContext(array $context): self;
}
