<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Interface for carrying "internal explanation" by exceptions.
 *
 * Internal explanation SHOULD NOT be exposed to clients
 * and SHOULD be used for internal purposes only (reporting, debugging, logging, analytics, etc.).
 */
interface SupportsInternalExplanation
{
    /**
     * Add a human-readable explanation for internal purposes (reporting, logging, debugging, etc.).
     */
    public function explain(?string $explanation): self;

    /**
     * Retrieve the internal explanation, if there's any.
     */
    public function explanation(): ?string;
}
