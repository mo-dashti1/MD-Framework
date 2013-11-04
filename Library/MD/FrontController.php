<?php
session_start();
class MD_FrontController{
	
	protected $configs = NULL;
	protected $map;
	protected $controller;
	protected $action;
	protected $params;
	protected $router;
	protected $dispatcher;
	protected $frontController;
	protected $route;
	protected $route_params;
	protected $db_adapter;

	public function __construct($config,$routs)
	{
		$this->_initAutoload();
		$this->_initRout($config,$routs);
		$this->_register();
		//$this->_initAutoload();
		$this->_initModel();
		$this->_initControllers();
		//$this->_initView();
		
	}
	protected function _initAutoload()
	{
		
	}
	protected function _register()
	{
		MD_Registry::set('page',NULL);
		MD_Registry::set('item',NULL);
	}

	protected function _initRout($config,$routs)
	{
		$this->map = require "Library/MD/router/scripts/instance.php";
		$this->configs = new MD_Configs($config,$routs,$this->map);
		// get the route
		$pre_path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$baseURL = $this->configs->options->baseurl ;
		$path  = substr($pre_path,strlen($baseURL)) ;
		
		$this->route = $this->map->match($path, $_SERVER);
		//var_dump($this->route);
	}
	

	protected function _initModel()
	{
		MD_Registry::set('config', $this->configs);
		
	}
	
	protected function _initControllers()
	{
		if($this->route)
		{
			include('Library/MD/Controller.php');
			$params = $this->route->values;
			foreach($params as $key => $value)
			{
				if($key == 'controller')
					$this->controller = $value;
				elseif($key == 'action')
					$this->action = $value;	
				else
					$this->params[$key] = trim($value,'\/');
										
			}
			//var_dump($this->params);
			
			
			$controller_name = ucfirst($this->controller).'Controller';
			$action_name = ucfirst($this->action).'Action';
			$class = APP_PATH.DS.'Controller'.DS.ucfirst($this->controller).'Controller.php';
			if(file_exists($class))
			{
				include $class;
			
				$controller = new $controller_name();
				$controller->params = $this->params;
				$controller->baseurl = 'http://'.$this->configs->options->baseurl;
				$controller->controller = ucfirst($this->controller);
				$controller->action = ucfirst($this->action);
				$controller->_init_variables();
				//$controller->post = $this->post;
				//$controller->get = $this->get;
				//$controller->test = 'aaaabbbb';
				//$controller->session &= $this->session;
				$methods = get_class_methods($controller);
				if(in_array($action_name,$methods))
				{
					call_user_func_array(array($controller,$action_name), array());			
				}
				else
				{
					echo 'Action : '.ucfirst($this->action).'Action'.' not found';
				}
			}
			else
			{
				echo "Controller : ".ucfirst($this->controller).' not found';
			}
		}
		else
			{
				echo "Page not found!!";
			}
	}
	
	protected function _initView()
	{
	}
	
}