<?php
date_default_timezone_set('Asia/Tehran');
defined('DS') || define('DS',DIRECTORY_SEPARATOR);
defined('PS') || define('PS',PATH_SEPARATOR);
defined('LS') || define('LS','/');
defined('BASE_PATH')
|| define('BASE_PATH', realpath(dirname(__FILE__)));
defined('APP_PATH')|| define('APP_PATH', BASE_PATH.DS.'App');
set_include_path(implode(PATH_SEPARATOR, array(
realpath(BASE_PATH .DS. 'Library'),realpath(BASE_PATH .DS. 'App'),
get_include_path(),
)));

include('Library/MD/Loader/Autoloader.php');
$autoLoader = MD_Loader_Autoloader::getInstance();
$autoLoader->registerNamespace('MD_');
$resourceLoader = new MD_Loader_Autoloader_Resource(array(
	'basePath' => APP_PATH,
	'namespace' => '',
	'resourceTypes' => array(
		'model' => array(
			'path' => 'Model/',
			'namespace' => 'Model_'
		),
		'controller' => array(
			'path' => 'Controller/',
			'namespace' => 'Controller_'
		),
	),
));


require "Library/MD/FrontController.php";
$Front = new MD_FrontController(BASE_PATH.DS."App".DS."Config".DS."application.ini",BASE_PATH.DS."App".DS."Config".DS."routs.ini");
