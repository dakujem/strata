<?php

declare(strict_types=1);

namespace Dakujem\Test;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/tests.php';

use Dakujem\Strata\Contracts\IndicatesClientFault;
use Dakujem\Strata\Contracts\IndicatesServerFault;
use Dakujem\Strata\Contracts\IndicatesThirdPartyFault;
use Dakujem\Strata\Http\HttpClientContextStrata;
use Dakujem\Strata\Http\HttpServerContextStrata;
use Dakujem\Strata\Support\SuggestsErrorMessage;
use Dakujem\Strata\Support\SuggestsHttpStatus;
use Dakujem\Strata\Support\SupportsContextStrata;
use Throwable;


function testCustomException(
    Throwable $instance,
    array $interfaces,
    int $httpStatusCode,
    string $httpStatusMessage,
): void {
    testCompatibility($instance);
    testInterfaces(
        $instance,
        SupportsContextStrata::class,
        SuggestsHttpStatus::class,
        SuggestsErrorMessage::class,
        ...$interfaces,
    );

    // internals
    testExplanation($instance);
    testInternalContext($instance);
    testTagging($instance);

    // conveying
    testPublicContext($instance);

    // http status/message
    testHttpStatusCodeSuggestion($instance, $httpStatusCode);
    testErrorMessageSuggestion($instance, $httpStatusMessage);
}

class ServerFault extends \Exception implements
    SuggestsErrorMessage,
    SuggestsHttpStatus,
    SupportsContextStrata,
    IndicatesServerFault
{
    use HttpServerContextStrata;
}

class ThirdPartyFault extends \Exception implements
    SuggestsErrorMessage,
    SuggestsHttpStatus,
    SupportsContextStrata,
    IndicatesThirdPartyFault
{
    use HttpServerContextStrata;
}

class DefaultClientFault extends \Exception implements
    SuggestsErrorMessage,
    SuggestsHttpStatus,
    SupportsContextStrata,
    IndicatesClientFault
{
    use HttpClientContextStrata;
}

class GenericClientFault extends \Exception implements
    SuggestsErrorMessage,
    SuggestsHttpStatus,
    SupportsContextStrata,
    IndicatesClientFault
{
    use HttpClientContextStrata;

    public function getDefaultMessageToConvey(): string
    {
        // When no explicit message is passed to the convey method,
        // the internal exception message is conveyed (potentially EXPOSED to the client).
        return $this->getMessage();
    }
}

testCustomException(
    instance: new ServerFault(),
    interfaces: [IndicatesServerFault::class],
    httpStatusCode: 500,
    httpStatusMessage: 'Internal server error',
);

testCustomException(
    instance: new DefaultClientFault(),
    interfaces: [IndicatesClientFault::class],
    httpStatusCode: 400,
    httpStatusMessage: 'Bad request',
);

testCustomException(
    instance: new ThirdPartyFault(),
    interfaces: [IndicatesThirdPartyFault::class],
    httpStatusCode: 500,
    httpStatusMessage: 'Internal server error',
);


testPublicConveying(
    new ServerFault('Whatever happens, we do not want the clients see this message.'),
    fn(): string => 'A system error has occurred. We are sorry for the inconvenience.',
);
testPublicConveying(
    new ThirdPartyFault('Whatever happens, we do not want the clients see this message.'),
    fn(): string => 'A system error has occurred. We are sorry for the inconvenience.',
);

// Note: The only way to make `convey` method expose the internal exception message is via overriding the `getDefaultMessageToConvey` method by the exception.
testPublicConveying(
    new DefaultClientFault('Whatever happens, we do not want the clients see this message.'),
    fn(): string => 'A system error has occurred. We are sorry for the inconvenience.',
);

// Note: Conveying the internal message by overriding the `getDefaultMessageToConvey` method.
testPublicConveying(
    new GenericClientFault('Yeah, this should be the message for the client.'),
    fn(): string => 'Yeah, this should be the message for the client.',
);
