<?php

declare(strict_types=1);

namespace Dakujem\Test;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/tests.php';

use Dakujem\Strata\Contracts\IndicatesServerFault;
use Dakujem\Strata\LogicException;
use Dakujem\Strata\RuntimeException;
use Dakujem\Strata\Support\SupportsContextStrata;


/**
 * @param class-string $class
 */
function testServerSideException(string $class): void
{
    $instance = new $class();

    testCompatibility($instance);
    testInterfaces($instance, SupportsContextStrata::class, IndicatesServerFault::class);

    testExplanation($instance);
    testInternalContext($instance);
    testTagging($instance);

    testPublicContext($instance);
    testPublicConveying($instance, fn(): string => $instance->getDefaultMessageToConvey());
}

// Here we test the 2 server-side exceptions:
testServerSideException(LogicException::class);
testServerSideException(RuntimeException::class);
