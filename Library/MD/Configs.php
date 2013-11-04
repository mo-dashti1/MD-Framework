<?php
/*
This class parses configuration file to set main constant of program and route file to determine specific parameters in URL 
*/
class MD_Configs {
	
	const DB_ADAPTER = 'pdo_mysql';
	const DB_HOST = 'localhost';
	const DB_NAME = 'test';
	const DB_USER = 'root';
	const DB_PASS = '';
	public $options = array();
	
	protected $routs = array();
	protected $map ;
	public function __construct($config_file,$routs,&$map)
	{
		$this->options = $this->_ParseConfig($config_file);
		$this->map = $map;
		$this->routs = $this->_ParseRout($routs);
		$map_options = $this->_GetMapOptions();
		$this->_AddRoutsToMap($map_options);
	}
	/*
	input : route to configuration file
	output : configuration object
	*/
	protected function _ParseConfig($config){
		$ini_array = parse_ini_file($config);

		$ini = new stdclass;
		foreach ($ini_array as $key=>$value) {
			$c = $ini;
			foreach (explode(".", $key) as $key) {
				if (!isset($c->$key)) {
					$c->$key = new stdclass;
				}
				$prev = $c;
				$c = $c->$key;
			}
			$prev->$key = $value;
		}
		
		return $ini;
	}
	/*
	input : ---
	output : an array of database parameteres 
	*/
	public function GetDbParams()
	{
		return $params = array(
							 'adapter'  =>	self::DB_ADAPTER,
							 'host'  	=>	self::DB_HOST,
							 'db_name'  =>	self::DB_NAME,
							 'db_user'  =>	self::DB_USER,
							 'db_pass'  =>	self::DB_PASS,
						);
	}
	/*
	input : ---
	output : current route
	*/
	public function GetRouts()
	{
		return $this->routs;
	}
	
	protected function _ParseRout($config){
		$ini_array = parse_ini_file($config);

		$ini = new stdclass;
		foreach ($ini_array as $key=>$value) {
			$c = $ini;
			foreach (explode(".", $key) as $key) {
				if (!isset($c->$key)) {
					$c->$key = new stdclass;
				}
				$prev = $c;
				$c = $c->$key;
			}
			$prev->$key = $value;
		}

		
		return $ini;
	}
	
	protected function _GetMapOptions()
	{
		$routs = $this->routs;
		$routoptions = array();
		foreach($routs as $controller => $action)
		{
			foreach($action as $key => $val)
			{
				$routoptions[$controller]['rout'][$key] = $val->rout;
				$routoptions[$controller]['format'][$key] = $val->format;
				
				if(!empty($val->params))
				foreach($val->params as $key2 => $val2)
				{
				$routoptions[$controller]['params'][$key][$key2] = $val2;
				}
				else
				$routoptions[$controller]['params'][$key] = NULL;
				
			}
		}
		
		return $routoptions;
	}
	
	protected function _AddRoutsToMap($routs_map_options)
	{
		foreach($routs_map_options as $controllers => $controller )
		{
			$parameters["values"]["controller"] = 'index';
			$parameters["values"]["action"] = 'index';
			$parameters["values"]["format"] = "html";
			
			$this->map->add(null, '/',$parameters);
			
			foreach($controller['rout'] as $action => $route )
			{
				// add a complex named route
				//////////////////////////////////////////////////////////////////
				$parameters = array();
				$rout = "/$controllers/$action";
				if(is_array($controller['params'][$action]))
				{
					foreach($controller['params'][$action] as $key => $v)
					{
						$rout .= "{:$key:$v}";
						$parameters["params"][$key] = $v;
					}
				}
				if(isset($controller['format'][$action]))
				$rout .= "{:format:".$controller['format'][$action]."}";
				else
				$rout .= "{:format}";
				$parameters["values"]["controller"] = $controllers;
				$parameters["values"]["action"] = $action;
				$parameters["values"]["format"] = "html";
				//echo $rout."<br>";
				$this->map->add($controllers."_".$action, $rout, $parameters);
				//////////////////////////////////////////////////////////////////
	
	
			}
			foreach($controller['rout'] as $action => $route )
			{
			// add a simple unnamed route with params
			/////////////////////////////////////////////////////////////////
				$rout = "/$controllers";
				if(is_array($controller['params'][$action]))
					foreach($controller['params'][$action] as $key => $v)
						{
							$rout .= "{:$key:$v}";
							$parameters["params"][$key] = $v;
						}
				if(isset($controller['format'][$action]))		
					$rout .= "{:format:".$controller['format'][$action]."}";
				else
					$rout .= "{:format}";
				$parameters = array();
				
				$parameters["values"]["controller"] = $controllers;
				$parameters["values"]["action"] = 'index';
				$parameters["values"]["format"] = "html";
				//echo $rout."<br>";
				$this->map->add(null, $rout,$parameters);
				break;
			//////////////////////////////////////////////////////////////////
			}
			
		}
	}
	
}
