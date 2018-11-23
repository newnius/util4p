<?php

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
	// REMOTE_ADDR may not be real ip in case server is behind proxy (nginx, docker etc.)
	if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
			for ($i = 0; $i < count($ips); $i++) {
				if (!preg_match('/^(10│172.16│192.168)./i', $ips[$i])) {
					$ip = $ips[$i];
					break;
				}
			}
		}
	}
	return $ip;
}

/* get from $_GET */
function cr_get_GET($key, $default = null)
{
	if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {
		return $_GET[$key];
	}
	return $default;
}

/* get from $_POST */
function cr_get_POST($key, $default = null)
{
	if (isset($_POST[$key]) && strlen($_POST[$key]) > 0) {
		return $_POST[$key];
	}
	return $default;
}
