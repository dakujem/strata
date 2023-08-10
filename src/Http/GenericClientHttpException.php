<?php

declare(strict_types=1);

namespace Dakujem\Strata\Http;

/**
 * Generic HTTP 4xx exception with rich context support.
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
 */
class GenericClientHttpException extends ClientHttpExceptionAbstract
{
    protected ?int $httpStatusCode = null;

    public function suggestStatusCode(): int
    {
        return $this->httpStatusCode ?? $this->defaultStatusCode();
    }

    public function setHttpStatus(?int $code): self
    {
        $this->httpStatusCode = $code;
        return $this;
    }

    protected function defaultStatusCode(): int
    {
        return 400; // 400 Bad request (default)
    }
}
