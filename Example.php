<?php

date_default_timezone_set("America/New_York"); // just in case... you definitely don't need this
include_once('FancyHands.php');

// API Keys
$apiKey 	= '';
$apiSecret 	= '';
	
// Development mode? (won't send task to FancyHands assistants)
$dev		= true;
	
// Initialize the API
$FancyHands = new FancyHands($apiKey, $apiSecret, $dev);


$data = array(
    'etc' => 'thing',
    'other' => 'yes',
    'cheese' => "Test Field",
);

print_r($FancyHands->_echo($data));

// Create A custom request...
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

print "Creating a new custom request\n";
$expire = strtotime("+1 day");
$request = $FancyHands->custom_create("PHP Custom Test", "PHP Custom Test...", 1, $customFields, $expire);
$key = $request->key;
print "Created custom!\n";


// get it
print "Loading it again: \n";
$r = $FancyHands->custom_get($request->key);
print "Loaded " . $r->title . " (" . $r->uniq . ")\n";

// cancel it
print "Canceling it\n";
if($FancyHands->cancel($request->key)) {
    print "Cancelled the request.\n";
}
else {
    print "Wasn't able to cancel the request.\n";
}

// get a list of canceled tasks
$query = $FancyHands->custom_get(null, $FancyHands::STATUS_EXPIRED);
foreach($query->requests as $r) {
    print("[" . $r->status . "] " . $r->title . " (" . $r->uniq . ")\n");
}


print "Creating a new standard request\n";
$expire = strtotime("+1 day");
$std = $FancyHands->standard_create("PHP Standard Test", "PHP Standard Test...", 1, $expire);
$key = $std->key;
print "Created standard!\n";



print "Creating message!\n";
$FancyHands->standard_messages_create($std->key, "I'm going to cancel this request");
print "Created message!\n";

print "loading request with messages\n";
$r = $FancyHands->standard_get($std->key);
print "loaded request with messages\n";
foreach($r->messages as $message) {
    print $message->content . "\n";
}


// cancel it
print "Canceling it\n";
if($FancyHands->cancel($r->key)) {
    print "Cancelled the request.\n";
}
else {
    print "Wasn't able to cancel the request.\n";
}

// get a list of canceled tasks
$query = $FancyHands->standard_get(null, $FancyHands::STATUS_EXPIRED);
foreach($query->requests as $r) {
    print("[" . $r->status . "] " . $r->title . " (" . $r->uniq . ")\n");
}

$conversation = array(
    'id'=> 'simple_conversation',
    'name'=> 'Simple Conversation',
    'version'=> 1.1,
    'scripts'=> array(
        
        array(
            'id'=> 'step1',
            'steps'=> array(
                array(
                    'name'=> 'name',
                    'type'=> 'text',
                    'prompt'=> 'Hello my name is $assistant_name, what\'s your name?',
                ),
                array(
                    'name'=> 'thanks',
                    'type'=> 'logic_control',
                    'prompt'=> "Thanks! That's all",
                ),
            )
        )
    )
);


$expire = strtotime("+1 day");
$call = $FancyHands->call_create("8154553440", $conversation, $expire);
$key = $call->key;
print "Created call!\n";

print "Cancelling\n";
if($FancyHands->cancel($key)) {
    print "Success\n";
}

# get a list of canceled tasks
$query = $FancyHands->call_get(null, $FancyHands::STATUS_EXPIRED);
foreach($query->calls as $r) {
    print("[" . $r->status . "] " . $r->title . " (" . $r->uniq . ")\n");
}
