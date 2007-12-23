<?php

/**
* $Id$
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function edituser($showmenu = false, $userid = 0, $parentid =0)
{
	global $smartmaillight_user_handler;

	$userObj = $smartmaillight_user_handler->get($userid);

	$listid = isset($_GET['listid']) ? $_GET['listid'] : 0;

	if ($listid) {
		$userObj->setVar('listid', $listid);
	}

	if (!$userObj->isNew()){

		if ($showmenu) {
			smart_adminMenu(1, _AM_SMLIGHT_USERS . " > " . _CO_SOBJECT_EDITING);
		}
		smart_collapsableBar('useredit', _AM_SMLIGHT_USER_EDIT, _AM_SMLIGHT_USER_EDIT_INFO);

		$sform = $userObj->getForm(_AM_SMLIGHT_USER_EDIT, 'adduser');
		$sform->display();
		smart_close_collapsable('useredit');
	} else {
		if ($showmenu) {
			smart_adminMenu(1, _AM_SMLIGHT_USERS . " > " . _CO_SOBJECT_CREATINGNEW);
		}

		smart_collapsableBar('usercreate', _AM_SMLIGHT_USER_CREATE, _AM_SMLIGHT_USER_CREATE_INFO);
		$sform = $userObj->getForm(_AM_SMLIGHT_USER_CREATE, 'adduser');
		$sform->display();
		smart_close_collapsable('usercreate');
	}
}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

$smartmaillight_user_handler = xoops_getModuleHandler('user');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0 ;

switch ($op) {
	case "mod":
	case "changedField":

		smart_xoops_cp_header();

		edituser(true, $userid);
		break;


	case "adduser":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_user_handler);
		$controller->storeFromDefaultForm(_AM_SMLIGHT_USER_CREATED, _AM_SMLIGHT_USER_MODIFIED);

		break;

	case "del":
	    include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_user_handler);
		$controller->handleObjectDeletion();

		break;

	case "view" :
		$userObj = $smartmaillight_user_handler->get($userid);

		smart_xoops_cp_header();

		smart_adminMenu(1, _AM_SMLIGHT_USER_VIEW . ' > ' . $userObj->getVar('name'));

		smart_collapsableBar('userview', $userObj->getVar('name') . $userObj->getEditItemLink(), _AM_SMLIGHT_USER_VIEW_DSC);

		$userObj->displaySingleObject();

		echo "<br />";
		smart_close_collapsable('userview');
		echo "<br>";

		break;

	default:

		smart_xoops_cp_header();

		smart_adminMenu(1, _AM_SMLIGHT_USERS);

		smart_collapsableBar('createdusers', _AM_SMLIGHT_USERS, _AM_SMLIGHT_USERS_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
		$objectTable = new SmartObjectTable($smartmaillight_user_handler);
		$objectTable->addColumn(new SmartObjectColumn('uid', 'left', 200, 'getUserLink'));
		$objectTable->addColumn(new SmartObjectColumn('listid'));

		$objectTable->addIntroButton('adduser', 'user.php?op=mod', _AM_SMLIGHT_USER_CREATE);

		//$objectTable->addQuickSearch(array('name'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createdusers');
		echo "<br>";

		break;
}

smart_modFooter();
xoops_cp_footer();

?>