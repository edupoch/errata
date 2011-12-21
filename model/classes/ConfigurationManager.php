<?php
class ConfigurationManager{
		
	private $host;
	private $database;
	private $user;
	private $password;
	private $databasePrefix;
	private $lang;
		
	public function __construct(){			
				
		$conf = simplexml_load_file('../model/conf.xml');	
		
		foreach($conf as $key => $value){
			$this -> __set($key, $value);
		}
		
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this -> $property;
		}
	}

	private function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this -> $property = $value;
		}

		return $this;
	}
	
}

?>
