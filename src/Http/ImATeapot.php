<?php

declare(strict_types=1);

namespace Dakujem\Strata\Http;

use Dakujem\Strata\Contracts\IndicatesGoodMood;

/**
 * 418 I'm a teapot
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/418
 */
class ImATeapot extends ClientHttpExceptionAbstract implements IndicatesGoodMood
{
    public static string $suggestedMessage = 'I\'m a teapot';

    public function suggestStatusCode(): int
    {
        return 418; // 418 I'm a teapot
    }
}
