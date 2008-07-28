<?php

/**
* $Id$
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function editrecipient($showmenu = false, $recipientid = 0, $parentid =0)
{
	global $smartmaillight_recipient_handler, $tabid;

	$recipientObj = $smartmaillight_recipient_handler->get($recipientid);

	if (!$recipientObj->isNew()){

		if ($showmenu) {
			smart_adminMenu($tabid, _AM_SMLIGHT_RECIPIENTS . " > " . _CO_SOBJECT_EDITING);
		}
		smart_collapsableBar('recipientedit', _AM_SMLIGHT_RECIPIENT_EDIT, _AM_SMLIGHT_RECIPIENT_EDIT_INFO);

		$sform = $recipientObj->getForm(_AM_SMLIGHT_RECIPIENT_EDIT, 'addrecipient');
		$sform->display();
		smart_close_collapsable('recipientedit');
	} else {
		if ($showmenu) {
			smart_adminMenu($tabid, _AM_SMLIGHT_RECIPIENTS . " > " . _CO_SOBJECT_CREATINGNEW);
		}

		smart_collapsableBar('recipientcreate', _AM_SMLIGHT_RECIPIENT_CREATE, _AM_SMLIGHT_RECIPIENT_CREATE_INFO);
		$sform = $recipientObj->getForm(_AM_SMLIGHT_RECIPIENT_CREATE, 'addrecipient');
		$sform->display();
		smart_close_collapsable('recipientcreate');
	}
}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

$smartmaillight_recipient_handler = xoops_getModuleHandler('recipient');

$tabid = $xoopsModuleConfig['enable_ecard'] ? 4 : 3;

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$recipientid = isset($_GET['recipientid']) ? intval($_GET['recipientid']) : 0 ;

switch ($op) {
	case "mod":
	case "changedField":

		smart_xoops_cp_header();

		editrecipient(true, $recipientid);
		break;


	case "addrecipient":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_recipient_handler);
		$controller->storeFromDefaultForm(_AM_SMLIGHT_RECIPIENT_CREATED, _AM_SMLIGHT_RECIPIENT_MODIFIED);

		break;

	case "del":
	    include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_recipient_handler);
		$controller->handleObjectDeletion();

		break;

	case "view" :
		$recipientObj = $smartmaillight_recipient_handler->get($recipientid);

		smart_xoops_cp_header();

		smart_adminMenu($tabid, _AM_SMLIGHT_RECIPIENT_VIEW . ' > ' . $recipientObj->getVar('name'));

		smart_collapsableBar('recipientview', $recipientObj->getVar('name') . $recipientObj->getEditItemLink(), _AM_SMLIGHT_RECIPIENT_VIEW_DSC);

		$recipientObj->displaySingleObject();

		echo "<br />";
		smart_close_collapsable('recipientview');
		echo "<br>";

		break;

	default:

		smart_xoops_cp_header();

		smart_adminMenu($tabid, _AM_SMLIGHT_RECIPIENTS);

		smart_collapsableBar('createdrecipients', _AM_SMLIGHT_RECIPIENTS, _AM_SMLIGHT_RECIPIENTS_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

		if ($xoopsModuleConfig['enable_ecard']) {
			$criteria = null;
		} else {
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('ecardid', 0));
		}

		$objectTable = new SmartObjectTable($smartmaillight_recipient_handler, $criteria);
		$objectTable->addColumn(new SmartObjectColumn('recipientid', 'left', 50));
		$objectTable->addColumn(new SmartObjectColumn('listid', 'left'));
		$objectTable->addColumn(new SmartObjectColumn('userid', 'left', 200, 'getItemAdminView'));
		if ($xoopsModuleConfig['enable_ecard']) {
			$objectTable->addColumn(new SmartObjectColumn('ecardid', 'left', 200));
			$objectTable->addColumn(new SmartObjectColumn('email_address', 'left', 200));
		}
		$objectTable->addColumn(new SmartObjectColumn('status', 'center', 100));

		$objectTable->addIntroButton('addrecipient', 'recipient.php?op=mod', _AM_SMLIGHT_RECIPIENT_CREATE);

		//$objectTable->addQuickSearch(array('name'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createdrecipients');
		echo "<br>";

		break;
}

smart_modFooter();
xoops_cp_footer();

?>