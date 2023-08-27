<?php

declare(strict_types=1);

namespace Dakujem\Strata\Http;

use Dakujem\Strata\Contracts\IndicatesClientFault;
use Dakujem\Strata\Support\SupportsContextStrata;
use Dakujem\Strata\Support\SuggestsErrorMessage;
use Dakujem\Strata\Support\SuggestsHttpStatus;
use Dakujem\Strata\Support\SupportsPublicConveying;

/**
 * HTTP 4xx exception abstract for exceptions with rich context support.
 *
 * WARNING:
 *   Causes the convey mechanism to be able to EXPOSE the exception's internal message!
 *   Only use this abstract where such behaviour is expected!
 */
abstract class ClientHttpExceptionAbstract extends \RuntimeException implements
    IndicatesClientFault,
    SupportsContextStrata,
    SuggestsHttpStatus,
    SuggestsErrorMessage
{
    use HttpExceptionBaseTrait;

    public function suggestStatusCode(): int
    {
        return 400; // 400 Bad Request
    }

    /**
     * WARNING
     *   This implementation causes calls to the convey method without explicit $message argument
     *   to convey the exception's internal message.
     *   Only use this abstract where such behaviour is expected!
     *
     * @see SupportsPublicConveying::convey()
     */
    public function getDefaultMessageToConvey(): string
    {
        // When no explicit message is passed to the convey method,
        // the internal exception message is conveyed (potentially EXPOSED to the client).
        return $this->getMessage();
    }
}
