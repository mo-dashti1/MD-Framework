<?php
require_once "Paging/Paginated.php";
require_once "Paging/DoubleBarLayout.php";
/*
This class parses configuration file to set main constant of program and route file to determine specific parameters in URL 
*/
class MD_Plugin_Paging extends Paginated{
	
	public function __construct($names, $limit, $page)
	{
		parent::__construct($names, $limit, $page) ;
	}

	public function layout(){

		return new DoubleBarLayout();
	}
	
	
}
