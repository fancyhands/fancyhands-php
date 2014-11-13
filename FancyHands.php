<?php

	class FancyHands {

        const API_HOST = "https://www.fancyhands.com";        
        const STATUS_NEW = 1;
        const STATUS_OPEN = 5;
        const STATUS_CLOSED = 20;
        const STATUS_EXPIRED = 21;
        
		public static $OAuth;
		public static $testMode;

		// By passing the third option as true, it will prevent task creations from being handled by a FancyHands agent.
		public function __construct($apiKey, $apiSecret, $test = false) {
			self::$OAuth = new OAuth($apiKey, $apiSecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_AUTHORIZATION);
			self::$testMode = $test;
		}

		/**
		 * cancel()
		 *
		 * Allows you to cancel an active FancyHands task (assuming it's not being worked on).
		 *
		 * @param	string	$key	Key of the task you wish to cancel.
		 * @return 	boolean			True if it worked, False if it didn't
		 */
		public function cancel($key) {
            $response = self::_post('/api/v1/request/custom/cancel', array('key' => $key));
            if($response->success) {
                return True;
            }
            else {
                return False;
            }
		}

		/**
		 * _echo()
		 *
		 * Allows you to create a new Standard FancyHands task, with or without custom fields.
		 *
		 * @param	array	$data			Any data that you want to echo back
		 * @return 	stdClass					The request that you sent.
		 */
		public function _echo($data) {
            return self::_post('/api/v1/echo/', $data);
		}




		/**
		 * standard_messages_create()
		 *
		 * Allows you to create a message on a standard task (when you want to tell the assistant something AFTER you've submitted the task)
		 *
		 * @param	string	$key			The `key` of the request
		 * @param	string	$message	The body of the message that you want to send.
		 * @return 	stdClass					The full request object (includes messages)
		 */
		public function standard_messages_create($key, $message) {
			$postFields = array(
				'key' => $key,
				'message' => $message
			);
            return self::_post('/api/v1/request/standard/messages/', $postFields);
		}

		/**
		 * standard_create()
		 *
		 * Allows you to create a new Standard FancyHands task, with or without custom fields.
		 *
		 * @param	string	$title			Title of the task.
		 * @param	string	$description	Description of the task.
		 * @param	float	$bid			Amount you would like to pay for the task.
		 * @param	date	$expirationDate	Expiration date - Must be within 7 days.
		 * @return 	stdClass					The request that you created.
		 */
		public function standard_create($title, $description, $bid, $expirationDate) {
			$postFields = array(
				'title' => $title,
				'description' => $description,
				'bid' => $bid,
				'expiration_date' => $expirationDate,
				'test' => self::$testMode
			);
            return self::_post('/api/v1/request/standard/', $postFields);
		}


		
		/**
		 * standard_get()
		 *
		 * Allows you to retrieve one or multiple FancyHands standard tasks.
		 *
		 * @param	string	$key	Key of the task you wish retrieve (optional)
		 * @param	int		$status	Status code of the task(s) you wish to retrieve. (optional)
		 * @param	cursor	$cursor	Results cursor. (optional)
		 * @return 	stdClass			result->requests and result->next_page or (if you provide a key) $result is just a request object
		 */
		public function standard_get($key = null, $status = null, $cursor = null) {
            return self::misc_get('standard', $key, $status, $cursor);
		}
                

		/**
		 * custom_create()
		 *
		 * Allows you to create a new FancyHands task, with custom fields.
		 *
		 * @param	string	$title			Title of the task.
		 * @param	string	$description	Description of the task.
		 * @param	float	$bid			Amount you would like to pay for the task.
		 * @param	array	$customFields	Multidimensional array of custom fields for the assistant to fill out. (see example) (optional)
		 * @param	date	$expirationDate	Expiration date - Must be within 7 days.
		 * @return 	array					"success" boolean, "message", task "key", and "created" date.
		 */
		public function custom_create($title, $description, $bid, $customFields, $expirationDate) {
			$customFields = json_encode($customFields);
			$postFields = array(
				'title' => $title,
				'description' => $description,
				'bid' => $bid,
				'custom_fields' => $customFields,
				'expiration_date' => $expirationDate,
				'test' => self::$testMode
			);
            return self::_post('/api/v1/request/custom/', $postFields);
		}
		
		/**
		 * custom_get()
		 *
		 * Allows you to retrieve one or multiple FancyHands custom tasks.
		 *
		 * @param	string	$key	Key of the task you wish retrieve (optional)
		 * @param	int		$status	Status code of the task(s) you wish to retrieve. (optional)
		 * @param	cursor	$cursor	Results cursor. (optional)
		 * @return 	stdClass			result->requests and result->next_page or (if you provide a key) $result is just a request object
		 */
		public function custom_get($key = null, $status = null, $cursor = null) {
            return self::misc_get('custom', $key, $status, $cursor);
		}



		/**
		 * call_create()
		 *
		 * Allows you to create a new FancyHands task, with custom fields.
		 *

		 * @param	string	$phone			The phone number we need to call
		 * @param	array	$conversation	Multidimensional array of conversation script that the assistant will use. 
		 * @param	date	$expirationDate	Expiration date - Must be within 7 days.
		 * @param	string	$title			Title of the task. (optional)
		 * @return 	stdClass					The newly created request
		 */
		public function call_create($phone, $conversation, $expirationDate, $title=null) {
			$conversation = json_encode($conversation);
			$postFields = array(
				'title' => $title,
				'conversation' => $conversation,
				'phone' => $phone,                
				'expiration_date' => $expirationDate,
				'test' => self::$testMode
			);
            return self::_post('/api/v1/call/', $postFields);
		}
		


		/**
		 * call_get()
		 *
		 * Allows you to retrieve one or multiple FancyHands call tasks.
		 *
		 * @param	string	$key	Key of the task you wish retrieve (optional)
		 * @param	int		$status	Status code of the task(s) you wish to retrieve. (optional)
		 * @param	cursor	$cursor	Results cursor. (optional)
		 * @return 	stdClass			result->requests and result->next_page or (if you provide a key) $result is just a request object
		 */
		public function call_get($key = null, $status = null, $cursor = null) {
            return self::misc_get('call', $key, $status, $cursor);
		}


        public function misc_get($type = null, $key = null, $status = null, $cursor = null) {
			$getFields = array(
				'key' => $key,
				'status' => $status,
				'cursor' => $cursor
			);
            return self::_get('/api/v1/request/' . $type . '/', $getFields);            
        }
        
        /** ******************* */
        // Just stuff used by this class
        private function _get($url, $getFields) {
            $s = self::$OAuth->fetch(self::API_HOST . $url, $getFields, OAUTH_HTTP_METHOD_GET);
            if($s) {
                return json_decode(self::$OAuth->getLastResponse());
            }
        }
        private function _prep_data($data) {
            // conver the expiration_date to a string.
            if(array_key_exists('expiration_date', $data) and is_int($data['expiration_date'])) {
                $data['expiration_date'] = self::_get_time_string($data['expiration_date']);
            }
            return $data;

        }
        private function _post($url, $data) {
            $data = self::_prep_data($data);
            $s = self::$OAuth->fetch(self::API_HOST . $url, $data, OAUTH_HTTP_METHOD_POST);
            if($s) {
                return json_decode(self::$OAuth->getLastResponse());                
            }
        }

        // receive a date (in int form), convert it to UTC, and then to string
        private function _get_time_string($d) {
            $tz = @date_default_timezone_get();
            if($tz != "UTC") {
                date_default_timezone_set("UTC");
            }
            $s = substr(date("c", $d), 0, -6) . "Z";
            if($tz != "UTC") {
                date_default_timezone_set($tz);
            }
            return $s;
        }

        
	}
