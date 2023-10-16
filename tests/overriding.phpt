<?php

declare(strict_types=1);

namespace Dakujem\Test;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/tests.php';

use Dakujem\Strata\Http\BadRequest;
use Dakujem\Strata\Http\Conflict;
use Dakujem\Strata\Http\Forbidden;
use Dakujem\Strata\Http\GenericClientHttpException;
use Dakujem\Strata\Http\ImATeapot;
use Dakujem\Strata\Http\NotFound;
use Dakujem\Strata\Http\Unauthorized;
use Dakujem\Strata\Http\UnprocessableContent;


// test static default message overriding

testErrorMessageSuggestion(new BadRequest(), BadRequest::DefaultErrorMessage);
testErrorMessageSuggestion(new GenericClientHttpException(), BadRequest::DefaultErrorMessage);
testErrorMessageSuggestion(new Unauthorized(), Unauthorized::DefaultErrorMessage);
testErrorMessageSuggestion(new Forbidden(), Forbidden::DefaultErrorMessage);
testErrorMessageSuggestion(new NotFound(), NotFound::DefaultErrorMessage);
testErrorMessageSuggestion(new Conflict(), Conflict::DefaultErrorMessage);
testErrorMessageSuggestion(new ImATeapot(), ImATeapot::DefaultErrorMessage);
testErrorMessageSuggestion(new UnprocessableContent(), UnprocessableContent::DefaultErrorMessage);

BadRequest::$suggestedMessage = 'Very bad request indeed';
Unauthorized::$suggestedMessage = 'Sorry';
Forbidden::$suggestedMessage = 'Nope';
NotFound::$suggestedMessage = 'Not there';
Conflict::$suggestedMessage = 'Why would you do that?';
ImATeapot::$suggestedMessage = 'Hidden gem';
UnprocessableContent::$suggestedMessage = 'Gibberish';

testErrorMessageSuggestion(new BadRequest(), 'Very bad request indeed');
testErrorMessageSuggestion(new GenericClientHttpException(), 'Very bad request indeed');
testErrorMessageSuggestion(new Unauthorized(), 'Sorry');
testErrorMessageSuggestion(new Forbidden(), 'Nope');
testErrorMessageSuggestion(new NotFound(), 'Not there');
testErrorMessageSuggestion(new Conflict(), 'Why would you do that?');
testErrorMessageSuggestion(new ImATeapot(), 'Hidden gem');
testErrorMessageSuggestion(new UnprocessableContent(), 'Gibberish');

// reset
BadRequest::$suggestedMessage = BadRequest::DefaultErrorMessage;
GenericClientHttpException::$suggestedMessage = BadRequest::DefaultErrorMessage;
Unauthorized::$suggestedMessage = Unauthorized::DefaultErrorMessage;
Forbidden::$suggestedMessage = Forbidden::DefaultErrorMessage;
NotFound::$suggestedMessage = NotFound::DefaultErrorMessage;
Conflict::$suggestedMessage = Conflict::DefaultErrorMessage;
ImATeapot::$suggestedMessage = ImATeapot::DefaultErrorMessage;
UnprocessableContent::$suggestedMessage = UnprocessableContent::DefaultErrorMessage;

testErrorMessageSuggestion(new BadRequest(), BadRequest::DefaultErrorMessage);
testErrorMessageSuggestion(new GenericClientHttpException(), BadRequest::DefaultErrorMessage);
testErrorMessageSuggestion(new Unauthorized(), Unauthorized::DefaultErrorMessage);
testErrorMessageSuggestion(new Forbidden(), Forbidden::DefaultErrorMessage);
testErrorMessageSuggestion(new NotFound(), NotFound::DefaultErrorMessage);
testErrorMessageSuggestion(new Conflict(), Conflict::DefaultErrorMessage);
testErrorMessageSuggestion(new ImATeapot(), ImATeapot::DefaultErrorMessage);
testErrorMessageSuggestion(new UnprocessableContent(), UnprocessableContent::DefaultErrorMessage);
