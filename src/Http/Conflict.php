<?php

declare(strict_types=1);

namespace Dakujem\Strata\Http;

use Dakujem\Strata\Contracts\IndicatesConflict;

/**
 * 409 Conflict
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/409
 */
class Conflict extends ClientHttpExceptionAbstract implements IndicatesConflict
{
    public const DefaultErrorMessage = 'Conflict';

    public static string $suggestedMessage = self::DefaultErrorMessage;

    public function suggestStatusCode(): int
    {
        return 409; // 409 Conflict
    }
}
