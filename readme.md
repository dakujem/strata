# Strata

[![Test Suite](https://github.com/dakujem/strata/actions/workflows/php-test.yml/badge.svg)](https://github.com/dakujem/strata/actions/workflows/php-test.yml)
[![Coverage Status](https://coveralls.io/repos/github/dakujem/strata/badge.svg?branch=tests)](https://coveralls.io/github/dakujem/strata?branch=tests)

Contracts and implementations for layered exception handling mechanisms.

>
> 💿 `composer require dakujem/strata`
>


## TL;DR

Native PHP exceptions allow to carry a string message and an integer code.

👉 **That's not enough.** 👈

Embrace context-enabled exceptions.


## Context-enabled exceptions

- convey information to clients
- carry meta-data for developers

HTTP client error example:
```php
use Dakujem\Strata\Http\UnprocessableContent;

if(!isEmail($input['email'])){
    // HTTP 422
    throw (new UnprocessableContent('Invalid e-mail address.'))
        // Convey messages to clients with localization support and metadata:
        ->convey(
            message: __('Please enter a valid e-mail address.'),
            source: 'input.email',
        )
        // Add details for developers to be reported or logged:
        ->pin([
            'name' => $input['name'],
            'email' => $input['email'],
        ], key: 'input');
}
```

Internal logic fault:
```php
use Dakujem\Strata\LogicException;

throw (new LogicException('Invalid value of Something.'))
    // Convey messages and meta-data to clients, humans and apps alike:
    ->convey(
        message: __('We are sorry, we encountered an issue with Something and are unable to fulfil your request.'),
        source: 'something.or.other',
        description: __('We are already fixing the issue, please try again later.'),
        meta: ['param' => 42]
    )
    // Add details for developers to be reported or logged:
    ->explain('Fellow developers, this issue is caused by an invalid value: ' . $someValue)
    ->pin(['value' => $someValue, 'severity' => 'serious'], 'additional context')
    ->pin(['other' => $value], 'other context')
    ->pin('anything')
    ->tag('something')
    ->tag(tag: 'serious', key: 'severity')
;
```

The meta-data can be used by server-side error handlers to report detailed data.

The client-facing data can be used by client apps and displayed to end users
or processed programmatically.  
This is especially useful for API+client app architecture (JS widgets, PWAs, mobile apps, etc.).


## Processing context using error handlers

Typically, your apps will have a global error handling mechanism in place,
be it a try-catch block in your bootstrap, error handling middleware or a native error handler.

Something along these lines:
```php
try {
    process_request(Request::fromGlobals());
} catch (Throwable $e) {
    handle_exception($e);
}
```

In Laravel, for example, it's usually the [`App\Exceptions\Handler` class that handles the exceptions](https://laravel.com/docs/10.x/errors#the-exception-handler).  
In Slim, [middleware is used for error handling](http://dev.slimframework.com/docs/v4/middleware/error-handling.html).  
[Symfony will also catch all exceptions and errors](https://symfony.com/doc/current/controller/error_pages.html), and it is possible to customize the handling logic.

All these rely on developers to come up with specific exceptions that carry specific context, like Guzzle's [`RequestException`](https://github.com/guzzle/guzzle/blob/7.8/src/Exception/RequestException.php) carries HTTP request and response.  
And that's fine. By all means, do create specific exceptions for specific purposes.  
Strata will help enable context support by implementing a single interface (`SupportsContextStrata`) and using a single trait (`ContextStrata`).

There's also times when one does not need specific exceptions,
but still wishes to pass contextual data to global error handlers.  
Strata provide exceptions for common HTTP responses.

To add context for reporting or logging or to add information targeted at front-end consumers,
strata provide both the interfaces and the implementations.


### Examples

Example of an exception handler in Laravel (JSON API):
```php
namespace App\Exceptions;

use Dakujem\Strata\Contracts\IndicatesAuthenticationFault;
use Dakujem\Strata\Contracts\IndicatesAuthorizationFault;
use Dakujem\Strata\Contracts\IndicatesClientFault;
use Dakujem\Strata\Contracts\IndicatesConflict;
use Dakujem\Strata\Contracts\IndicatesInvalidInput;
use Dakujem\Strata\Support\ErrorContainer;
use Dakujem\Strata\Support\SuggestsHttpStatus;
use Dakujem\Strata\Support\SupportsPublicContext;

class Handler extends ExceptionHandler
{
    protected function prepareJsonResponse($request, Throwable $e)
    {
        $errors = $e instanceof SupportsPublicContext ? $e->publicContext() : null;
        $errors ??= [];

        if ($errors === []) {
            $message = $detail = null;
            // Public error message for clients:
            if ($e instanceof SuggestsErrorMessage) {
                $message = $e->suggestErrorMessage();
            }
            // Laravel/Symfony HTTP exception
            if ($e instanceof HttpExceptionInterface) {
                $message = $e->getMessage();
            }
            // Slim HTTP exception
            if ($e instanceof HttpException) {
                $message = $e->getTitle();
                $detail = $e->getDescription();
            }
            $errors[] = new ErrorContainer(
                message: $message,
                detail: $detail,
            );
        }

        // Status code
        $code = 500;
        if ($e instanceof SuggestsHttpStatus) {
            $code = $e->suggestStatusCode();
        } elseif ($e instanceof IndicatesInvalidInput) {
            $code = 422; // 422 Unprocessable Content
        } elseif ($e instanceof IndicatesConflict) {
            $code = 409; // 409 Conflict
        } elseif ($e instanceof IndicatesAuthorizationFault) {
            $code = 403; // 403 Forbidden
        } elseif ($e instanceof IndicatesAuthenticationFault) {
            $code = 401; // 401 Unauthorized
        } elseif ($e instanceof IndicatesClientFault) {
            $code = 400; // 400 Bad Request
        } elseif ($e instanceof HttpExceptionInterface) {
            // Laravel/Symfony HTTP exceptions
            $code = $e->getStatusCode();
        } elseif ($e instanceof ValidationException) {
            $code = $e->status; // 422 Unprocessable Content (default)
        } elseif ($e instanceof HttpException) {
            // Slim HTTP exception
            $code = $e->getCode();
        }

        return response()
            ->json(
                data: [
                    'errors' => $errors,
                ],
            )
            ->withStatus(
                $code,
            );
    }
}
```

Example of processing the internal context for improved [Sentry](https://sentry.io/) reports:
```php
use Dakujem\Strata\Support\SupportsInternalContext;
use Dakujem\Strata\Support\SupportsInternalExplanation;
use Dakujem\Strata\Support\SupportsTagging;
use Sentry\Event;
use Sentry\EventHint;
use Sentry\State\HubInterface;
use Sentry\State\Scope;

function reportException(Throwable $e)
{
    $hub = Container::get(HubInterface::class);
    $hub->configureScope(function (Scope $scope) use ($e): void {
    
        // Internal context comprises all the pinned data (see `pin` method usage above)
        if ($e instanceof SupportsInternalContext) {
            foreach ($e->context() as $key => $value) {
                if (is_string($value) || is_numeric($value) || $value instanceof Stringable) {
                    $value = [
                        'value' => (string)$value,
                    ];
                }
                if (is_object($value)) {
                    $value = (array)$value;
                }
                if (is_array($value)) {
                    $scope->setContext(
                        is_numeric($key) ? 'context-' . $key : $key,
                        $value,
                    );
                }
            }
        }

        if ($e instanceof SupportsTagging) {
            foreach ($e->tags() as $key => $value) {
                // When tags have numeric keys, use tag:true format, otherwise use key:tag format.
                $scope->setTag(
                    is_numeric($key) ? $value : $key,
                    is_numeric($key) ? 'true' : $value,
                );
            }
        }

        if ($e instanceof SupportsInternalExplanation) {
            $scope->setContext(
                '_dev_',
                [
                    'explanation' => $e->explanation(),
                ],
            );
        }
    });

    $event = Event::createEvent();
    $event->setMessage($e->getMessage());
    $hint = new EventHint();
    $hint->exception = $e;

    $hub->captureEvent($event, $hint);
}
```


## Notable contracts for API design

Contracts for automatic error handling, especially useful for HTTP APIs:

- `IndicatesClientFault` 4xx
- `IndicatesServerFault` 5xx

```php
if($exception instanceof IndicatesClientFault){
    return convert_client_exception_to_4xx_response($exception);
}
if($exception instanceof IndicatesServerFault){
    report_server_fault($exception);
    return apologize_for_server_issue_with_5xx_status($exception);
}
```


## Common HTTP 4xx exceptions

This package provides exceptions for common 4xx HTTP status responses:

- 400 `BadRequest`
- 404 `NotFound`
- 403 `Forbidden`
- 401 `Unauthorized`
- 409 `Conflict`
- 422 `UnprocessableContent`

See the [HTTP status reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status) for more information.


## Build your own

The strata contracts and traits allow and encourage developers
to come up with their own exceptions for specific use-cases easily.

```php
class MySpecificException extends WhateverBaseException implements SupportsContextStrata
{
    use ContextStrata;

    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message ?? 'This is the default message for my specific exception.',
            $code ?? 0,
            $previous,
        );
    }
}
```

If only selected mechanisms are needed, strata are flexible enough.

Explore the provided support traits and interfaces.

| Interface                      | Implementation trait          | Mechanism                             | Method    |
|:-------------------------------|:------------------------------|:--------------------------------------|:----------|
| `SupportsInternalContext`      | `SupportInternalContext`      | generic internal metadata "pinning"   | `pin`     |
| `SupportsInternalExmplanation` | `SupportInternalExmplanation` | human-readable details for developers | `explain` |
| `SupportsTagging`              | `SupportTagging`              | machine-processable tags              | `tag`     |
| `SupportsPublicContext`        | `SupportPublicContext`        | public data for clients               | `pass`    |
| `SupportsPublicConveying`      | `SupportPublicConveying`      | coherent error details for clients    | `convey`  |


## Compatibility and support

This package requires **PHP `>=8.0`**. There are no other external dependencies.

> A backported version for PHP 7.4 exists: [`dakujem/strata74`](https://github.com/dakujem/strata74)
