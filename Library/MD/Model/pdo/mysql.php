<?php
/**
 * Mysql Driver For MD Framework
 *
 */


class MD_Model_pdo_mysql implements MD_Model_Interface
{
	private $bindValues;
	/**
	 *
	 * store Database parameters
	 * @var array
	 */
	private $params;
	/**
	 *
	 * store PDO object
	 * @var Object
	 */
	
	private $db;

	/**
	 * store all fields name in the table
	 * @access private
	 * @var array
	 */
	private $fields;

	/**
	 *
	 * store fields name for create and update command
	 * @var array
	 */
	private $cu_fields;

	/**
	 *
	 * store last query
	 * @var string
	 */
	public $query;



	/**
	 *
	 * store table name
	 * @var string
	 */
	private $table;

	/**
	 *
	 * store result
	 * @var array
	 */

	private $result;
	/**
	 *
	 * fields of that need to validate
	 * @var array
	 */
	protected  $validation;

	/**
	 *
	 * store table primary key
	 * @var string
	 */
	private $pk = 'id';

	/**
	 *
	 * construct of class.create connection and set $this->table
	 * @param string $table
	 */
	public function __construct($table ,$pk = 'id',$params)
	{
		$this->pk = $pk;
		$this->db_params = $params;
		$this->table = strtolower($table);
		$this->connect();
	}
	/**
	 * 
	 * Enter description here ...
	 * @throws Exception
	 */
	public function connect()
	{
		if(!class_exists('PDO',false))
			throw new Exception("PHP PDO package is required.");
		$server = $this->db_params->host ;
		$username = $this->db_params->username;
		$password = $this->db_params->password;
		$database = $this->db_params->dbname;
		$opt = array(
				// an SQL command to execute when connecting
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
			);			
		$this->db = new PDO('mysql:host='.$server.';dbname='.$database ,$username, $password, $opt);

	}

	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::set_field_name()
	 */
	public function set_field_name()
	{
		$table = $this->table;
		//select all fields from $this->table
		$result = $this->db->prepare("select * from $table");
		$result->execute();
		// $result;
		$i = 0 ;
		while($i < $result->columnCount())
		{
			//  $i;
			$meta = $result->getColumnMeta($i);
			$this->fields[$meta['name']]=  '' ;
			$i++;
		}


	}
	
	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::find()
	 */
	public function find($fields = array())
	{
		if(!empty($fields))
		{
			$field = implode(',', $fields);
		}else {
			$field = '*';
		}

		$this->query = "SELECT $field FROM $this->table ";

		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::where()
	 */
	public function where($where = array())
	{
		try {
				
			if(!is_array($where))
			{
				throw new MD_Model_Exception('WHERE clause must be an array');
			}
			foreach($where as $field=>$value)
			{
				$q[] = $value;
			}
			//var_dump($q);
			$this->query .= ' WHERE '.implode(' AND ', $q);
			foreach($where as $field=>$value)
			{
				$this->bindValues[":$field"] = $value;
			}
	
			return $this;
		}
		catch (MD_model $e)
		{
			echo $e->getMessage , $e->getLine();
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::where()
	 */
	public function orwhere($where = array())
	{
		try {
				
			if(!is_array($where))
			{
				throw new MD_Model_Exception('WHERE clause must be an array');
			}
			foreach($where as $field=>$value)
			{
				$q[] = $value;
			}
			//var_dump($q);
			$this->query .= ' WHERE '.implode(' OR ', $q);
			foreach($where as $field=>$value)
			{
				$this->bindValues[":$field"] = $value;
			}
	
			return $this;
		}
		catch (MD_model $e)
		{
			echo $e->getMessage , $e->getLine();
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::join()
	 */
	function join($with , $field_table1 , $field_table2, $cond = array())
	{
		if(empty($cond))
                    $this->query .= " LEFT JOIN $with ON $field_table1 = $field_table2 ";
		elseif(is_array($cond) AND !empty($cond))
		{
			//print_r($cond);exit();
			foreach($cond as $k=>$v)
			{
				$condition[] = $k.'='.$v;
			}
			$cond = implode(' AND ',$condition);
			//echo $cond ; exit();
			$this->query .= " LEFT JOIN $with ON $field_table1 = $field_table2  WHERE $cond ";
			 
		}else
		{
			$this->query .= " LEFT JOIN $with ON $field_table1 = $field_table2 WHERE $cond ";
		}

		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::order()
	 */
	function order($order = '',$dir = '')
	{
		$this->query .= " ORDER BY $order $dir ";
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::order()
	 */
	function groupBy($order = '')
	{
		$this->query .= " GROUP BY $order";
		return $this;
	}
	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::limit()
	 */
	function limit($start , $results)
	{
		$this->query .= " LIMIT $start , $results ";
		//$this->query .= " LIMIT :start , :results ";
		//$this->bindValues[':start']= $start ;
		//$this->bindValues[':results']= $results ;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::query()
	 */
	function query($query,$type='select',$result_type = 'FETCH_ASSOC')
	{
		$this->query = $query;
		$this->result = $this->db->prepare($this->query);
		$this->result->execute();
		$this->result->errorInfo();
		//var_dump("{${PDO::FETCH_ASSOC}}");
		echo "{${PDO::FETCH_ASSOC}}".'  <== *';
		switch($type)
		{
			case 'update' : return  true;break;
			case 'insert' : return  true;break;//PDO::$result_type"
			//case 'select' : return $this->result->fetchAll(PDO::FETCH_ASSOC);break;
			//case 'select' : return $this->result->fetchAll(call_user_func("{${PDO::$result_type}}"));break;
			case 'select' : return $this->result->fetchAll("{${PDO::FETCH_ASSOC}}");break;
			case 'delete' : return  true;break;
		}
		//$this->query = $query;
		//$this->query .= " LIMIT :start , :results ";
		//$this->bindValues[':start']= $start ;
		//$this->bindValues[':results']= $results ;

		return true;
	}

	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::fetchAll()
	 */
	public function fetchAll()
	{
		$this->result = $this->db->prepare($this->query);

		if(!empty($this->bindValues))
		{
			foreach($this->bindValues as $key=>$value)
			{
				if(is_int($value))
				$this->result->bindValue($key , $value , PDO::PARAM_INT);
				else
				$this->result->bindValue($key , $value);
			}
		}
		$this->result->execute();
		$this->result->errorInfo();
		return $this->result->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::fetchOne()
	 */
	public function fetchOne()
	{
		$this->result = $this->db->prepare($this->query);
		if(!empty($this->bindValues))
			foreach($this->bindValues as $key=>$value)
			{
				if(is_int($value))
				$this->result->bindValue($key , $value , PDO::PARAM_INT);
				else
				$this->result->bindValue($key , $value);
			}
		$this->result->execute();
		return $this->result->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::__call()
	 */
	public function __call( $method, $args )
	{
			
		if (preg_match( '/findBy(.*)/i', $method, $found ) )
		{
			$field = strtolower($found[1]);//get field name
			$this->set_field_name();
			if ( array_key_exists(  $field, $this->fields ) )
			{
				$sql = "SELECT * FROM $this->table WHERE  $field = :$field";
				$this->query = $sql;

				foreach($args as $k=>$v)
				$this->bindValues[':'.$field] = $v;
					
				return $this;
			}
		}

		return false;
	}

	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::numRows()
	 */
	public function numRows()
	{

		$this->result = $this->db->prepare($this->query);
		if(!empty($this->bindValues))
			foreach($this->bindValues as $key=>$value)
			{
				if(is_int($value))
				$this->result->bindValue($key , $value , PDO::PARAM_INT);
				else
				$this->result->bindValue($key , $value);
			}

		$this->result->execute();
		return $this->result->rowCount();
	}


	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::create()
	 */
	public function create($values = array(), $escape = false)
	{

		if(!is_array($values)) return false;
		try
		{

			if($escape)
			{
				$values = $this->sanitize($values);
			}
			$this->set_field_name();
			foreach($values as $field=>$value)
			{
				if(in_array($field , array_keys($this->fields)))
				{
					$this->cu_fields[$field] = $value;
				}
			}

			return true;
		}
		catch(Exception $e)
		{
			$e->getMessage();
			return false;
		}

	}

	/**
 	* (non-PHPdoc)
	 * @see MD_Model_Interface::insert()
	 */
	public function insert()
	{
		//print_r($this->cu_fields);
		foreach($this->cu_fields as $field=>$v)
		$ins[] = ':'.$field;

		$ins = implode(',' , $ins);
		$fields = implode(',',array_keys($this->cu_fields));
		$sql = "INSERT INTO $this->table ($fields) VALUES ($ins)";

		$this->result = $this->db->prepare($sql);
		foreach($this->cu_fields as $f=>$v)
		{
			$this->result->bindValue(':'.$f , $v);
		}
		$res=$this->result->execute() ;
		if($res)	return $this->db->lastInsertId() ;
		else 	return $res ;
		//print_r($this->result->errorInfo());
		
	}


	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::update()
	 */
	public function update($pk_value = 0)
	{
		foreach($this->cu_fields as $field=>$v)
		{
			
				$up[] = $field.'= :'.$field;
			
		}

		$up = implode(',' , $up);

		$sql = "UPDATE $this->table SET $up WHERE $this->pk = :$this->pk";
		$this->cu_fields[$this->pk] = $pk_value;


		$this->result = $this->db->prepare($sql);

		foreach($this->cu_fields as $f=>$v)
		{
			$this->result->bindValue(':'.$f , $v);
		}

		return $this->result->execute();
		//print_r($this->result->errorInfo());	
	}

	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Interface::delete()
	 */
	public function delete($pkValue = 0)
	{
		if($pkValue == 0) return false;

		$sql = "DELETE FROM $this->table WHERE $this->pk = $pkValue";
		$this->result = $this->db->prepare($sql);
		$this->result->execute();
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see MD_Model_Abstract::lastQuery()
	 */
	public function lastQuery()
	{
		return $this->query;
	}




}