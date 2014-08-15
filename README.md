FancyHands PHP Wrapper
======================

PHP Wrapper for the [FancyHands API](https://www.fancyhands.com/developer) originally written by [Charles Zink](https://twitter.com/charleszink) ([Github](https://github.com/dealerteam-charles)).  (So thank him if it's helpful) 

It has been updated for new API calls by [Ted Roden](https://twitter.com/tedroden) [Github](https://github.com/tedroden) (so blame him if it's broken)

Requirements
------------

The only requirement, other than PHP, is the pecl OAuth extension

### Installing pecl/oauth on Ubuntu/Debian:

    sudo apt-get install pecl
    sudo pecl install oauth

### Installing pecl/oauth a Mac

[Install pecl](http://jason.pureconcepts.net/2012/10/install-pear-pecl-mac-os-x/)

    sudo pecl install oauth
	
Usage
-----

[Complete usage examples in Example.php](https://github.com/fancyhands/fancyhands-php/blob/master/Example.php)

### Making an API Request

Getting everything ready.

```php
// API Keys
$apiKey 	= 'YOUR_API_KEY';
$apiSecret 	= 'YOUR_API_SECRET';

// Initialize the API
$FancyHands = new FancyHands($apiKey, $apiSecret);
```

Create a [standard](http://localhost:8080/api/explorer#/explorer/fancyhands.standard.Standard) request:

```php
// create a standard request that expires tomorrow
$expire = strtotime("+1 day");
$std = $FancyHands->standard_create("Standard Request", Do something for me...", 3.0, $expire);
```

Add a message to the standard request:

```php
$FancyHands->standard_messages_create($std->key, "I'm going to cancel this request");
```

Cancel it: 

```php
if($FancyHands->cancel($r->key)) {
   print("Canceled!");
}
```

Get a list of all expired requests:

```php
$query = $FancyHands->standard_get(null, $FancyHands::STATUS_EXPIRED);
foreach($query->requests as $r) {
    print("[" . $r->status . "] " . $r->title . " (" . $r->uniq . ")\n");
}
```

Get a single request: 

```php
$request = $FancyHands->standard_get($std->key);
```

Create a [custom](https://github.com/fancyhands/api/wiki/fancyhands.request.Custom) request:

```php
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
$expire = strtotime("+1 day");
$request = $FancyHands->custom_create("PHP Custom Test", "PHP Custom Test...", 1, $customFields, $expire);
```

Other Versions
--------------

 - A native [CodeIgniter library](https://github.com/dealerteam-charles/FancyHandsPHP-CodeIgniter).

Support
-------

 - You can contact FancyHands developer support at api@fancyhands.com 
 - For help using or installing this wrapper, feel free to contact charleszink@gmail.com as well.

License
-------

[MIT](https://github.com/fancyhands/fancyhands-php/blob/master/LICENSE.txt)


