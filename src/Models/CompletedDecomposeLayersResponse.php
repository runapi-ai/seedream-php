<?php

declare(strict_types=1);

namespace RunApi\Seedream\Models;

use RunApi\Core\Support\Payload;

/** Completed layer decomposition response with required results. */
readonly class CompletedDecomposeLayersResponse extends DecomposeLayersResponse
{
    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        $base = Payload::array($raw, 'base_image');
        return new self(
            id: Payload::string($raw, 'id'),
            status: Payload::string($raw, 'status'),
            error: self::error($raw),
            baseImage: Image::fromArray($base),
            layers: self::layers($raw, required: true),
            raw: $raw,
        );
    }

    public static function fromResponse(DecomposeLayersResponse $response): self
    {
        return self::fromArray($response->toArray());
    }
}
