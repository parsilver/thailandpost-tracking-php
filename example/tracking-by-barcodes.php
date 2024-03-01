<?php

require_once __DIR__.'/../vendor/autoload.php';

use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;

$tokenKey = '<YOUR_API_TOKEN>';

$client = ClientBuilder::create()->setCredential($tokenKey)->build();

$api = new ApiEndpoint($client);

$barcodes = ['EN123456789TH', 'EN987654321TH'];

$response = $api->trackByBarcodes($barcodes);

echo $countNumber = $response->json('response.track_count.count_number');
