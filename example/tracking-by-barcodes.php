<?php

require_once __DIR__.'/../vendor/autoload.php';

use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;

$tokenKey = "your-token-key";

$client = ClientBuilder::create()
    ->setCredential($tokenKey)
    ->build();

$api = new ApiEndpoint($client);

$response = $api->trackByBarcodes([
    "barcode" => ["EN123456789TH", "EN987654321TH"],
]);

if ($response->isSuccessfull()) {
    echo $countNumber = $response->json("response.track_count.count_number");
} else {
    echo "Error";
}
