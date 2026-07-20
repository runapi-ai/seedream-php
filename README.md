# Seedream PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/seedream)](https://packagist.org/packages/runapi-ai/seedream)
[![License](https://img.shields.io/github/license/runapi-ai/seedream-php)](https://github.com/runapi-ai/seedream-php/blob/main/LICENSE)

The Seedream PHP SDK is the Composer package for Seedream on RunAPI. Use it when your PHP application needs associative-array request bodies, task status lookup, polling helpers, file helpers, and consistent RunAPI errors.

## Install

```bash
composer require runapi-ai/seedream
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Seedream\SeedreamClient;

$client = new SeedreamClient(); // reads RUNAPI_API_KEY

$task = $client->textToImage->create([
    'model' => 'seedream-v4-text-to-image',
    'prompt' => 'A precise product render of a glass teapot on white marble',
]);

$status = $client->textToImage->get($task->id);

$edit = $client->editImage->create([
    'model' => 'seedream-v4-edit',
    'prompt' => 'Make it golden hour',
    'source_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
]);

$lite = $client->textToImage->create([
    'model' => 'seedream-5-lite-text-to-image',
    'prompt' => 'A bright editorial photo of a modern bookstore cafe',
    'aspect_ratio' => '4:3',
    'output_quality' => 'basic',
    'output_format' => 'jpeg',
]);

$result = $client->textToImage->run([
    'model' => 'seedream-v4-text-to-image',
    'prompt' => 'A serene mountain lake at dawn',
]);

echo $result->images[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest task state, and `run()` when a script should create and poll until completion. In web request handlers, prefer `create()` plus webhook or later `get()` polling so a worker is not held open.

Returned file URLs are temporary. Download and store generated files in your own durable storage within the retention window.

All SDK exceptions inherit from `RunApi\Core\Errors\RunApiException`, including validation, authentication, rate limit, task failure, and task timeout errors.

## Seedream 5 Pro

Use `seedream-5-pro-text-to-image` for generation and `seedream-5-pro-edit` for image editing. Both accept `output_quality`, optional `output_format`, and optional content safety checking; editing accepts up to 10 source image URLs.

## Links

- Model page: https://runapi.ai/models/seedream
- SDK docs: https://runapi.ai/docs#sdk-seedream
- Product docs: https://runapi.ai/docs#seedream
- Pricing and rate limits: https://runapi.ai/models/seedream/4.5-text-to-image
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/seedream-php
- Multi-language SDK repository: https://github.com/runapi-ai/seedream-sdk

## License

Licensed under the Apache License, Version 2.0.
