<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

use JsonSerializable;

/**
 * Encapsulates error context for API response coherence.
 * This object is intended for serialization.
 */
class ErrorContainer implements JsonSerializable
{
    /**
     * The error message for humans.
     * May be localized.
     * @var string|null
     */
    public ?string $message;
    /**
     * Pointer to an element or component that caused the error.
     * @var string|null
     */
    public ?string $source;
    /**
     * Human-readable error details.
     * May be localized.
     * @var string|null
     */
    public ?string $detail;
    /**
     * Any metadata, tech details.
     * @var mixed
     */
    public mixed $meta;
    /**
     * HTTP status code, used for multi-status error responses.
     * @var int|null
     */
    public ?int $status;
    /**
     * Application-specific error code.
     * @var string|null
     */
    public ?string $code;

    public function __construct(
        ?string $message = null,
        ?string $source = null,
        ?string $detail = null,
        mixed $meta = null,
        ?int $status = null,
        ?string $code = null,
    ) {
        $this->message = $message;
        $this->source = $source;
        $this->detail = $detail;
        $this->meta = $meta;
        $this->status = $status;
        $this->code = $code;
    }

    public function jsonSerialize(): array
    {
        return array_filter(
            [
                'message' => $this->message,
                'detail' => $this->detail,
                'source' => $this->source,
                'code' => $this->code,
                'status' => $this->status,
                'meta' => $this->meta,
            ],
            fn($v): bool => $v !== null,
        );
    }
}
