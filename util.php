<?php
	
	function cr_require_file($filename)
	{
		if(file_exists($filename))
		{
			require_once($filename);
		}else
		{
			header('HTTP/1.1 500 Internal Server Error');
			exit("File $filename not exist");
		}
	}

  /*
   * get client side ip
   */
	function cr_get_client_ip()
	{
		$ip = false;
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']); 
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){ 
			$ip=$_SERVER['HTTP_CLIENT_IP']; 
		}
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
			$ips=explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']); 
			if($ip){
				array_unshift($ips, $ip); 
				$ip=false;
			}
			for ($i=0; $i < count($ips); $i++){
				if(!preg_match ('/^(10│172.16│192.168)./i', $ips[$i])){
					$ip=$ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']); 
	}


	function cr_get($value, $default)
	{
		if(isset($value) && !empty($value)){
			return $value;
		}
		return $default;
	}

  // get is the same with GET
	function cr_get_GET($key, $default=null)
	{
		if(isset($_GET[$key]) && strlen($_GET[$key])>0 ){
			return $_GET[$key];
		}
		return $default;
	}

	// post is the same with POST
	function cr_get_POST($key, $default=null)
	{
		if(isset($_POST[$key]) && strlen($_POST[$key])>0 ){
			return $_POST[$key];
		}
		return $default;
	}

	// get is the same with GET
	function cr_get_SESSION($key, $default=null)
	{
		if(isset($_SESSION[$key]) && strlen($_SESSION[$key]>0)){
			return $_SESSION[$key];
		}
		return $default;
	}

  // get is the same with SERVER
	function cr_get_SERVER($key, $default=null)
	{
		if(isset($_SERVER[$key])){
			return $_SERVER[$key];
		}
		return $default;
	}
