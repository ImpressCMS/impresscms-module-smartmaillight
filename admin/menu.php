<?php
/**
* $Id$
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

$i = -1;

$i++;
$adminmenu[$i]['title'] = _MI_SMLIGHT_MESSAGES;
$adminmenu[$i]['link'] = "admin/message.php";
global $xoopsModuleConfig;

$i++;
$adminmenu[$i]['title'] = _MI_SMLIGHT_LISTS;
$adminmenu[$i]['link'] = "admin/list.php";

if ($xoopsModuleConfig['enable_ecard']) {
	$i++;
	$adminmenu[$i]['title'] = _MI_SMLIGHT_ECARDS;
	$adminmenu[$i]['link'] = "admin/ecard.php";
}

$i++;
$adminmenu[$i]['title'] = _MI_SMLIGHT_TEMPLATES;
$adminmenu[$i]['link'] = "admin/template.php";

$i++;
$adminmenu[$i]['title'] = _MI_SMLIGHT_RECIPIENTS;
$adminmenu[$i]['link'] = "admin/recipient.php";

if (isset($xoopsModule)) {

	$i = -1;

	$i++;
	$headermenu[$i]['title'] = _PREFERENCES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');

	$i++;
	$headermenu[$i]['title'] = _CO_SOBJECT_GOTOMODULE;
	$headermenu[$i]['link'] = SMARTMAILLIGHT_URL;

	$i++;
	$headermenu[$i]['title'] = _MI_SMLIGHT_SENDNOW;
	$headermenu[$i]['link'] = SMARTMAILLIGHT_ADMIN_URL . 'sendnow.php';

	$i++;
	$headermenu[$i]['title'] = _CO_SOBJECT_UPDATE_MODULE;
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');

	$i++;
	$headermenu[$i]['title'] = _AM_SOBJECT_ABOUT;
	$headermenu[$i]['link'] = SMARTMAILLIGHT_URL . "admin/about.php";
}
?>
