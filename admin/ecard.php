<?php

/**
* $Id$
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function editecard($showmenu = false, $ecardid = 0, $parentid =0)
{
	global $smartmaillight_ecard_handler;

	$ecardObj = $smartmaillight_ecard_handler->get($ecardid);

	$listid = isset($_GET['listid']) ? $_GET['listid'] : 0;

	if ($listid) {
		$ecardObj->setVar('listid', $listid);
	}


	if (!$ecardObj->isNew()){

		if ($showmenu) {
			smart_adminMenu(2, _AM_SMLIGHT_ECARDS . " > " . _CO_SOBJECT_EDITING);
		}
		smart_collapsableBar('ecardedit', _AM_SMLIGHT_ECARD_EDIT, _AM_SMLIGHT_ECARD_EDIT_INFO);

		$sform = $ecardObj->getForm(_AM_SMLIGHT_ECARD_EDIT, 'addecard');
		$sform->display();
		smart_close_collapsable('ecardedit');
	} else {
		if ($showmenu) {
			smart_adminMenu(2, _AM_SMLIGHT_ECARDS . " > " . _CO_SOBJECT_CREATINGNEW);
		}

		smart_collapsableBar('ecardcreate', _AM_SMLIGHT_ECARD_CREATE, _AM_SMLIGHT_ECARD_CREATE_INFO);
		$ecardObj->hideFieldFromForm('status');
		$sform = $ecardObj->getForm(_AM_SMLIGHT_ECARD_CREATE, 'addecard');
		$sform->display();
		smart_close_collapsable('ecardcreate');
	}
}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

$smartmaillight_ecard_handler = xoops_getModuleHandler('ecard');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$ecardid = isset($_GET['ecardid']) ? intval($_GET['ecardid']) : 0 ;

switch ($op) {
	case "mod":
	case "changedField":

		smart_xoops_cp_header();

		editecard(true, $ecardid);
		break;


	case "addecard":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_ecard_handler);
		$controller->storeFromDefaultForm(_AM_SMLIGHT_ECARD_CREATED, _AM_SMLIGHT_ECARD_MODIFIED);

		break;

	case "del":
	    include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_ecard_handler);
		$controller->handleObjectDeletion();

		break;

	case "view" :
		$ecardObj = $smartmaillight_ecard_handler->get($ecardid);

		smart_xoops_cp_header();

		smart_adminMenu(2, _AM_SMLIGHT_ECARD_VIEW . ' > ' . $ecardObj->getVar('name'));

		smart_collapsableBar('ecardview', $ecardObj->getVar('name') . $ecardObj->getEditItemLink(), _AM_SMLIGHT_ECARD_VIEW_DSC);

		$ecardObj->displaySingleObject();

		echo "<br />";
		smart_close_collapsable('ecardview');
		echo "<br>";

		break;

	default:

		smart_xoops_cp_header();

		smart_adminMenu(2, _AM_SMLIGHT_ECARDS);

		smart_collapsableBar('createdecards', _AM_SMLIGHT_ECARDS, _AM_SMLIGHT_ECARDS_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
		$objectTable = new SmartObjectTable($smartmaillight_ecard_handler);
		$objectTable->addColumn(new SmartObjectColumn('date', 'left', 150, 'getItemAdminView'));
		$objectTable->addColumn(new SmartObjectColumn('subject', 'left', false));
		$objectTable->addColumn(new SmartObjectColumn('from_name', 'left', 200));
		$objectTable->addColumn(new SmartObjectColumn('from_email', 'left', 200));
		$objectTable->addColumn(new SmartObjectColumn('templateid', 'left', 200));
		$objectTable->addColumn(new SmartObjectColumn('status', 'center', 200));

		$objectTable->addIntroButton('addecard', 'ecard.php?op=mod', _AM_SMLIGHT_ECARD_CREATE);

		$objectTable->addQuickSearch(array('subject', 'body'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createdecards');
		echo "<br>";

		break;
}

smart_modFooter();
xoops_cp_footer();

?>