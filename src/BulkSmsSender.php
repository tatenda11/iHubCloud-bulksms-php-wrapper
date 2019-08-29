<?php

/**
 * Contains the BulkSmsSender wrapper class.
 *
 * @author      Tatenda Munenge
 * @see 		http://www.bulksmsweb.com/
 * @license     MIT
 * @since       2019-08-20
 *
 */

namespace tate\bulksms;

class BulkSmsSender
{
	
	private $api_url = '';
	private $api_tokken = '';
	private $api_username = '';
	private $destinations = [];
	private $groups = [];
	public  $message = '';
	public  $request_success = false;
	public  $server_responce= [];

		
	public  function __construct($url = '' , $tokken='', $username='')
	{
		$this->api_url = $url;
		$this->api_tokken = $tokken;
		$this->api_username = $username;
		return $this;
	}


	public function set_api_url($uri)
	{
		$this->api_url = $url;
		return $this;
	}

	public function set_api_username($username)
	{
		$this->username = $username;
		return $this;
	}

	public function set_api_tokken($tokken)
	{	
		$this->api_tokken = $tokken;
		return $this;
	}

	public function set_message($message)
	{
		$this->message = $message;
	}
	
	public function add_recepient($numbers, $fomate_num = false)
	{
		if(is_array($numbers) && !empty($numbers))
		{
			if($fomate_num)
			{
				for($i = 0; $i < count($numbers); $i++)
				{
					array_push($this->destinations, $this->fomate_num($numbers[$i]) );
				}
				return $this;
			}
			$this->destinations = array_merge($this->destinations, $numbers);

		}
		elseif(!empty($numbers))
		{
			if($fomate_num)
			{
				array_push($this->destinations, $this->fomate_num($numbers));
			}
			else
			{
			 	array_push($this->destinations, $numbers);
			}
		}
		return $this;
		
	}


	public function add_group_recepients($group_code)
	{
		if(is_array($group_code) && !empty($group_code))
		{
			$this->groups = array_merge($this->groups, $group_code);
			return $this;
		}
		array_push($this->groups, $group_code);
		return $this;
	}

	/*
	* Assumes only local Zimbabwean numbers are used
	* validation based on standard service provider 10 digit numbers
	* @todo add more filter checks
	* @todo support internation numbers incase there is an option to so by service provide
	*/
	private function fomate_num($num)
	{
		$country_code = '263';
		$num = preg_replace("/[^0-9]/", "", $num );
		//normal standard number
		if(strlen($num) == 10  && $num[0] == 0)
		{
			return preg_replace('/^0/', $country_code, $num);
		}
		if(strlen($num) == 9  && $num[0] != 0){
			return $country_code + $num;
		}
		return $num;   
	}
	/*
	 * @see  //read api documentation http://portal.bulksmsweb.com/downloads/BulkSMS-API.pdf
	*/
	
	public function send_sms($use_curl_request = false)
	{
		if(empty($this->api_url) || empty($this->api_tokken) || empty($this->api_username))
		{
			throw new \Exception("Invalid param, Supply api url tokken and username");
			return false;	
		}
		$ws_str = $this->api_url . '&u=' . $this->api_username . '&h=' . $this->api_tokken . '&op=pv';
        $ws_str .= '&to=' . urlencode($this->compile_destination()) . '&msg='.urlencode($this->message);
        //die($ws_str);
       	try{
       		 ($use_curl_request === true) ? $this->send_curl_request($ws_str) : send_get_file_contents($ws_str);
       	}
       	catch(\Exception $e){
       		throw new \Exception("cURL extension not loaded! use send_sms(false)");
       	}
       	finally{
       		return false;
       	}
        
        if(!empty($this->server_responce))
        {
        	return true;
        }
        return false;
	}


	private function send_get_file_contents($url)
	{
		try
		{
			$ws_response = @file_get_contents($url);
        	$this->server_responce = json_decode($ws_response, true);
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage());
			return;
		}
	}

	private function send_curl_request($url) 
	{
		if(!extension_loaded("curl")) 
		{
			throw new \Exception("cURL extension not loaded! use send_sms(false)");
			return;
		}
		try
		{
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($ch);
			$this->server_responce = json_decode($data, true);
			curl_close($ch);
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage());
			return;
		}
	}


	private function compile_destination()
	{
		$str_nums =   rtrim(implode(',', $this->destinations), ',');
		if(!empty($this->groups))
		{
			$grp_str = $str_nums != '' ?  ',' . rtrim(implode(',', $this->groups), ',') : rtrim(implode(',', $this->groups), ',');
			$str_nums .= $grp_str;
		}
		return $str_nums;
	}
}