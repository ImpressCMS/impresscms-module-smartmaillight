<?php
include_once('header.php');

if (!is_object($xoopsUser) && !$xoopsModuleConfig['anon_subscript']) {
	redirect_header(XOOPS_URL . '/user.php', 3, _MD_SMLIGHT_NEEDTOBECONNECTED);
	exit;
}

$xoopsOption['template_main'] = 'smartmaillight_index.html';
include_once(XOOPS_ROOT_PATH . "/header.php");
session_start();
$op = isset($_POST['op']) ? $_POST['op'] : 'default';
$uid = is_object($xoopsUser) ? $xoopsUser->uid(): 0;
//
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
if(isset($email) && $email != ''){
	setcookie("smart_email", $email);
}else{
	$email =  $_COOKIE["smart_email"] ;
}

$smartmaillight_user_handler = xoops_getModuleHandler('user');
$smartmaillight_list_handler = xoops_getModuleHandler('list');

switch ($op) {
	case 'smartmaillight_list_submit':
		if (isset($_POST['all_lists'])) {
			foreach($_POST['all_lists'] as $listid) {
				$is_subscribed = in_array($listid, $_POST['smartmaillight_selected_lists']);
				$smartmaillight_user_handler->addUserToList($uid, $listid, $is_subscribed, $email);
			}
		}
		redirect_header( SMARTMAILLIGHT_URL , 2, _MD_SMLIGHT_LISTS_UPDATED);
		exit;

	break;



	default:
		$aLists = $smartmaillight_list_handler->getObjects(null, true, false);
		if($email != ''){
			$aSubscribedList = $smartmaillight_user_handler->getListidsForEmail($email);
		}elseif($uid){
			$aSubscribedList = $smartmaillight_user_handler->getListidsForUid($uid);
		}
		$xoopsTpl->assign('smartmaillight_subscribedlists', $aSubscribedList);
		$xoopsTpl->assign('is_user', is_object($xoopsUser));
		$xoopsTpl->assign('email', $email);
		$xoopsTpl->assign('none_sel', empty($aSubscribedList));
		$xoopsTpl->assign('smartmaillight_lists', $aLists);
		$xoopsTpl->assign('module_home', smart_getModuleName(false, true));
	break;
}

include_once("footer.php");
?>