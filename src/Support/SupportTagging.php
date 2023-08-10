<?php

declare(strict_types=1);

namespace Dakujem\Strata\Support;

/**
 * Trait implementing the "tagging" mechanism for exceptions.
 *
 * Tags SHOULD be considered internal information and SHOULD NOT be exposed to clients.
 *
 * The trait implements interface:
 * @see SupportsTagging
 */
trait SupportTagging
{
    protected array $tags = [];

    /**
     * Pin a tag (label) to the exception.
     * The tag is considered internal.
     *
     * Both plain string tags and key-tag pairs are supported.
     */
    public function tag(string $tag, ?string $key = null): self
    {
        if ($key !== null) {
            $this->tags[$key] = $tag;
        } else {
            $this->tags[] = $tag;
        }
        return $this;
    }

    /**
     * Return the list of tags.
     */
    public function tags(): array
    {
        return $this->tags;
    }

    /**
     * Replace current tags with given ones.
     */
    public function replaceTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }
}
