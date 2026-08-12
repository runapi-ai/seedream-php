<?php

declare(strict_types=1);

namespace RunApi\Seedream\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Seedream\Models\CompletedDecomposeLayersResponse;
use RunApi\Seedream\Models\DecomposeLayersResponse;
use RunApi\Seedream\Types;

/** Separates one image into a base image and independent layers. */
readonly class DecomposeLayers extends TypedConfiguredResource
{
    /**
     * @param array{model: string, image_url: string, prompt?: string, size?: string, output_format?: string, callback_url?: string} $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    public function get(string $id, ?RequestOptions $options = null): DecomposeLayersResponse
    {
        /** @var DecomposeLayersResponse $response */
        $response = parent::get($id, $options);
        return $response;
    }

    /**
     * @param array{model: string, image_url: string, prompt?: string, size?: string, output_format?: string, callback_url?: string} $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedDecomposeLayersResponse
    {
        /** @var CompletedDecomposeLayersResponse $response */
        $response = parent::run($params, $options);
        return $response;
    }

    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/seedream/decompose_layers',
            'seedream/decompose-layers',
            DecomposeLayersResponse::class,
            CompletedDecomposeLayersResponse::class,
            Types::DECOMPOSE_LAYERS_MODELS,
            'decompose-layers',
            DecomposeLayersResponse::class,
            CompletedDecomposeLayersResponse::class,
        );
    }
}
