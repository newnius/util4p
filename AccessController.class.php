<?php

	class AccessController
	{
		// $operation => arrayof roles
		private static $rules_array = array(
			/* user */
			'user_add' => array('root', 'admin'),
			'user_get' => array('root', 'admin', 'reviewer', 'teacher'),
			'user_update_self' => array('root', 'admin', 'reviewer', 'teacher'),
			'user_update' => array('root'),
			'user_delete' => array('root'),
		);

		
		public static function hasAccess($role, $operation)
		{
			//echo "Calling hasAccess($role, $operation)\n";
			if(array_key_exists($operation, self::$rules_array))
			{
				return in_array($role, self::$rules_array[$operation]);
			}
			return false;
		}

  }
