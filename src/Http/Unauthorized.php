<?php

declare(strict_types=1);

namespace Dakujem\Strata\Http;

use Dakujem\Strata\Contracts\IndicatesAuthenticationFault;

/**
 * 401 Unauthorized
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/401
 */
class Unauthorized extends ClientHttpExceptionAbstract implements IndicatesAuthenticationFault
{
    public const DefaultErrorMessage = 'Unauthorized';

    public static string $suggestedMessage = self::DefaultErrorMessage;

    public function suggestStatusCode(): int
    {
        return 401; // 401 Unauthorized
    }
}
