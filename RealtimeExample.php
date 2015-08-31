<?php

date_default_timezone_set("America/New_York"); // just in case... you definitely don't need this
include_once('FancyHands.php');

// API Keys
$apiKey 	= '';
$apiSecret 	= '';
$dev = false;
	
// Initialize the API
$FancyHands = new FancyHands($apiKey, $apiSecret, $dev);

$response = $FancyHands->realtime_request_create("Can you just respond to this once and close it (this is a test).");
print_r($response);

$request = $response->request;

$response = $FancyHands->realtime_message_create($request->key, "Just close and continue.");

print_r($FancyHands->realtime_request_get($request->key));


$response = $FancyHands->realtime_message_get($request->key);

print_r($response);

print_r($FancyHands->realtime_request_close($request->key));



