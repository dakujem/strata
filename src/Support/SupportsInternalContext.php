<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Interface for carrying "internal context" by exceptions.
 *
 * Internal context SHOULD NOT be exposed to clients
 * and SHOULD be used for internal purposes only (reporting, debugging, logging, analytics, etc.).
 */
interface SupportsInternalContext
{
    /**
     * Append a piece of information to the exception's context.
     * The information is considered internal.
     *
     * Optionally specify a key.
     * Passing the same key SHOULD overwrite the previous value.
     */
    public function pin(mixed $value, string|int|null $key = null): self;

    /**
     * Get the internal context as an array of pinned values.
     * Values passed with keys SHOULD be present under the respective indices as key-value pairs.
     */
    public function context(): array;

    /**
     * Completely replace current context with given one.
     */
    public function replaceContext(array $context): self;
}
