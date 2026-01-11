<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * This trait allows an Exception to convey error details to the clients.
 *
 * It implements interface:
 * @see SupportsPublicConveying
 */
trait SupportPublicConveying
{
    /**
     * Conveys error message and details,
     * to be passed to the client.
     *
     * NOTE:
     *   If `null` is passed as the $message argument,
     *   the internal message of the exception MAY be conveyed
     *   and potentially EXPOSED to the client!
     *
     * Internally creates the ErrorContainer object:
     * @see ErrorContainer
     */
    public function convey(
        ?string $message = null,
        ?string $source = null,
        ?string $detail = null,
        mixed $meta = null,
        ?int $status = null,
        ?string $code = null,
    ): static {
        return $this->pass(new ErrorContainer(
            message: $message ?? $this->getDefaultMessageToConvey(),
            source: $source,
            detail: $detail,
            meta: $meta,
            status: $status,
            code: $code,
        ));
    }

    /**
     * A default message to be conveyed in case none is passed to the `convey` method.
     *
     * WARNING
     *   If the internal message of the exception is returned as the fault message,
     *   private tech data may leak. Think twice if that is a good idea.
     */
    public function getDefaultMessageToConvey(): string
    {
        // Use this for cases where you are SURE you can reveal the exception internal messages.
        // return $this->getMessage();

        return 'A system error has occurred. We are sorry for the inconvenience.';
    }

    abstract public function pass(mixed $value, string|int|null $key = null): self;
}
