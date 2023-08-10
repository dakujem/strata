<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Interface for carrying "internal explanation" by exceptions.
 *
 * Internal explanation SHOULD NOT be exposed to clients
 * and SHOULD be used for internal purposes only (debugging, logging, analytics, etc.).
 */
interface SupportsInternalExplanation
{
    /**
     * Add an explanation for internal purposes (logging, debugging, etc.).
     */
    public function explain(?string $explanation): self;

    /**
     * Retrieve the internal explanation, if any.
     */
    public function explanation(): ?string;
}
