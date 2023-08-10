<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Interface for "tagging" exceptions.
 * Tags may be used by exception-handlers and SHOULD NOT be considered public.
 */
interface SupportsTagging
{
    /**
     * Pin a tag (label) to the exception.
     * Tags SHOULD be considered internal.
     *
     * Both plain string tags and key-tag pairs SHOULD be supported.
     */
    public function tag(string $tag, ?string $key = null): self;

    /**
     * Return a list of tags.
     *
     * @return string[]
     */
    public function tags(): array;

    /**
     * Replace all current tags with given ones.
     */
    public function replaceTags(array $tags): self;
}
