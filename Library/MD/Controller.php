<?php
abstract class MD_Controller implements MD_Controller_Interface
{
   public $db;
   public $params;
   public $controller;
   public $action;
   public $has_template = true;
   public $post = NULL;
   public $get = NULL;
   public $session = NULL;
   public $view = array();
   public $baseurl;
   
   public function __construct(){
		
	}
	public function parent_func_test(){
		echo "parent func";
	}
	
	public function get_session($name){
		if(isset($_SESSION[$name]))
			return $_SESSION[$name];
		else
			return false;
	}
	public function set_session($name){
		if(is_array($name))
			foreach($name as $key => $value)
				$_SESSION[$key] = $value;
	}
	public function unset_session($name){
		unset($_SESSION[$name]);
	}
	
	
	public function _init_variables(){
		if(isset($_POST))
		{
			foreach($_POST as $key => $value)
				$this->post[$key] = $value;
		}
		if(isset($_GET))
		{
			foreach($_GET as $key => $value)
				$this->get[$key] = $value;
		}
		if(isset($_SESSION))
		{
			$this->session =& $_SESSION;
		}
	}
	public function _redirect($location){
		if(preg_match('/http.*/i', $location))
		{
			header("Location: $location");
			exit;
		}
		else
		{
			$location = $this->baseurl.$location;
			header("Location: $location");
			exit;
		}
	}
	
	public function loadView($strViewPath, $arrayOfData)
	{
		// This makes $arrayOfData['content'] turn into $content
		extract($arrayOfData);
		
		// Require the file
		ob_start();
		require($strViewPath);
		
		// Return the string
		$strView = ob_get_contents();
		ob_end_clean();
		return $strView;
	}
	public function render()
	{
		if($this->has_template)
		{
			$config = MD_Registry::get('config');
			$dafult_template = $config->options->layout->layoutPath.'/'.$config->options->layout->default.'.phtml';
			$action_template = APP_PATH.'/View/'.strtolower($this->controller).'/'.strtolower($this->action).'.phtml';
			$this->view['content_template']= $this->loadView($action_template,$this->view);
			
			echo $this->loadView($dafult_template,$this->view);
		}
		else
		{
			$action_template = APP_PATH.'/View/'.strtolower($this->controller).'/'.strtolower($this->action).'.phtml';
			echo $this->loadView($action_template,$this->view);
		}
	}
	public function render_action($controller,$action,$params = array())
	{
		$config = MD_Registry::get('config');
		$class = APP_PATH.DS.'Controller'.DS.ucfirst($controller).'Controller.php';
			if(file_exists($class))
			{
				require_once ($class);
				
				$controller_name = ucfirst($controller).'Controller';
				$action_name = ucfirst($action).'Action';
				
				$external_controller = new $controller_name();
				$external_controller->params = $params;
				$external_controller->controller = ucfirst($controller);
				$external_controller->action = ucfirst($action);
				$external_controller->has_template = false;
				$external_controller->baseurl = 'http://'.$config->options->baseurl;
				$external_controller->_init_variables();
				$methods = get_class_methods($external_controller);
				if(in_array($action_name,$methods))
				{
					call_user_func_array(array($external_controller,$action_name), array());			
				}
				else
				{
					echo "include controller($action) not found!";
				}
			}
			else
			{
				echo "include controller($controller) not found!";
			}
			
	}
}