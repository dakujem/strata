<?php

declare(strict_types=1);

namespace Dakujem\Test;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/tests.php';

use Dakujem\Strata\Contracts\IndicatesAuthenticationFault;
use Dakujem\Strata\Contracts\IndicatesAuthorizationFault;
use Dakujem\Strata\Contracts\IndicatesClientFault;
use Dakujem\Strata\Contracts\IndicatesConflict;
use Dakujem\Strata\Contracts\IndicatesGoodMood;
use Dakujem\Strata\Contracts\IndicatesInvalidInput;
use Dakujem\Strata\Contracts\IndicatesMissingResource;
use Dakujem\Strata\Http\BadRequest;
use Dakujem\Strata\Http\Conflict;
use Dakujem\Strata\Http\Forbidden;
use Dakujem\Strata\Http\GenericClientHttpException;
use Dakujem\Strata\Http\ImATeapot;
use Dakujem\Strata\Http\NotFound;
use Dakujem\Strata\Http\Unauthorized;
use Dakujem\Strata\Http\UnprocessableContent;
use Dakujem\Strata\Support\SuggestsErrorMessage;
use Dakujem\Strata\Support\SuggestsHttpStatus;
use Dakujem\Strata\Support\SupportsContextStrata;
use Throwable;


function testHttpException(
    Throwable $instance,
    array $interfaces,
    int $httpStatusCode,
    string $httpStatusMessage,
    ?callable $defaultConveyMessageGetter = null,
): void {
    testCompatibility($instance);

    testInterfaces(
        $instance,
        SupportsContextStrata::class,
        SuggestsHttpStatus::class,
        SuggestsErrorMessage::class,
        IndicatesClientFault::class,
        ...$interfaces,
    );

    // internals
    testExplanation($instance);
    testInternalContext($instance);
    testTagging($instance);

    // conveying
    testPublicContext($instance);
    testPublicConveying(
        $instance,
        $defaultConveyMessageGetter ?? fn(Throwable $e): string => $e->getMessage(),
    );

    // http status/message
    testHttpStatusCodeSuggestion($instance, $httpStatusCode);
    testErrorMessageSuggestion($instance, $httpStatusMessage);
}

// Here we test the HTTP exceptions:
testHttpException(
    instance: new BadRequest(),
    interfaces: [],
    httpStatusCode: 400,
    httpStatusMessage: 'Bad request',
);

testHttpException(
    instance: new Unauthorized(),
    interfaces: [IndicatesAuthenticationFault::class],
    httpStatusCode: 401,
    httpStatusMessage: 'Unauthorized',
);

testHttpException(
    instance: new Forbidden(),
    interfaces: [IndicatesAuthorizationFault::class],
    httpStatusCode: 403,
    httpStatusMessage: 'Forbidden',
);

testHttpException(
    instance: new NotFound(),
    interfaces: [IndicatesMissingResource::class],
    httpStatusCode: 404,
    httpStatusMessage: 'Not found',
);

testHttpException(
    instance: new Conflict(),
    interfaces: [IndicatesConflict::class],
    httpStatusCode: 409,
    httpStatusMessage: 'Conflict',
);

testHttpException(
    instance: new UnprocessableContent(),
    interfaces: [IndicatesInvalidInput::class],
    httpStatusCode: 422,
    httpStatusMessage: 'Unprocessable content',
);

testHttpException(
    instance: new ImATeapot(),
    interfaces: [IndicatesGoodMood::class],
    httpStatusCode: 418,
    httpStatusMessage: 'I\'m a teapot',
);


testHttpException(
    instance: new GenericClientHttpException(),
    interfaces: [],
    httpStatusCode: 400,
    httpStatusMessage: 'Bad request',
);
testHttpException(
    instance: new GenericClientHttpException('Oops!', 499),
    interfaces: [],
    httpStatusCode: 400,
    httpStatusMessage: 'Bad request',
    defaultConveyMessageGetter: fn() => 'Oops!',
);
testHttpException(
    instance: (new GenericClientHttpException('Oops!'))->setHttpStatus(499),
    interfaces: [],
    httpStatusCode: 499,
    httpStatusMessage: 'Bad request',
    defaultConveyMessageGetter: fn() => 'Oops!',
);

