<?php

/**
* $Id$
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/
if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

if( !defined("SMARTMAILLIGHT_DIRNAME") ){
	define("SMARTMAILLIGHT_DIRNAME", 'smartmaillight');
}

if( !defined("SMARTMAILLIGHT_URL") ){
	define("SMARTMAILLIGHT_URL", XOOPS_URL.'/modules/'.SMARTMAILLIGHT_DIRNAME.'/');
}
if( !defined("SMARTMAILLIGHT_ROOT_PATH") ){
	define("SMARTMAILLIGHT_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.SMARTMAILLIGHT_DIRNAME.'/');
}

if( !defined("SMARTMAILLIGHT_IMAGES_URL") ){
	define("SMARTMAILLIGHT_IMAGES_URL", SMARTMAILLIGHT_URL.'images/');
}

if( !defined("SMARTMAILLIGHT_ADMIN_URL") ){
	define("SMARTMAILLIGHT_ADMIN_URL", SMARTMAILLIGHT_URL.'admin/');
}

/** Include SmartObject framework **/
include_once XOOPS_ROOT_PATH.'/modules/smartobject/class/smartloader.php';

/*
 * Including the common language file of the module
 */
$fileName = SMARTMAILLIGHT_ROOT_PATH . 'language/' . $GLOBALS['xoopsConfig']['language'] . '/common.php';
if (!file_exists($fileName)) {
	$fileName = SMARTMAILLIGHT_ROOT_PATH . 'language/english/common.php';
}

include_once($fileName);

include_once(SMARTMAILLIGHT_ROOT_PATH . "include/functions.php");

// Creating the SmartModule object
$smartmaillightModule =& smart_getModuleInfo(SMARTMAILLIGHT_DIRNAME);

// Find if the user is admin of the module
$smartmaillight_isAdmin = smart_userIsAdmin(SMARTMAILLIGHT_DIRNAME);

$myts = MyTextSanitizer::getInstance();
if(is_object($smartmaillightModule)){
	$smartmaillight_moduleName = $smartmaillightModule->getVar('name');
}

// Creating the SmartModule config Object
$smartmaillightConfig =& smart_getModuleConfig(SMARTMAILLIGHT_DIRNAME);

include_once(SMARTMAILLIGHT_ROOT_PATH . "class/list.php");
include_once(SMARTMAILLIGHT_ROOT_PATH . "class/user.php");
include_once(SMARTMAILLIGHT_ROOT_PATH . "class/recipient.php");
include_once(SMARTMAILLIGHT_ROOT_PATH . "class/message.php");

?>