<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Trait implementing the mechanism for carrying "internal context" by exceptions.
 *
 * Internal context SHOULD NOT be exposed to clients
 * and SHOULD be used for internal purposes only (debugging, logging, analytics, etc.).
 *
 * The trait implements interface:
 * @see SupportsInternalContext
 */
trait SupportInternalContext
{
    protected array $context = [];

    /**
     * Pin a piece of information to the exception.
     * The information is considered internal.
     * Optionally specify a key to the internal context.
     *
     * Passing `null` with a key will remove the information under the key.
     */
    public function pin(mixed $value, string|int|null $key = null): static
    {
        if ($value === null) {
            if ($key !== null) {
                unset($this->context[$key]);
            }
            return $this;
        }
        if ($key === null) {
            $this->context[] = $value;
        } else {
            $this->context[$key] = $value;
        }
        return $this;
    }

    /**
     * Get the internal context as an associative array of key-value pairs.
     */
    public function context(): array
    {
        return $this->context;
    }

    /**
     * Replace current context with given one.
     */
    public function replaceContext(array $context): static
    {
        $this->context = $context;
        return $this;
    }
}
