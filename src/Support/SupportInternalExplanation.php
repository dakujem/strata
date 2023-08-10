<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Trait implementing the mechanism for carrying "internal explanation" by exceptions.
 *
 * Internal explanation SHOULD NOT be exposed to clients
 * and SHOULD be used for internal purposes only (debugging, logging, analytics, etc.).
 *
 * The trait implements interface:
 * @see SupportsInternalExplanation
 */
trait SupportInternalExplanation
{
    protected ?string $explanation = null;

    /**
     * Add an explanation for internal purposes (logging, debugging, etc.).
     */
    public function explain(?string $explanation): self
    {
        $this->explanation = $explanation;
        return $this;
    }

    /**
     * Retrieve the internal explanation, if any.
     */
    public function explanation(): ?string
    {
        return $this->explanation;
    }
}
