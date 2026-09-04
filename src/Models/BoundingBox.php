<?php

declare(strict_types=1);

namespace RunApi\Seedream\Models;

use RunApi\Core\Models\BaseModel;

/**
 * Bounding box describing a layer's position within the base image.
 */
readonly class BoundingBox extends BaseModel
{
    /**
     * @param list<int>|null $absolute Pixel coordinates [left, top, right, bottom].
     * @param list<int>|null $normalized Normalized coordinates.
     * @param array<string, mixed> $raw
     */
    public function __construct(public ?array $absolute = null, public ?array $normalized = null, array $raw = [])
    {
        parent::__construct($raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        return new self(
            absolute: isset($raw['absolute']) && is_array($raw['absolute']) ? array_map(intval(...), $raw['absolute']) : null,
            normalized: isset($raw['normalized']) && is_array($raw['normalized']) ? array_map(intval(...), $raw['normalized']) : null,
            raw: $raw,
        );
    }
}
