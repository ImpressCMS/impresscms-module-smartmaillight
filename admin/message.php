<?php

/**
* $Id$
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function editmessage($showmenu = false, $messageid = 0, $parentid =0)
{
	global $smartmaillight_message_handler;

	$messageObj = $smartmaillight_message_handler->get($messageid);

	$listid = isset($_GET['listid']) ? $_GET['listid'] : 0;

	if ($listid) {
		$messageObj->setVar('listid', $listid);
	}


	if (!$messageObj->isNew()){

		if ($showmenu) {
			smart_adminMenu(0, _AM_SMLIGHT_MESSAGES . " > " . _CO_SOBJECT_EDITING);
		}
		smart_collapsableBar('messageedit', _AM_SMLIGHT_MESSAGE_EDIT, _AM_SMLIGHT_MESSAGE_EDIT_INFO);

		$sform = $messageObj->getForm(_AM_SMLIGHT_MESSAGE_EDIT, 'addmessage');
		$sform->display();
		smart_close_collapsable('messageedit');
	} else {
		if ($showmenu) {
			smart_adminMenu(0, _AM_SMLIGHT_MESSAGES . " > " . _CO_SOBJECT_CREATINGNEW);
		}

		smart_collapsableBar('messagecreate', _AM_SMLIGHT_MESSAGE_CREATE, _AM_SMLIGHT_MESSAGE_CREATE_INFO);
		$messageObj->hideFieldFromForm('status');
		$sform = $messageObj->getForm(_AM_SMLIGHT_MESSAGE_CREATE, 'addmessage');
		$sform->display();
		smart_close_collapsable('messagecreate');
	}
}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

$smartmaillight_message_handler = xoops_getModuleHandler('message');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$messageid = isset($_GET['messageid']) ? intval($_GET['messageid']) : 0 ;

switch ($op) {
	case "mod":
	case "changedField":

		smart_xoops_cp_header();

		editmessage(true, $messageid);
		break;


	case "addmessage":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_message_handler);
		$controller->storeFromDefaultForm(_AM_SMLIGHT_MESSAGE_CREATED, _AM_SMLIGHT_MESSAGE_MODIFIED);

		break;

	case "del":
	    include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_message_handler);
		$controller->handleObjectDeletion();

		break;

	case "view" :
		$messageObj = $smartmaillight_message_handler->get($messageid);

		smart_xoops_cp_header();

		smart_adminMenu(0, _AM_SMLIGHT_MESSAGE_VIEW . ' > ' . $messageObj->getVar('name'));

		smart_collapsableBar('messageview', $messageObj->getVar('name') . $messageObj->getEditItemLink(), _AM_SMLIGHT_MESSAGE_VIEW_DSC);

		$messageObj->displaySingleObject();

		echo "<br />";
		smart_close_collapsable('messageview');
		echo "<br>";

		break;

	default:

		smart_xoops_cp_header();

		smart_adminMenu(0, _AM_SMLIGHT_MESSAGES);

		smart_collapsableBar('createdmessages', _AM_SMLIGHT_MESSAGES, _AM_SMLIGHT_MESSAGES_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
		$objectTable = new SmartObjectTable($smartmaillight_message_handler);
		$objectTable->addColumn(new SmartObjectColumn('date', 'left', 150, 'getItemAdminView'));
		$objectTable->addColumn(new SmartObjectColumn('subject', 'left', false));
		$objectTable->addColumn(new SmartObjectColumn('listid', 'left', 200));
		$objectTable->addColumn(new SmartObjectColumn('status', 'center', 200));

		$objectTable->addIntroButton('addmessage', 'message.php?op=mod', _AM_SMLIGHT_MESSAGE_CREATE);

		$objectTable->addQuickSearch(array('subject', 'body'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createdmessages');
		echo "<br>";

		break;
}

smart_modFooter();
xoops_cp_footer();

?>