<?php
	require_once('util.php');
	require_once('CRObject.class.php');
	require_once('MysqlPDO.class.php');
	require_once('SQLBuilder.class.php');

	class CRLogger
	{
		const LEVEL_DEBUG = 1;
		const LEVEL_INFO = 2;
		const LEVEL_WARN = 3;
		const LEVEL_ERROR = 4;

		private static $db_table = 'cr_log';
		private static $log_file = 'cr_log.log';

		public static function configure($config)
		{
			self::$db_table = $config->get('db_table', self::$db_table);
			self::$log_file = $config->get('log_file', self::$log_file);
		}

		public static function log2db($log)
		{
			$tag = $log->get('tag');
			$level = $log->getInt('level', self::LEVEL_INFO);
			$ip = $log->get('ip', cr_get_client_ip());
			$time = $log->getInt('time', time());
			$content = $log->get('content');

			$key_values = array(
				'tag' => '?', 'level' => '?', 'ip' => '?', 'time' => '?', 'content' => '?'
			);
			$builder = new SQLBuilder();
			$builder->insert(self::$db_table, $key_values);
			$sql = $builder->build();
			$params = array( $tag, $level, ip2long($ip), $time, $content );
			return (new MysqlPDO())->execute($sql, $params) == 1;
		}

		public static function log2file()
		{
			return true;
		}

		public static function search($filter)
		{
			$tag = $filter->get('tag');
			$level_min = $filter->getInt('level_min');
			$ip = $filter->get('ip');
			$time_begin = $filter->getInt('time_begin');
			$time_end = $filter->getInt('time_end');
			$offset = $filter->getInt('offset', 0);
			$limit = $filter->getInt('limit', -1);
			$order = $filter->get('order');

			$selected_rows = array('id', 'tag', 'level', 'ip', 'time', 'content');
			$where_arr = array();
			$opt_arr = array();
			$order_arr = array();
			$params = array();

			if(!empty($tag)){
				$where_arr['tag'] = '?';
				$params[] = $tag;
			}
			if(!empty($level_min)){
				$where_arr['level'] = '?';
				$opt_arr['level'] = '>=';
				$params[] = $level_min;
			}
			if(!empty($ip)){
				$where_arr['ip'] = '?';
				$params[] = ip2long($ip);
			}
			if(!empty($time_begin)){
				$where_arr['time'] = '?';
				$opt_arr['time'] = '>=';
				$params[] = $time_begin;	
			}
			/* TODO here is a bug
			if(!empty($time_end)){
				$where_arr['time'] = '?';
				$opt_arr['time'] = '<=';
				$params[] = $time_end;
			}
			*/

			switch($order){
				case 'latest':
					$order_arr['time'] = 'desc';
					break;
				default:
					$order_arr['id'] = 'desc';
				break;
			}
			$builder = new SQLBuilder();
			$builder->select(self::$db_table, $selected_rows);
			$builder->where($where_arr, $opt_arr);
			$builder->order($order_arr);
			$builder->limit($offset, $limit);
			$sql = $builder->build();
			$logs = (new MysqlPDO())->executeQuery($sql, $params);
			return $logs;
		}

  }
