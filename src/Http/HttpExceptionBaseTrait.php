<?php

declare(strict_types=1);

namespace Dakujem\Strata\Http;

/**
 * @deprecated BC only
 */
trait HttpExceptionBaseTrait
{
    use HttpServerContextStrata;

    public static string $suggestedMessage = 'Bad request';

    public function suggestErrorMessage(): string
    {
        return static::$suggestedMessage;
    }
}
