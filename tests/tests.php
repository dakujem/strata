<?php

declare(strict_types=1);

use Dakujem\Strata\Support\ErrorContainer;
use Dakujem\Strata\Support\SuggestsErrorMessage;
use Dakujem\Strata\Support\SuggestsHttpStatus;
use Dakujem\Strata\Support\SupportsInternalContext;
use Dakujem\Strata\Support\SupportsInternalExplanation;
use Dakujem\Strata\Support\SupportsPublicContext;
use Dakujem\Strata\Support\SupportsPublicConveying;
use Dakujem\Strata\Support\SupportsTagging;
use Tester\Assert;


/**
 * @param class-string ...$interfaces
 */
function testInterfaces(Throwable $e, string ...$interfaces): void
{
    foreach ($interfaces as $type) {
        Assert::type($type, $e);
    }
}

/**
 * Sanity and compatibility test.
 */
function testCompatibility(Throwable $e): void
{
    $class = $e::class;
    $previous = new \Exception();
    $instance = new $class(
        'exception message',
        123456783,
        $previous,
    );
    Assert::type(Throwable::class, $instance);
    Assert::same('exception message', $instance->getMessage());
    Assert::same(123456783, $instance->getCode());
    Assert::same($previous, $instance->getPrevious());
}

function testExplanation(Throwable $e): void
{
    Assert::type(SupportsInternalExplanation::class, $e);

    // No default explanation.
    Assert::same(null, $e->explanation());

    // Set/Get.
    $e->explain('Well explained indeed.');
    Assert::same('Well explained indeed.', $e->explanation());

    // Nope.
    Assert::throws(fn() => $e->explain(1234), TypeError::class);
    Assert::throws(fn() => $e->explain([]), TypeError::class);
    Assert::throws(fn() => $e->explain(new stdClass()), TypeError::class);

    // Reset.
    $e->explain(null);
    Assert::same(null, $e->explanation());
}

/**
 * @param SupportsInternalContext&Throwable $e
 */
function testInternalContext(Throwable $e): void
{
    _testContext($e, SupportsInternalContext::class, 'pin', 'context', 'replaceContext');
}

/**
 * @param SupportsPublicContext&Throwable $e
 */
function testPublicContext(Throwable $e): void
{
    _testContext($e, SupportsPublicContext::class, 'pass', 'publicContext', 'replacePublicContext');
}

function _testContext(
    Throwable $e,
    string $interface,
    string $insertMethod,
    string $retrieveMethod,
    string $overwriteMethod
): void {
    Assert::type($interface, $e);

    Assert::same([], $e->{$retrieveMethod}());

    $e->{$insertMethod}('some value');
    Assert::same(['some value'], $e->{$retrieveMethod}());

    // pinning a null has no effect
    $e->{$insertMethod}(null);
    Assert::same(['some value'], $e->{$retrieveMethod}());

    // pinning a null under a specific key removes the key
    $e->{$insertMethod}(null, '0');
    Assert::same([], $e->{$retrieveMethod}());

    $e->{$insertMethod}('value-1', 'key-1');
    $e->{$insertMethod}('value-2', 'key-2');
    $e->{$insertMethod}('value-3', 'key-3');
    $e->{$insertMethod}('value-42', 42);
    Assert::same(['key-1' => 'value-1', 'key-2' => 'value-2', 'key-3' => 'value-3', 42 => 'value-42'], $e->{$retrieveMethod}());

    // removing a value under the key with null
    $e->{$insertMethod}(null, 'key-2');
    Assert::same(['key-1' => 'value-1', 'key-3' => 'value-3', 42 => 'value-42'], $e->{$retrieveMethod}());

    // reset
    $e->{$overwriteMethod}([]);
    Assert::same([], $e->{$retrieveMethod}());

    // It is possible to pin value of any type.
    $e->{$insertMethod}(1234);
    $e->{$insertMethod}(['foo' => 'bar'], 'an array');
    $e->{$insertMethod}($e, 'self');
    Assert::same([
        1234,
        'an array' => ['foo' => 'bar'],
        'self' => $e,
    ], $e->{$retrieveMethod}());

    // reset
    $e->{$overwriteMethod}([]);
    Assert::same([], $e->{$retrieveMethod}());
}

/**
 * @param SupportsTagging&Throwable $e
 */
function testTagging(Throwable $e): void
{
    Assert::type(SupportsTagging::class, $e);

    Assert::same([], $e->tags());

    $e->tag('default tag');
    Assert::same(['default tag'], $e->tags());
    $e->tag('another tag');
    Assert::same(['default tag', 'another tag'], $e->tags());

    // reset
    $e->replaceTags([]);
    Assert::same([], $e->tags());

    $e->tag('value-1', 'key-1');
    $e->tag('value-2', 'key-2');
    $e->tag('value-3', 'key-3');
    $e->tag('value-42', 42);
    Assert::same(['key-1' => 'value-1', 'key-2' => 'value-2', 'key-3' => 'value-3', 42 => 'value-42'], $e->tags());

    Assert::throws(fn() => $e->tag(null), TypeError::class);
    Assert::throws(fn() => $e->tag(1234), TypeError::class);
    Assert::throws(fn() => $e->replaceTags([12345]), TypeError::class);

    // reset
    $e->replaceTags([]);
    Assert::same([], $e->tags());
}

/**
 * @param SupportsPublicConveying&SupportsPublicContext&Throwable $e
 */
function testPublicConveying(Throwable $e, callable $defaultConveyingMessageGetter): void
{
    Assert::type(SupportsPublicConveying::class, $e);
    Assert::type(SupportsPublicContext::class, $e);

    // first call to convey detailed data
    $e->convey(
        'An important message.',
        'input.email',
        'A description for humans.',
        ['any' => 'metadata'],
        400,
        'crashed...somehow',
    );
    // second call without parameters (should use defaults)
    $e->convey();

    $context = $e->publicContext();
    Assert::same(2, count($context));

    /** @var ErrorContainer $container */
    $container = $context[0] ?? null;
    Assert::type(ErrorContainer::class, $container);
    Assert::same('An important message.', $container->message);
    Assert::same('input.email', $container->source);
    Assert::same('A description for humans.', $container->detail);
    Assert::same(['any' => 'metadata'], $container->meta);
    Assert::same(400, $container->status);
    Assert::same('crashed...somehow', $container->code);

    // here we test that without the parameters to `convey` method, the container receives the expected default message and nothing else
    $defaultContainer = $context[1] ?? null;
    Assert::type(ErrorContainer::class, $defaultContainer);
    Assert::same($defaultConveyingMessageGetter($e), $defaultContainer->message);
    Assert::same(null, $defaultContainer->source);
    Assert::same(null, $defaultContainer->detail);
    Assert::same(null, $defaultContainer->meta);
    Assert::same(null, $defaultContainer->status);
    Assert::same(null, $defaultContainer->code);
}

function testDefaultConveyingMessage(Throwable $e, string $expectedMessage):void{
    Assert::type(SupportsPublicConveying::class, $e);
    Assert::type(SupportsPublicContext::class, $e);



}


/**
 * @param SuggestsHttpStatus&Throwable $e
 */
function testHttpStatusCodeSuggestion(Throwable $e, int $code): void
{
    Assert::type(SuggestsHttpStatus::class, $e);

    Assert::same($code, $e->suggestStatusCode());
}

/**
 * @param SuggestsErrorMessage&Throwable $e
 */
function testErrorMessageSuggestion(Throwable $e, string $message): void
{
    Assert::type(SuggestsErrorMessage::class, $e);

    Assert::same($message, $e->suggestErrorMessage());
}
