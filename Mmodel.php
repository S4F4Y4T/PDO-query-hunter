<?php

class Mmodel{
	
	private $host 	= host;
	private $dbname = dbname;
	private $user 	= user;
	private $pass 	= pass;
	
	protected $db = array();
	
	public function __construct(){
		
		$dsn  = "mysql:dbname=".$this->dbname."; host=".$this->host;
		$user = $this->user;
		$pass = $this->pass;
		
		$this->db = new Database($dsn, $user, $pass);
	}
}
?>
