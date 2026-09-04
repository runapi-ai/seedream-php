<?php

declare(strict_types=1);

namespace RunApi\Seedream\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/**
 * A decomposed layer with its position and metadata.
 */
readonly class Layer extends BaseModel
{
    /**
     * @param array<string, mixed> $raw
     */
    public function __construct(
        public string $url,
        public int $zIndex,
        public ?BoundingBox $boundingBox = null,
        public ?string $name = null,
        public ?string $description = null,
        array $raw = [],
    ) {
        parent::__construct($raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        $bbox = $raw['bounding_box'] ?? null;
        return new self(
            url: Payload::string($raw, 'url'),
            zIndex: Payload::int($raw, 'z_index'),
            boundingBox: is_array($bbox) ? BoundingBox::fromArray($bbox) : null,
            name: $raw['name'] ?? null,
            description: $raw['description'] ?? null,
            raw: $raw,
        );
    }
}
