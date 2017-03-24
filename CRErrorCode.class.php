<?php

	class CRErrorCode
	{
		/* common */
		const SUCCESS = 0;
		const FAIL = 1;
		const NO_PRIVILEGE = 2;
		const UNKNOWN_ERROR = 3;
		const IN_DEVELOP = 4;
		const INVALID_REQUEST = 5;
		const UNKNOWN_REQUEST = 6;
		const CAN_NOT_BE_EMPTY = 7;
		const INCOMPLETE_CONTENT = 8;
		const FILE_NOT_UPLOADED = 9;
		const RECORD_NOT_EXIST = 10;
		const INVALID_PASSWORD = 11;
		const UNABLE_TO_CONNECT_REDIS = 12;	
		const UNABLE_TO_CONNECT_MYSQL = 13;	

		/* user */
		const USERNAME_OCCUPIED = 14;
		const EMAIL_OCCUPIED = 15;
		const INVALID_USERNAME = 16;
		const INVALID_EMAIL = 17;
		const WRONG_PASSWORD = 18;
		const NOT_LOGED = 19;
		const USER_NOT_EXIST = 20;
		const USER_IS_BLOCKED = 21;


		public static function getErrorMsg($errno){
			switch($errno){
				case CRErrorCode::SUCCESS:
					return '成功';

				case CRErrorCode::USERNAME_OCCUPIED:
					return '用户名已存在！';

				case CRErrorCode::EMAIL_OCCUPIED:
					return '邮箱已存在！';

				case CRErrorCode::NO_PRIVILEGE:
					return '您没有权限执行此项操作（可能是由于会话超时，需要重新登录）！';

				case CRErrorCode::INVALID_USERNAME:
					return '无效的用户名！';

				case CRErrorCode::INVALID_EMAIL:
					return '无效的邮箱！';

				case CRErrorCode::UNKNOWN_ERROR:
					return '未知错误！';

				case CRErrorCode::WRONG_PASSWORD:
					return '密码错误！';

				case CRErrorCode::IN_DEVELOP:
					return '功能开发中！';

				case CRErrorCode::UNABLE_TO_CONNECT_REDIS:
					return '连接Redis数据库错误！';

				case CRErrorCode::UNABLE_TO_CONNECT_MYSQL:
					return '连接Mysql数据库错误！';

				case CRErrorCode::NOT_LOGED:
					return '您尚未登录！';

				case CRErrorCode::USER_NOT_EXIST:
					return '用户不存在！';

				case CRErrorCode::INVALID_REQUEST:
					return '不合理的请求！';

				case CRErrorCode::UNKNOWN_REQUEST:
					return '未知的请求！';

				case CRErrorCode::CAN_NOT_BE_EMPTY:
					return '不能为空！';

				case CRErrorCode::FAIL:
					return '操作失败！';

				case CRErrorCode::INCOMPLETE_CONTENT:
					return '内容不完整，存在未填项！';

				case CRErrorCode::FILE_NOT_UPLOADED:
					return '文件上传失败！';

				case CRErrorCode::RECORD_NOT_EXIST:
					return '该条记录未找到！';

				case CRErrorCode::USER_IS_BLOCKED:
					return '该用户已被锁定！';

				case CRErrorCode::INVALID_PASSWORD:
					return '无效的密码！';

				default:
					return '未知错误！('.$errno.')';
			}
		}
	}
