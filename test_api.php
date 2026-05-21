<?php
$url = 'https://slipgaji.simaru.my.id/api/satuan-kerja';
$apiKey = 'sipena-secret';

$ctx = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "X-API-Key: $apiKey\r\nAccept: application/json"
    ]
]);

$response = file_get_contents($url, false, $ctx);
echo $response;
