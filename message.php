<?php
include_once('header.php');

$xoopsOption['template_main'] = 'smartmaillight_message.html';
include_once(XOOPS_ROOT_PATH . "/header.php");

$smartmaillight_message_handler = xoops_getModuleHandler('message');
$smartmaillight_list_handler = xoops_getModuleHandler('list');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$smartmaillight_message_handler = xoops_getModuleHandler('message');
$messageid = isset($_GET['messageid']) ? intval($_GET['messageid']) : 0 ;

if ($messageid) {
	$op = 'view';
}

switch ($op) {
	case "view" :
		$messageObj = $smartmaillight_message_handler->get($messageid);

		$xoopsTpl->assign('smartmaillight_message', $messageObj->toArray());
		$xoopsTpl->assign('categoryPath', '<a href="message.php">' . _MD_SMLIGHT_MESSAGES_HISTORY . '</a> > ' . $messageObj->getVar('subject'));

		break;

	default:

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('archived', 1));
		$criteria->add(new Criteria('status', SMARTMAILLIGHT_MESSAGE_STATUS_SENT));

		$objectTable = new SmartObjectTable($smartmaillight_message_handler, $criteria, array());
		$objectTable->isForUserSide();

		$objectTable->addColumn(new SmartObjectColumn('date', 'center', 200));
		$objectTable->addColumn(new SmartObjectColumn('subject'));

		$objectTable->addQuickSearch(array('subject', 'body'));

		$xoopsTpl->assign('smartmaillight_messages', $objectTable->fetch());
		$xoopsTpl->assign('categoryPath', _MD_SMLIGHT_MESSAGES_HISTORY);

		break;
}
$xoopsTpl->assign('module_home', smart_getModuleName(false, true));
include_once("footer.php");
?>