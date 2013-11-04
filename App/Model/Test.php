<?php
class Model_Test extends MD_Model{
	public static $table='test';
	public static $primary_key='id';
	public $db;
	public function __construct()
	{
		//var_dump(MD_Registry::get('config'));
		
		$this->db = $this->connect();
		
		$db->create();
		
		$this->db->find(array("test","id"));
		$this->db->where(array("id <= 12","id >= 2"));
		$this->db->order("id","DESC");
		$this->db->limit(0,10);
		
		//$this->db->create(array("test"=>"abcd333"));
		//$this->db->insert();
		//$this->db->update(8);
		//$this->db->delete(8);
		
		var_dump($db->fetchAll());
		//var_dump($db->fetchOne());
	}
	public function updateusers($users_id,$values){
		foreach($users_id as $key=>$value)
		{
			$this->db->create(array("test"=>"abcd333"));
			$this->db->update(8);
		}
	}
	
}