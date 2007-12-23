<?php
include_once('header.php');

$xoopsOption['template_main'] = 'smartmaillight_ecard.html';
include_once(XOOPS_ROOT_PATH . "/header.php");

$smartmaillight_ecard_handler = xoops_getModuleHandler('ecard');

$pickupid = isset($_GET['id']) ? $_GET['id'] : false ;

$ecardObj = $smartmaillight_ecard_handler->geteCardByPickupid($pickupid);

if (!$ecardObj) {
	redirect_header(XOOPS_URL, 3, _NOPERM);
}

$xoopsTpl->assign('smartmaillight_ecard', $ecardObj->getEcardContent());
$xoopsTpl->assign('categoryPath', '<a href="message.php">' . _MD_SMLIGHT_ECARD . '</a> > ' . $ecardObj->getVar('subject'));

$xoopsTpl->assign('module_home', smart_getModuleName(false, true));
include_once("footer.php");
?>