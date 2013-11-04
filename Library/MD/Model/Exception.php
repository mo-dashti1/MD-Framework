<?php
class MD_Model_Exception extends MD_Exception
{
	function __construct($m)
	{
		parent::__construct("MD Model Exception <pre>  $m </pre> ");
	}
}