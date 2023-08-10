<?php

declare(strict_types=1);

namespace Dakujem\Strata\Http;

use Dakujem\Strata\Contracts\IndicatesInvalidInput;

/**
 * 422 Unprocessable content
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422
 */
class UnprocessableContent extends ClientHttpExceptionAbstract implements IndicatesInvalidInput
{
    public static string $suggestedMessage = 'Unprocessable content';

    public function suggestStatusCode(): int
    {
        return 422; // 422 Unprocessable content
    }
}
