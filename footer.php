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

$xoopsTpl->assign("smartmaillight_adminpage", smart_getModuleAdminLink());
$xoopsTpl->assign("isAdmin", $smartmaillight_isAdmin);
$xoopsTpl->assign('smartmaillight_url', SMARTMAILLIGHT_URL);
$xoopsTpl->assign('smartmaillight_images_url', SMARTMAILLIGHT_IMAGES_URL);

$xoTheme->addStylesheet(SMARTMAILLIGHT_URL . 'module.css');

$xoopsTpl->assign("ref_smartfactory", "SmartMailLight is developed by The SmartFactory (http://smartfactory.ca), a division of INBOX International (http://inboxinternational.com)");

include_once(XOOPS_ROOT_PATH . '/footer.php');

?>