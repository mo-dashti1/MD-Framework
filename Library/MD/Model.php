<?php
abstract class MD_Model implements MD_Model_Interface
{
   private $db;
   private $params;
   private $default_adapter = NULL;
   private $default_table = NULL;
   private $primary_key = NULL;
   public function __construct(){
		
	}
	
	public function set_default_adapter(){
		
		$config = MD_Registry::get('config');
		$adapter = $config->options->db->adapter;
		if(preg_match('/^pdo_.*/i', $adapter))
		{
			$db_adapter = 'MD_Model_'.$adapter;
			$this->default_adapter = new $db_adapter($this->default_table,$this->primary_key,$config->options->db->params);
		}
		elseif(preg_match('/Mysql.*/i' , $adapter))
		{
			$class = 'MD_Model_Mysql';
			$this->default_adapter = new $db_adapter($this->default_table,$this->primary_key,$config->options->db->params);
		}
		else
		{
			throw new MD_Model_Exception("Driver $diruver Not Found");
		}
	}
	/**
	 * connect to database
	 * 
	 */
	static public function getTable() {

        return static::$table;
	}
	static public function getPK() {

        return static::$primary_key;
	}
	 
	public function connect(){
		$this->default_table = self::getTable();
		$this->primary_key = self::getPK();
		
		if(!$this->default_adapter);
			self::set_default_adapter();
		
		return $this->default_adapter;	
		}
	
	/**
	 * 
	 * fetch all field name in table and store that to $fields property
	 */
 	public function set_field_name(){}
 	
 	/**
 	 * 
 	 * create SELECT $fields FROM $table
 	 * 
 	 * $fields must be an array.
 	 * $fields = array('id','name','fname')
 	 * @param array $fields
 	 */
 	public function find($fields = array()){}
 	
 	/**
 	 * set condition for fetch data
 	 * $fields must be an array such as : 
 	 * $fields = array('id'=>$id , 'name'=>'Artemis');
 	 * @param array $fields
 	 */
 	public function where($fields){}
 	
 	/**
 	 * join tow table 
 	 * 
 	 * @param string $wiht 
 	 * @param string $field_table1
 	 * @param string $field_table2
 	 * @param array $cond
 	 */
 	public function join($wiht, $field_table1, $field_table2, $cond=array()){}
 	
 	/**
 	 * 
 	 * order data
 	 * @param string $order field name to order
 	 * @param string $dir ASC or DESC
 	 */
 	public function order($order ='id', $dir = 'ASC'){}
 	
 	/**
 	 * 
 	 * limit fetch data
 	 * @param int $start
 	 * @param int $results
 	 */
 	public function limit($start , $results){}
 	
 	/**
 	 * fetch all rows from last query;
 	 * 
 	 */
 	public function fetchAll(){}
 	
 	/**
 	 * fetch one row from last query
 	 * 
 	 */
 	public function fetchOne(){}
 	
 	/**
 	 * 
 	 * create dynamic  findBy[$field] method
 	 * @param string $method field name
 	 * @param string $args
 	 * @return $this 
 	 */
 	public function __call($method, $args){}
 	
 	/**
 	 * 
 	 * return number of rows from last query
 	 * @return int
 	 */
 	 public function numRows(){}
 	
 	/**
 	 * 
 	 * create and validating data to create or update
 	 * @param array $values
 	 * @param bool $escape
 	 * @return true or false
 	 */
 	public function create($values = array(), $escape = false){}
 	
 	/**
 	 * 
 	 * insert data to db
 	 * 
 	 */
 	public function insert(){}
 	
 	/**
 	 * 
 	 * update data
 	 * @param string $pk_value
 	 */
 	public function update($pk_value){}
 	
 	/**
 	 * Delete a row
 	 * 
 	 * @param string $pkValue
 	 */
 	 public function delete($pkValue = 0){}
	
}