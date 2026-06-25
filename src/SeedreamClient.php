<?php

declare(strict_types=1);

namespace RunApi\Seedream;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Seedream\Resources\EditImage;
use RunApi\Seedream\Resources\TextToImage;

/**
 * The Seedream image generation API client.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class SeedreamClient extends BaseClient
{
    /**
     * Provides text-to-image generation operations.
     */
    public readonly TextToImage $textToImage;
    /**
     * Provides image editing operations using source images.
     */
    public readonly EditImage $editImage;

    /**
     * Create a Seedream client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToImage = TextToImage::fromHttp($this->http);
        $this->editImage = EditImage::fromHttp($this->http);
    }
}
