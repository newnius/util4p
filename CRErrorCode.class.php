<?php

	class CRErrorCode
	{
		const SUCCESS = 0;
		const FAIL = 1;
		
		public static function getErrorMsg($errno){
			switch($errno){
				case CRErrorCode::SUCCESS:
					return 'Success';
				
				default:
					return 'Unknown error:('.$errno.')';
			}
		}
	}
