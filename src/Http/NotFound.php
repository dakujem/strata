<?php

declare(strict_types=1);

namespace Dakujem\Strata\Http;

use Dakujem\Strata\Contracts\IndicatesMissingResource;

/**
 * 404 Not found
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/404
 */
class NotFound extends ClientHttpExceptionAbstract implements IndicatesMissingResource
{
    public const DefaultErrorMessage = 'Not found';

    public static string $suggestedMessage = self::DefaultErrorMessage;

    public function suggestStatusCode(): int
    {
        return 404; // 404 Not found
    }
}
