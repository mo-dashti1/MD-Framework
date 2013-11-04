<?php
class TestController extends MD_Controller
{
	public function IndexAction(){
		// redirect to specific location
		//$this->_redirect("http://google.com");
		//$this->view['test'] = 'hi';
		
		
		
		// access to global registry
		//var_dump(MD_Registry::get('config'));
		//MD_Registry::set('name',"me");
		
		//set, get and unset session values
		//$this->set_session(array("new_key"=>"test_value"));
		//$this->view['name'] = $this->get_session("new_key");
		//$this->unset_session("new_key");
		//var_dump($_SESSION);
		
		//get rout parameters : controller, action,url parameteres
		//var_dump($this->params);
		
		//access get and post values
		//var_dump($this->get);
		//var_dump($this->post);
		
		// call extra plugin from MD library
		//$test = new MD_Plugin_Test();
		
		//access model classes to connect to database
		//$test2 = new Model_Test();
		//$users = $tes2->updateusers();
		//$this->render();
		
		
		//echo "test controller/ index action";
		$this->view['test'] = ' 2hiiii2';
		$this->render();
	}
	
	public function AddAction(){
		//echo "test controller/ add action";
		$this->view['params'] = $this->params;
		$this->render();
		//var_dump($this->params);
		
	}
	
	public function AjaxAction(){
		$this->has_template = false ;//disable template	
		//$this->render();
	}
}