<?php
class CRObject
{
	private $map = array();

	public function set($key, $value){
		$this->map[$key] = $value;
		return true;
	}

	public function get($key, $default=null){
		if(isset($this->map[$key]) && !is_null($this->map[$key]) ){
			return $this->map[$key];
		}
		return $default;
	}

	public function getInt($key, $default=null){
		if(isset($this->map[$key]) && !is_null($this->map[$key]) && is_numeric($this->map[$key])){
			return intval($this->map[$key]);
		}
		return $default;
	}

	
	public function toArray(){
		return $map;
	}


	//for debug only
	public function list_all(){
		var_dump($this->map);
	}

}
