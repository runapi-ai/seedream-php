<?php

declare(strict_types=1);

namespace RunApi\Seedream;

/**
 * Constants for model slugs supported by the Seedream PHP SDK.
 */
final class Types
{
    /** @var list<string> */
    public const TEXT_TO_IMAGE_MODELS = ['seedream-4.5-text-to-image', 'seedream-5-lite-text-to-image', 'seedream-v4-text-to-image'];

    /** @var list<string> */
    public const EDIT_IMAGE_MODELS = ['seedream-4.5-edit', 'seedream-5-lite-edit', 'seedream-v4-edit'];

    private function __construct()
    {
    }
}
