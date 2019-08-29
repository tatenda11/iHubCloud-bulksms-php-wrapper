# iHubCloud-bulksms-php-wrapper
A simple PHP wrapper class for http://www.bulksmsweb.com/ for sending sms messages

# Prerequisites

This library has a set of prerequisites that must be met for it to work

1.  PHP version 5.6 or higher
2.  Curl extension optional but highly recommended



# Installation

Install the library using composer

```sh
$ composer require tate/bulksms:dev-master
```

and include the composer autoloader

```php
<?php
	require_once 'path/to/vendor/autoload.php';

	// use BulkSmsSender class here
```
---
---


# Or 

Alternatively, if you do not have composer installed, [first download the library here](https://github.com/tatenda11/iHubCloud-bulksms-php-wrapper/archive/master.zip). And include the autoloader file included with the library

```php
<?php
	require_once 'path/to/library/autoloader.php';

	// Do stuff
```
# Usage example

1 Create a bulk sms web account at http://www.bulksmsweb.com/
2 Get you username tokken and api url from http://www.bulksmsweb.com/
3 Install the BulkSmsSender class via composer of download the repository
4 Load the class
5 Create an instance of BulkSmsSender class
6 Start sending sms  

```php
	require_once  'path/to/vendor/autoload.php';
	use tate\bulksms\BulkSmsSender;
	// api credentials signup for free http://bulksmsweb.com and get your own credentials + 20 test sms
	$username = 'xxx';
	$token = 'xxx';
	$url = 'xxx';
	$sms = new BulkSmsSender($url, $token,$username);
	$sms->add_recepient('0770000000', true); // results in 263770000000
	$sms->$sms->set_message("important notice team"); 
	try{
		$sms->send_sms(true);
		//do not pass true to use lighter version file_get_contents instead of curl on when curl is not enabled on your server

		//sms->send_sms();

		//getting sever responce 
		/*
		* $sms->server_responce is array of bulk sms server feedback decordered into a php array
		*@see  //read api documentation http://portal.bulksmsweb.com/downloads/BulkSMS-API.pdf
		*/
		print_r($sms->server_responce);
	}
	catch(\Exception $e){
		echo $e->getMessage();
	}

```
