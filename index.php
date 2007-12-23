<?php
include_once('header.php');

if (!is_object($xoopsUser)) {
	redirect_header(XOOPS_URL . '/user.php', 3, _MD_SMLIGHT_NEEDTOBECONNECTED);
	exit;
}

$xoopsOption['template_main'] = 'smartmaillight_index.html';
include_once(XOOPS_ROOT_PATH . "/header.php");

$op = isset($_POST['op']) ? $_POST['op'] : 'default';
$uid = $xoopsUser->uid();

$smartmaillight_user_handler = xoops_getModuleHandler('user');
$smartmaillight_list_handler = xoops_getModuleHandler('list');

switch ($op) {
	case 'smartmaillight_list_submit':
		if (isset($_POST['all_lists'])) {
			foreach($_POST['all_lists'] as $listid) {
				$is_subscribed = in_array($listid, $_POST['smartmaillight_selected_lists']);
				$smartmaillight_user_handler->addUserToList($uid, $listid, $is_subscribed);
			}
		}
		redirect_header(SMARTMAILLIGHT_URL, 2, _MD_SMLIGHT_LISTS_UPDATED);
		exit;

	break;

	default:
		$aLists = $smartmaillight_list_handler->getObjects(null, true, false);

		$aSubscribedList = $smartmaillight_user_handler->getListidsForUid($uid);
		$xoopsTpl->assign('smartmaillight_subscribedlists', $aSubscribedList);

		$xoopsTpl->assign('smartmaillight_lists', $aLists);
		$xoopsTpl->assign('module_home', smart_getModuleName(false, true));
	break;
}

include_once("footer.php");
?>