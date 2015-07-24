<?php
date_default_timezone_set('Asia/Jakarta');
@session_start();
/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
	define('ENVIRONMENT', 'development');
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */


if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL);
		break;
	
		case 'testing':
		case 'production':
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}
$system_path = 'core';
$application_folder = 'apps';

if (defined('STDIN')){
	chdir(dirname(__FILE__));
}

if (realpath($system_path) !== FALSE){
	$system_path = realpath($system_path).'/';
}


$system_path = rtrim($system_path, '/').'/';

if ( ! is_dir($system_path)){
	exit("Folder path is not found correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('EXT', '.php');
define('BASEPATH', str_replace("\\", "/", $system_path));
define('FCPATH', str_replace(SELF, '', __FILE__));
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));
define('COPYRIGHT_OWNER',$_SERVER['HTTP_HOST']);

if (is_dir($application_folder)){
	define('APPPATH', $application_folder.'/');
}else{
	if ( ! is_dir(BASEPATH.$application_folder.'/')){
		exit("Application folder is not found correct this: ".SELF);
	}
	
	define('APPPATH', BASEPATH.$application_folder.'/');
}


if( !defined('_ROOT') ){
	define('_ROOT',	dirname(__FILE__)."/");
}

$url = "http://".$_SERVER['SERVER_NAME'].str_replace("index.php","",$_SERVER['SCRIPT_NAME']);
if( !defined('_URL') ){
	define('_URL',$url);
}

if(!defined('_ASSET_URL')){
	define('_ASSET_URL',_URL."assets/");
}

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	$is_ajax = 1;
} else {
	$is_ajax = 0;
}

define('IS_AJAX', $is_ajax);
require_once BASEPATH.'core/CodeIgniter.php';