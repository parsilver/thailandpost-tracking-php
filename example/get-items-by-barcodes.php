<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;

$tokenKey = 'your-api-token-key-here';

$client = ClientBuilder::create()
    ->setCredential($tokenKey)
    ->build();

$api = new ApiEndpoint($client);

$response = $api->getItemsByBarcodes([
    'status' => 'all',
    'language' => 'TH',
    'barcode' => ['EN123456789TH', 'EN987654321TH'],
]);

if ($response->isSuccessfull()) {
    echo $countNumber = $response->json('response.track_count.count_number');

    echo '<hr>';

    echo '<pre>';
    echo json_encode($response->json(), JSON_PRETTY_PRINT);
    echo '</pre>';
} else {
    echo 'Error';
}
