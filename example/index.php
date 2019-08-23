<?php
require_once __DIR__ . '../vendor/autoload.php';

use tate\bulksms\BulkSmsSender;

// api credentials signup for free http://bulksmsweb.com and get your own credentials + 20 test sms
$username = 'xxx';
$token = 'xxx';
$url = 'xxx';

// intializing the class
$sms = new BulkSmsSender($url, $token,$username);

//adding recepients method
// passing true number will be automatically formated to acceptable number formate per api specification
$sms->add_recepient('0770000000', true); // results in 263770000000
$sms->add_recepient('0770000000'); // results in 0770000000 and might have error with the bulksms api make sure you are passing valid mobile number formate otherwise pass in true to fomate mobile number

//muiltiple recepients
$recepients = array(
	'0770000000',
	'0770000000',
	'0770000000',
	'0770000000',
	'0770000000'
);

$sms->add_recepient($recepients, true);

//setting the message

$sms->$sms->set_message("ndeipi wangu"); //set message method

//sending the message

//important enclose the send_sms() in a try catch block to catch any errors
try{
	// pass in true parameter to use php curl to call bulk sms api 
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


