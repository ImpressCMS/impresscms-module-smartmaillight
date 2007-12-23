<?php

/**
* $Id$
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once "../../mainfile.php";

if( !defined("SMARTMAILLIGHT_DIRNAME") ){
	define("SMARTMAILLIGHT_DIRNAME", 'smartmaillight');
}

include_once XOOPS_ROOT_PATH.'/modules/' . SMARTMAILLIGHT_DIRNAME . '/include/common.php';
smart_loadCommonLanguageFile();

include_once SMARTMAILLIGHT_ROOT_PATH . "include/functions.php";
?>