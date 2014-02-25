<?php

	class FancyHands {

		public static $OAuth;
		public static $testMode;
		
		// By passing the third option as true, it will prevent task creations from being handled by a FanyHands agent.
		public function __construct($apiKey,$apiSecret,$test = false) {
				
			self::$OAuth = new OAuth($apiKey,$apiSecret,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_AUTHORIZATION);
			self::$testMode = $test;
			
		}
		
		/**
		 * cancel()
		 *
		 * Allows you to cancel an active FancyHands task.
		 *
		 * @param	string	$key	Key of the task you wish to cancel.
		 * @return 	array			"success" boolean, and "message" if the request failed.
		 */
		public function cancel($key) {
		
			try{
			
				self::$OAuth->fetch("https://www.fancyhands.com/api/v1/request/custom/cancel",array('key' => $key),OAUTH_HTTP_METHOD_POST);
				return array('success' => true, 'message' => '');
				
			} catch(OAuthException $E) {
			
				return array('success' => false, 'messsage' => $E->lastResponse);
				
			}
			
		}
		
		/**
		 * create()
		 *
		 * Allows you to create a new FancyHands task, with or without custom fields.
		 *
		 * @param	string	$title			Title of the task.
		 * @param	string	$description	Description of the task.
		 * @param	float	$bid			Amount you would like to pay for the task.
		 * @param	array	$customFields	Multidimensional array of custom fields for the assistant to fill out. (see example) (optional)
		 * @param	date	$expirationDate	Expiration date ormatted as "2014-02-26T10:09:08Z" - Must be within 7 days.
		 * @return 	array					"success" boolean, "message", task "key", and "created" date.
		 */
		public function create($title, $description, $bid, $customFields, $expirationDate) {
		
			$customFields = json_encode($customFields);
			$postFields = array(
				'title' => $title,
				'description' => $description,
				'bid' => $bid,
				'custom_fields' => $customFields,
				'expiration_date' => $expirationDate,
				'test' => self::$testMode
			);
			
			try {

				self::$OAuth->fetch("https://www.fancyhands.com/api/v1/request/custom/",$postFields,OAUTH_HTTP_METHOD_POST);
				$response = json_decode(self::$OAuth->getLastResponse());
				return array('success' => true, 'message' => '', 'key' => $response->key, 'created' => $response->date_created);
				
			} catch(OAuthException $E) {
				
				return array('success' => false, 'messsage' => $E->lastResponse, 'key' => '', 'created' => '');
				
			}
			
		}
		
		/**
		 * get()
		 *
		 * Allows you to retrieve one or multiple FancyHands tasks.
		 *
		 * @param	string	$key	Key of the task you wish retrieve (optional)
		 * @param	int		$status	Status code of the task(s) you wish to retrieve. (optional)
		 * @param	cursor	$cursor	Results cursor. (optional)
		 * @return 	array			"success" boolean, "message", and "response" with a mulidimensional object of tasks from FancyHands.
		 */
		public function get($key = null, $status = null, $cursor = null) {
		
			$getFields = array(
				'key' => $key,
				'status' => $status,
				'cursor' => $cursor
			);
			
			try {

				self::$OAuth->fetch("https://www.fancyhands.com/api/v1/request/custom/",$getFields,OAUTH_HTTP_METHOD_GET);
				$response = json_decode(self::$OAuth->getLastResponse());
				
				return array('success' => true, 'message' => '', 'response' => $response);

			} catch(OAuthException $E) {
				
				return array('success' => false, 'messsage' => $E->lastResponse);
				
			}
			
		}
		

	}