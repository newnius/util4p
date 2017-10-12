<?php

	/* @deprecated */
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
	 * Notice: This method works best in the situation where the server is behind proxy
	 *   and proxy will replace HTTP_CLIENT_IP. If your app is exposed straight to
	 *   the Internet, this may return a wrong ip when a visits from Intranet but
	 *   claims from Internet. It is a trade-off.
   */
	function cr_get_client_ip()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		if(preg_match ('/^(10│172.16│192.168)./i', $ip))
		{// REMOTE_ADDR may not be real ip in case server is behind proxy (nginx, docker etc.)
			if(!empty($_SERVER['HTTP_CLIENT_IP'])){
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ips = explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
				for ($i=0; $i < count($ips); $i++){
					if(!preg_match ('/^(10│172.16│192.168)./i', $ips[$i])){
						$ip = $ips[$i];
						break;
					}
				}
			}
		}
		return $ip;
	}

	/* @deprecated */
	function cr_get($value, $default)
	{
		if(isset($value) && !empty($value)){
			return $value;
		}
		return $default;
	}

  /* get from $_GET */
	function cr_get_GET($key, $default=null)
	{
		if(isset($_GET[$key]) && strlen($_GET[$key])>0 ){
			return $_GET[$key];
		}
		return $default;
	}

	/* get from $_POST */
	function cr_get_POST($key, $default=null)
	{
		if(isset($_POST[$key]) && strlen($_POST[$key])>0 ){
			return $_POST[$key];
		}
		return $default;
	}

	/* @deprecated
	 * get from $_SESSION
	 */
	function cr_get_SESSION($key, $default=null)
	{
		if(isset($_SESSION[$key]) && strlen($_SESSION[$key]>0)){
			return $_SESSION[$key];
		}
		return $default;
	}

	/* @deprecated
   * get from $_SERVER
	 */
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
	 * @return  array('err'=>'', 'headers'=>''. 'info'=>'', 'content'=>'')
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
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$ret = curl_exec($ch);
		$response['err'] = curl_error($ch);
		if(!$response['err']){
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($ret, 0, $header_size);
			$headers = array();
			// Split the string on every "double" new line.
			$arrRequests = explode("\r\n\r\n", $header);
			// Loop of response headers. The "count() -1" is to avoid an empty row for the extra line break before the body of the response.
			for ($index = 0; $index < count($arrRequests) -1; $index++) {
				foreach (explode("\r\n", $arrRequests[$index]) as $i => $line)
				{
					if ($i === 0)
						$headers[$index]['http_code'] = $line;
					else
					{
						list ($key, $value) = explode(': ', $line);
						$headers[$index][$key] = $value;
					}
				}
			}
			$body = substr($ret, $header_size);
			$response['headers'] = $headers[max(0, count($headers)-1)];
			$response['content'] = $body;
		}
		if(!$response['err'])
			$response['info'] = curl_getinfo($ch);
		curl_close($ch);
		return $response;
	}
