<?php
require_once 'config.php';
require_once LIB.'functions.php';
import('db');
import('lang');
import('users');
import('roles');

db_connect();

//Check for change in the currency
if( isset($_GET['CURRENCY_UOM_ID']) ){
	//Check if currency exists
	$_SESSION['CURRENCY_UOM_ID'] = $_GET['CURRENCY_UOM_ID'];
}

//If there is no set currency put the default one
if( !isset($_SESSION['CURRENCY_UOM_ID']) ){
	$_SESSION['CURRENCY_UOM_ID'] = DEFAULT_CURRENCY;
}

$_SESSION['lang'] = DEFAULT_LANG;
$module     = 'index';
$controller = 'index';
$href_lang  = '';

// Make available passing erros and success
// messages between pages
$errors = isset($_GET['errors']) ? array($_GET['errors']) : array();
$success = isset($_GET['success']) ? array($_GET['success']) : array();

$is_logged = user_is_logged();
$system_settings =[]; //get_settings();

if( isset($_GET['url']) ){
	$url = explode('/', $_GET['url']);

	if(!empty($url)){

		if( $url[0] == ADMIN_PATH ){
			$module = isset($url[1]) && !empty($url[1])?$url[1]:'index';
			$controller = 'admin/';
			$controller .= isset($url[2]) && !empty($url[2])?$url[2]:'index';

			if( !isset($_SESSION['admin']) && isset($_COOKIE['RememberUser']) && $_COOKIE['RememberUser'] == 1 ){

			}
		}
		else{
			$module_index = 0;
			$controller_index = 1;

			$module = isset($url[$module_index]) && !empty($url[$module_index])?$url[$module_index]:'index';
			$controller = isset($url[$controller_index]) && !empty($url[$controller_index])?$url[$controller_index]:'index';
		}
	}
}

require_once 'client.php';

$include = MODULES.$module.'/'.$controller.'.php';

if(file_exists($include)){
	//require_once MODULES."/test/test.php";
	require_once $include;
}

else{
	if (is_admin()) {
		header('location: /users/index');
	} else {
		header('location: /dashboard/dashboard');
	}
}
?>
