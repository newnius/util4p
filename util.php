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

	/**
	 * curl (support https)
	 * origin: http://blog.csdn.net/linvo/article/details/8816079
	 * 
	 * @param   string  url
	 * @param   int     timeout
	 * @param   bool    HTTPS strict check
	 * @return  array('err'=>'', 'headers'=>''. 'content'=>'')
	 */
	function cr_curl($url, $timeout = 15, $CA = true)
	{
		$cacert = dirname(__FILE__).'/cacert.pem'; //CA根证书
		$SSL = substr($url, 0, 8) == "https://" ? true : false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);
		if ($SSL && $CA) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // 只信任CA颁布的证书
			curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA根证书（用来验证的网站证书是否是CA颁布）
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配
		} else if ($SSL && !$CA) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //避免data数据过长问题
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$ret = curl_exec($ch);
		$response['err'] = curl_error($ch);
		if(!$response['err'])
			$response['headers'] = curl_getinfo($ch);
		if(!$response['err'])
			$response['content'] = $ret;
		curl_close($ch);
		return $response;
	}
