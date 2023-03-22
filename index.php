<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config.php";
require_once "Mmodel.php";
require_once "Database.php";

class Home extends Mmodel{
	
	
	public function fetch(){
		$this->db->select('id');
		$this->db->table('transactions');
		$this->db->where('id', 4);
		$this->db->where('id >', 2);
		$this->db->limit(1);
		$result = $this->db->execute();

		return [
		        'obj' => $result->fetch(),
		        'arr' => $result->fetch_array(),
		        'count' => $result->count()
		    ];

    
	}
}

$home = new Home();
print_r($home->fetch());

?>


