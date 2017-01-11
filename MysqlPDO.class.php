<?php
class MysqlPDO
{
  private $dbh;
  private static $isDebug = false;

  public static function enableDebug(){
    MysqlPDO::$isDebug = true;
  }

  public function MysqlPDO()
  {
    $this->connect();
  }

  private function connect()
  {
    try {
      $this->dbh = new PDO('mysql:host='.DB_HOST.';charset=utf8;port='.DB_PORT.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
      return true;
    } catch (PDOException $e) {
      $this->dbh = null;
      if(MysqlPDO::$isDebug)
      {
        echo $e->getMessage();
      }
      return false;
    }
  }

  public function execute($sql, $a_params, $need_inserted_id=false)
  {
    if($this->dbh == null){
      return 0;
    }
		try{
    	$stmt = $this->dbh->prepare($sql);
			$stmt->execute($a_params);
    	$affected_rows = $stmt->rowCount();
			if($need_inserted_id){
				return $affected_rows>0?$this->dbh->lastInsertId():null;
			}
    	$this->dbh = null;
    	return $affected_rows;
		}catch(Exception $e) {
      if(MysqlPDO::$isDebug)
      {
        echo $e->getMessage();
      }
			return 0;
		}
  }


  function executeQuery($sql, $a_params)
  {
    if($this->dbh == null){
      return array();
    }
		try{
			$stmt = $this->dbh->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_ASSOC); //Could also be FETCH_NUM, FETCH_OBJ, or FETCH_CLASS
    	$result = null;
    	if($stmt->execute($a_params)){
     		$result = $stmt->fetchAll();
    	}
    	$this->dbh = null;
    	return $result;
		}catch(Exception $e) {
      if(MysqlPDO::$isDebug)
      {
        echo $e->getMessage();
      }
			return null;
		}
  }

}
