<?php

require __DIR__ . "vendor/autoload.php";

use PhraseanetSDK\EntityManager;
use PhraseanetSDK\Client;
use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;

$HttpAdapter = GuzzleAdapter::create();
$HttpAdapter->setBaseUrl('http://url-to-phraseanet.net/');

$apikey = '';
$apiSecret = '';
$token = '';

$client = new Client($apikey, $apiSecret, $HttpAdapter);
$client->setAccessToken($token);

$em = new EntityManager($client);

$query = $em->getRepository('Record')->search(array(
    'query' => 'animals',
    'offset_start' => 0,
    'per_page' => 20,
    'bases' => array(1, 4),
    'record_type' => 'image'
));

echo $query->getTotalResults() . " items found in " . $query->getQueryTime() . " seconds\n";
