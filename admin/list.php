<?php

/**
* $Id$
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function editlist($showmenu = false, $listid = 0, $parentid =0)
{
	global $smartmaillight_list_handler;

	$listObj = $smartmaillight_list_handler->get($listid);

	if (!$listObj->isNew()){

		if ($showmenu) {
			smart_adminMenu(1, _AM_SMLIGHT_LISTS . " > " . _CO_SOBJECT_EDITING);
		}
		smart_collapsableBar('listedit', _AM_SMLIGHT_LIST_EDIT, _AM_SMLIGHT_LIST_EDIT_INFO);

		$sform = $listObj->getForm(_AM_SMLIGHT_LIST_EDIT, 'addlist');
		$sform->display();
		smart_close_collapsable('listedit');
	} else {
		if ($showmenu) {
			smart_adminMenu(1, _AM_SMLIGHT_LISTS . " > " . _CO_SOBJECT_CREATINGNEW);
		}

		smart_collapsableBar('listcreate', _AM_SMLIGHT_LIST_CREATE, _AM_SMLIGHT_LIST_CREATE_INFO);
		$sform = $listObj->getForm(_AM_SMLIGHT_LIST_CREATE, 'addlist');
		$sform->display();
		smart_close_collapsable('listcreate');
	}
}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

$smartmaillight_list_handler = xoops_getModuleHandler('list');
$smartmaillight_user_handler = xoops_getModuleHandler('user');
$smartmaillight_message_handler = xoops_getModuleHandler('message');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$listid = isset($_GET['listid']) ? intval($_GET['listid']) : 0 ;

switch ($op) {
	case "mod":
	case "changedField":

		smart_xoops_cp_header();

		editlist(true, $listid);
		break;


	case "addlist":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_list_handler);
		$controller->storeFromDefaultForm(_AM_SMLIGHT_LIST_CREATED, _AM_SMLIGHT_LIST_MODIFIED);

		break;

	case "del":
	    include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_list_handler);
		$controller->handleObjectDeletion();

		break;

	case "view" :
		$listObj = $smartmaillight_list_handler->get($listid);

		smart_xoops_cp_header();

		smart_adminMenu(1, _AM_SMLIGHT_LIST_VIEW . ' > ' . $listObj->getVar('title'));

		smart_collapsableBar('listview', $listObj->getVar('title') . $listObj->getEditItemLink(), _AM_SMLIGHT_LIST_VIEW_DSC);

		$listObj->displaySingleObject();

		echo "<br />";
		smart_close_collapsable('listview');
		echo "<br>";

		smart_collapsableBar('createdusers', _AM_SMLIGHT_USERS, _AM_SMLIGHT_LIST_USERS_DSC);

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('listid', $listid));
		$criteria->add(new Criteria('active', true));

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
		$objectTable = new SmartObjectTable($smartmaillight_user_handler, $criteria);
		$objectTable->addColumn(new SmartObjectColumn('uid', 'left', 200, 'getUserLink'));
		$objectTable->addColumn(new SmartObjectColumn('listid'));

		$objectTable->addIntroButton('adduser', 'user.php?op=mod&listid=' . $listid, _AM_SMLIGHT_USER_CREATE);

		//$objectTable->addQuickSearch(array('name'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createdusers');
		echo "<br>";

		smart_collapsableBar('createdmessages', _AM_SMLIGHT_MESSAGES, _AM_SMLIGHT_LIST_MESSAGES_DSC);

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('list.listid', $listid));

		$objectTable = new SmartObjectTable($smartmaillight_message_handler, $criteria);
		$objectTable->addColumn(new SmartObjectColumn('subject', 'left', false, 'getItemAdminView'));
		$objectTable->addColumn(new SmartObjectColumn('date', 'left', 150));

		$objectTable->addIntroButton('addmessage', 'message.php?op=mod&listid=' . $listid, _AM_SMLIGHT_MESSAGE_CREATE);

		$objectTable->addQuickSearch(array('subject', 'body'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createdmessages');
		echo "<br>";

		break;

	default:

		smart_xoops_cp_header();

		smart_adminMenu(1, _AM_SMLIGHT_LISTS);

		smart_collapsableBar('createdlists', _AM_SMLIGHT_LISTS, _AM_SMLIGHT_LISTS_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
		$objectTable = new SmartObjectTable($smartmaillight_list_handler);
		$objectTable->addColumn(new SmartObjectColumn('title', 'left', 150, 'getAdminViewItemLink'));
		$objectTable->addColumn(new SmartObjectColumn('description'));

		$objectTable->addIntroButton('addlist', 'list.php?op=mod', _AM_SMLIGHT_LIST_CREATE);

		$objectTable->addQuickSearch(array('title'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createdlists');
		echo "<br>";

		break;
}

smart_modFooter();
xoops_cp_footer();

?>