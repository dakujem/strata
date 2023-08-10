# Strata

Contracts for layered exception handling mechanisms.


## Rich context enabled exceptions

Example of conveying information to clients and adding meta-data for developers:

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
        ], 'input');
}
```

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
    ->tag('serious', 'severity')
;
```

The meta-data can be used by server-side error handlers to report detailed data,
not just a message.

The client-facing data can be used by client apps and displayed to end users
or processed programmatically.


## Contracts

Contracts for automatic error handling, especially useful for HTTP APIs:

- `IndicatesClientFault` 4xx
- `IndicatesServerFault` 5xx


## Common HTTP 4xx exceptions

- 400 `BadRequest`
- 404 `NotFound`
- 403 `Forbidden`
- 401 `Unauthorized`
- 409 `Conflict`
- 422 `UnprocessableContent`

See the [HTTP status reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status) for more information.


## Build your own

Contracts and traits allow and encourage developers
to come up with their own exceptions for specific use-cases easily.
