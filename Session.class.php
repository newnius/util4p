<?php
	session_start();

	class Session
	{
		private static $time_out = 0; // 0-never expire
		private static $bind_ip = false; // bind session with ip, when client ip changes, previous session will be unavailable

		/*
		 */
		public static function configure($config)
		{
			self::$time_out = $config->get('time_out', self::$time_out);
			self::$bind_ip = $config->getBool('bind_ip', self::$bind_ip);
		}

		/*
		 */
		public static function put($key, $value, $namespace='default')
		{
			$_SESSION[$namespace][$key] = $value;
			$_SESSION['_SELF']['LAST_ACTIVE'] = time();
			return true;
		}


		/*
		 */
		public static function get($key, $default=null, $namespace='default')
		{
			if(!isset($_SESSION['_SELF']['LAST_ACTIVE'])){
				$_SESSION['_SELF']['LAST_ACTIVE'] = 0;
			}
			if(self::$time_out > 0 && time()-$_SESSION['_SELF']['LAST_ACTIVE'] > self::$time_out ){
				return $default;
			}
			$_SESSION['_SELF']['LAST_ACTIVE'] = time();
			if(isset($_SESSION[$namespace][$key]))
			{
				return $_SESSION[$namespace][$key];
			}
			return $default;
		}


		/*
		 */
		public static function remove($key, $namespace='default')
		{
			unset($_SESSION[$namespace][$key]);
			return true;
		}


		/*
		 */
		public static function clear($namespace='default')
		{
			$_SESSION[$namespace] = array();
			return true;
		}


		/*
		 */
		public static function clearAll()
		{
			$_SESSION=array();
			session_destroy();
			return true;
		}

	}
