<?php
class MD_Controller_Exception extends MD_Exception
{
	function __construct($m)
	{
		$message = "<hr/>";
		$message .= "MD Controller Exception <pre> {$m} </pre> "; 
		$message .= "<hr/>";
		parent::__construct($message);
	}
}