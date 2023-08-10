<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * This trait allows for an Exception to carry error details intended for client-facing error renderers.
 *
 * It implements interface:
 * @see SupportsPublicContext
 */
trait SupportPublicContext
{
    protected array $publicContext = [];

    /**
     * Pass a piece of information through the exception's client context.
     * The information is considered public and is intended to be conveyed to the client.
     *
     * Optionally specify a key to the client context.
     *
     * Passing `null` with a key will remove the information under the key.
     */
    public function pass(mixed $value, ?string $key = null): self
    {
        if ($value === null) {
            if ($key !== null) {
                unset($this->publicContext[$key]);
            }
            return $this;
        }
        if ($key === null) {
            $this->publicContext[] = $value;
        } else {
            $this->publicContext[$key] = $value;
        }
        return $this;
    }

    /**
     * Get the public context intended for client-facing error renderers.
     */
    public function publicContext(): array
    {
        return $this->publicContext;
    }

    /**
     * Completely replace current client-facing context with given one.
     */
    public function replacePublicContext(array $context): self
    {
        $this->publicContext = $context;
        return $this;
    }
}
