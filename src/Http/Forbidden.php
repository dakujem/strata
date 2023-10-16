<?php

declare(strict_types=1);

namespace Dakujem\Strata\Http;

use Dakujem\Strata\Contracts\IndicatesAuthorizationFault;

/**
 * 403 Forbidden
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/403
 */
class Forbidden extends ClientHttpExceptionAbstract implements IndicatesAuthorizationFault
{
    public const DefaultErrorMessage = 'Forbidden';

    public static string $suggestedMessage = self::DefaultErrorMessage;

    public function suggestStatusCode(): int
    {
        return 403; // 403 Forbidden
    }
}
