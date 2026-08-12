<?php

declare(strict_types=1);

namespace RunApi\Seedream\Models;

use RunApi\Core\Models\TaskResponse;
use RunApi\Core\Support\Payload;

/** Async layer decomposition task response. */
readonly class DecomposeLayersResponse extends TaskResponse
{
    /** @param list<Image> $layers @param array<string, mixed> $raw */
    public function __construct(?string $id, string $status, ?string $error = null, public ?Image $baseImage = null, public array $layers = [], array $raw = [])
    {
        parent::__construct(id: $id, status: $status, error: $error, raw: $raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        $base = $raw['base_image'] ?? null;
        return new self(
            id: Payload::string($raw, 'id'),
            status: Payload::string($raw, 'status'),
            error: self::error($raw),
            baseImage: is_array($base) ? Image::fromArray($base) : null,
            layers: self::layers($raw),
            raw: $raw,
        );
    }

    /**
     * @param array<string, mixed> $raw
     *
     * @return list<Image>
     */
    protected static function layers(array $raw, bool $required = false): array
    {
        return Payload::listOf($raw, 'layers', Image::fromArray(...), $required);
    }
}
