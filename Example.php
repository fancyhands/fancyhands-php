<?php

	include_once('FancyHands.php');
	
	// API Keys
	$apiKey 	= '';
	$apiSecret 	= '';
	
	// Development mode? (won't send task to FancyHands assistants)
	$dev		= true;
	
	// Initialize the API
	$FancyHands = new FancyHands($apiKey, $apiSecret, $dev);
	
	// Get all tasks.
	print_r($FancyHands->get());
	
	// Get all tasks with status code 20.
	print_r($FancyHands->get(null,20));
	
	// Cancel a task with key "foobar"
	print_r($FancyHands->cancel("foobar"));
	
	// Create a new task with one custom field, a title of "OAuth Test", a description of "OAuth Test Desc", a bid of $1.00, and an expiration date of 2014-02-27T00:00:00Z
    $customFields = array(
    	array(
    		'type' => 'text',
    		'required' => false,
    		'label' => "Test Field",
    		"description" => "Test Field Description",
    		"order" => 0,
    		"field_name" => "test_field"
    	)
    );
    
    print_r($FancyHands->create("OAuth Test", "OAuth Test Desc", 1, $customFields, "2014-02-27T00:00:00Z"));