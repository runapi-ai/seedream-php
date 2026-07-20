<?php

declare(strict_types=1);

namespace RunApi\Seedream\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Seedream\Models\CompletedImageTaskResponse;
use RunApi\Seedream\Resources\EditImage;
use RunApi\Seedream\Resources\TextToImage;
use RunApi\Seedream\SeedreamClient;

final class SeedreamClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new SeedreamClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToImage::class, $client->textToImage);
        self::assertInstanceOf(EditImage::class, $client->editImage);
    }

    public function testTextToImageRunReturnsImages(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","images":[{"url":"https://file.runapi.ai/image.png"}]}'),
        ]);
        $client = new SeedreamClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToImage->run([
            'model' => 'seedream-v4-text-to-image',
            'prompt' => 'A product render',
        ]);

        self::assertSame('https://file.runapi.ai/image.png', $result->images[0]->url);
        self::assertInstanceOf(CompletedImageTaskResponse::class, $result);
        self::assertSame('/api/v1/seedream/text_to_image', $transport->requests[0]->getUri()->getPath());
        self::assertSame('/api/v1/seedream/text_to_image/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testLiteOutputFormatIsForwarded(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_lite","status":"processing"}'),
        ]);
        $client = new SeedreamClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->editImage->create([
            'model' => 'seedream-5-lite-edit',
            'prompt' => 'Restyle this image',
            'source_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
            'aspect_ratio' => '1:1',
            'output_quality' => 'high',
            'output_format' => 'jpeg',
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('jpeg', $body['output_format']);
        self::assertArrayNotHasKey('outputFormat', $body);
    }

    public function testSeedream5ProRequestsUsePublicModelIds(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_pro_text","status":"processing"}'),
            new Response(200, [], '{"id":"task_pro_edit","status":"processing"}'),
        ]);
        $client = new SeedreamClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->textToImage->create([
            'model' => 'seedream-5-pro-text-to-image',
            'prompt' => 'A photorealistic rooftop cafe at sunrise',
            'aspect_ratio' => '21:9',
            'output_quality' => 'high',
            'output_format' => 'jpeg',
        ]);
        $client->editImage->create([
            'model' => 'seedream-5-pro-edit',
            'prompt' => 'Turn the material into transparent glass',
            'source_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
            'aspect_ratio' => '3:2',
            'output_quality' => 'basic',
            'output_format' => 'png',
        ]);

        $textBody = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $editBody = json_decode((string) $transport->requests[1]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('seedream-5-pro-text-to-image', $textBody['model']);
        self::assertSame('seedream-5-pro-edit', $editBody['model']);
        self::assertSame(['https://cdn.runapi.ai/public/samples/image.jpg'], $editBody['source_image_urls']);
    }
}
