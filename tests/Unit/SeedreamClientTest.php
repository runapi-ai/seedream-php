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
}
