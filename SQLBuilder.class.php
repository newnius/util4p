<?php

class SQLBuilder
{
  private $sql = '';

  public function insert($table, $key_values){
    $this->sql = "INSERT INTO `$table`(";
    foreach($key_values as $key => $value){
      $this->sql .= "`$key`, ";
    }
    $this->sql = substr($this->sql, 0, strlen($this->sql)-2);
    $this->sql .= ") VALUES(";
    foreach($key_values as $key => $value){
      if($value === null){
        $this->sql .= "null, ";
      }else if($value == '?'){
        $this->sql .= "?, ";
      }else{
        $this->sql .= "'$value', ";
      }
    }
    $this->sql = substr($this->sql, 0, strlen($this->sql)-2);
    $this->sql .= ")";
  }

  public function select($table, $selected_rows=array()){
    $this->sql = 'SELECT ';
    foreach( $selected_rows as $row ){
      $this->sql .= "`$row`, ";
    }
    if(count($selected_rows) == 0){
      $this->sql .= ' * ';
    }else{
      $this->sql = substr($this->sql, 0, strlen($this->sql)-2);
    }
    $this->sql .= " FROM `$table` ";
  }

  /*
   *
   */
  public function update($table, $key_values){
    if($key_values==null || count($key_values) == 0){
      return ;
    }
    $this->sql = "UPDATE `$table` SET ";
    foreach($key_values as $key=>$value){
      if($value === null){
        $this->sql .= " `$key` = null, ";
      }else if($value == '?'){
        $this->sql .= " `$key` = ?, ";
      }else{
        $this->sql .= " `$key` = '$value', ";
      }
    }
    $this->sql = substr($this->sql, 0, strlen($this->sql)-2);
  }


  public function delete($table){
    $this->sql = "DELETE FROM `$table` ";
  }

  public function where($arr, $opts=array()){
    $where_clause_cnt = 0;
    $keys = array_keys($arr);

    foreach($keys as $key){
      if(!isset($opts[$key])){
        $opts[$key] = '=';
      }
      if($where_clause_cnt == 0){
        $this->sql .= ' WHERE ';
      }else{
        $this->sql .= ' AND ';
      }
      if($arr[$key] == null){
        $this->sql .= " `$key` is null ";
        $where_clause_cnt++;
      }else if($arr[$key] == '?'){
        $this->sql .= " `$key` {$opts[$key]} ? ";
        $where_clause_cnt++;
      }else{
        $this->sql .= " `$key` {$opts[$key]} '{$arr[$key]}' ";
        $where_clause_cnt++;
      }
    } 
  }

  public function order($by_arr, $order = 'desc'){
    if($by_arr==null || count($by_arr) == 0){
      return ;
    }
    $this->sql .= ' ORDER BY ';
    foreach( $by_arr as $by ){
      $this->sql .= "`$by`, ";
    }
    $this->sql = substr($this->sql, 0, strlen($this->sql)-2);
    $this->sql .= $order=='desc'?' desc ':' asc ';
  }

  public function limit($offset, $cnt){
    $this->sql .= " LIMIT $offset, $cnt";
  }

  public function build(){
    return $this->sql;
  }

}
