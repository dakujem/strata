<?php

declare(strict_types=1);

namespace Dakujem\Test;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/tests.php';

use Dakujem\Strata\Support\ErrorContainer;
use ReflectionClass;
use Tester\Assert;


// not much to test here, but we'll do that anyway
$ec = new ErrorContainer(
    message: 'An important message.',
    source: 'input.email',
    detail: 'A description for humans.',
    meta: ['any' => 'metadata'],
    status: 400,
    code: 'crashed...somehow',
);

Assert::same('An important message.', $ec->message);
Assert::same('input.email', $ec->source);
Assert::same('A description for humans.', $ec->detail);
Assert::same(['any' => 'metadata'], $ec->meta);
Assert::same(400, $ec->status);
Assert::same('crashed...somehow', $ec->code);

Assert::same([
    'message' => 'An important message.',
    'detail' => 'A description for humans.',
    'source' => 'input.email',
    'code' => 'crashed...somehow',
    'status' => 400,
    'meta' => ['any' => 'metadata'],
], $ec->jsonSerialize());

// test that all public props are present in json
$rf = new ReflectionClass(ErrorContainer::class);
Assert::same(count($rf->getProperties()), count($ec->jsonSerialize()));

// test that null values are omitted when serializing
$ec2 = new ErrorContainer('only the message is set');
Assert::same(1, count($ec2->jsonSerialize()));
$ec3 = new ErrorContainer(meta: null);
Assert::same([], $ec3->jsonSerialize());

// but string (even empty) are not
$ec4 = new ErrorContainer(message: '', detail: '', code: null);
Assert::same(2, count($ec4->jsonSerialize()));
