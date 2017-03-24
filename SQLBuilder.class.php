<?php

	class SQLBuilder
	{
		private $sql = '';

		/*
		 */
		public function insert($table, $key_values)
		{
			$this->sql = "INSERT INTO `$table`";
			$keys = array_keys($key_values);
			if(count($keys) && $keys[0] !== 0)
			{ //support `INSERT INTO table VALUES (...)
				$this->sql .= '(';
				foreach($keys as $key){
					$this->sql .= "`$key`, ";
				}
				$this->sql = substr($this->sql, 0, strlen($this->sql)-2);
				$this->sql .= ')';
			}
			$this->sql .= " VALUES(";
			foreach($key_values as $key => $value)
			{
				if($value === null){
					$this->sql .= "null, ";
				}else if($value === '?'){
					$this->sql .= "?, ";
				}else{
					$this->sql .= "'$value', ";
				}
			}
			$this->sql = substr($this->sql, 0, strlen($this->sql)-2);
			$this->sql .= ")";
		}


		/*
		 */
		public function select($table, $selected_rows=array())
		{
			$this->sql = 'SELECT ';
			foreach( $selected_rows as $row )
			{
				if(strpos($row, ' ') || strpos($row, '(')){//contain functions, not pure row name
					$this->sql .= "$row, ";
				}else{
					$this->sql .= "`$row`, ";
				}
			}
			if(count($selected_rows) === 0){
				$this->sql .= ' * ';
			}else{
				$this->sql = substr($this->sql, 0, strlen($this->sql)-2);
			}
			$this->sql .= " FROM `$table` ";
		}


		/*
		 */
		public function update($table, $key_values)
		{
			if($key_values===null || count($key_values) === 0){
				return ;
			}
			$this->sql = "UPDATE `$table` SET ";
			foreach($key_values as $key=>$value)
			{
				if($value === null){
					$this->sql .= " `$key` = null, ";
				}else if($value === '?'){
					$this->sql .= " `$key` = ?, ";
				}else{
					$this->sql .= " `$key` = '$value', ";
				}
			}
			$this->sql = substr($this->sql, 0, strlen($this->sql)-2);
		}


		/*
		 */
		public function delete($table)
		{
			$this->sql = "DELETE FROM `$table` ";
		}


		/*
		 */
		public function where($arr, $opts=array())
		{
			$where_clause_cnt = 0;
			$keys = array_keys($arr);

			foreach($keys as $key)
			{
				if(!isset($opts[$key]))
				{
					$opts[$key] = '=';
				}
				if($where_clause_cnt == 0)
				{
					$this->sql .= ' WHERE ';
				}else{
					$this->sql .= ' AND ';
				}
				if($arr[$key] === null){
					if($opts[$key] === '=')
					{
						$this->sql .= " `$key` is null ";
					}else{
						$this->sql .= " `$key` is not null ";
					}
					$where_clause_cnt++;
				}else if($arr[$key] === '?' || in_array(strtoupper($opts[$key]), array('IN', 'BETWEEN', 'LIKE')) ){
					$this->sql .= " `$key` {$opts[$key]} {$arr[$key]} ";
					$where_clause_cnt++;
				}else{
					$this->sql .= " `$key` {$opts[$key]} '{$arr[$key]}' ";
					$where_clause_cnt++;
				}
			}
		}


		/*
		 */
		public function group($by_arr)
		{
			if($by_arr===null || count($by_arr) === 0){
				return ;
			}
			$this->sql .= ' GROUP BY ';
			foreach( $by_arr as $by )
			{
				$this->sql .= "`$by`, ";
			}
			$this->sql = substr($this->sql, 0, strlen($this->sql)-2);
		}


		/*
		 */
		public function order($by_arr)
		{
			if($by_arr===null || count($by_arr) === 0){
				return ;
			}
			$this->sql .= ' ORDER BY ';
			foreach( $by_arr as $by => $order )
			{
				$this->sql .= "`$by` $order, ";
			}
			$this->sql = substr($this->sql, 0, strlen($this->sql)-2);
		}


		/*
		 */
		public function limit($offset, $cnt)
		{
			if($cnt < 0){// support LIMIT 0 ,-1 to get all records
				return $this->sql;
			}
			$this->sql .= " LIMIT $offset, $cnt";
		}


		/*
		 */
		public function build()
		{
			return $this->sql;
		}

	}
