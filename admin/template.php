<?php

/**
* $Id$
* Module: SmartMailLight
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

function edittemplate($showmenu = false, $templateid = 0, $parentid =0)
{
	global $smartmaillight_template_handler, $submenus, $tabid;

	$templateObj = $smartmaillight_template_handler->get($templateid);

	if (!$templateObj->isNew()){

		if ($showmenu) {
			smart_adminMenu($tabid, _AM_SMLIGHT_TEMPLATES . " > " . _CO_SOBJECT_EDITING);
		}
		smart_collapsableBar('templateedit', _AM_SMLIGHT_TEMPLATE_EDIT, _AM_SMLIGHT_TEMPLATE_EDIT_INFO);

		$sform = $templateObj->getForm(_AM_SMLIGHT_TEMPLATE_EDIT, 'addtemplate');
		$sform->display();
		smart_close_collapsable('templateedit');
	} else {
		if ($showmenu) {
			smart_adminMenu($tabid, _AM_SMLIGHT_TEMPLATES . " > " . _CO_SOBJECT_CREATINGNEW);
		}

		smart_collapsableBar('templatecreate', _AM_SMLIGHT_TEMPLATE_CREATE, _AM_SMLIGHT_TEMPLATE_CREATE_INFO);
		$sform = $templateObj->getForm(_AM_SMLIGHT_TEMPLATE_CREATE, 'addtemplate');
		$sform->display();
		smart_close_collapsable('templatecreate');
	}
}

include_once("admin_header.php");
include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";

$smartmaillight_template_handler = xoops_getModuleHandler('template');

$tabid = $xoopsModuleConfig['enable_ecard'] ? 3 : 2;

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$templateid = isset($_GET['templateid']) ? intval($_GET['templateid']) : 0 ;

switch ($op) {
	case "mod":
	case "changedField":

		smart_xoops_cp_header();

		edittemplate(true, $templateid);
		break;


	case "addtemplate":
        include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_template_handler);
		$controller->storeFromDefaultForm(_AM_SMLIGHT_TEMPLATE_CREATED, _AM_SMLIGHT_TEMPLATE_MODIFIED);

		break;

	case "del":
	    include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartmaillight_template_handler);
		$controller->handleObjectDeletion();

		break;

	case "view" :
		$templateObj = $smartmaillight_template_handler->get($templateid);

		smart_xoops_cp_header();

		smart_adminMenu($tabid, _AM_SMLIGHT_TEMPLATE_VIEW . ' > ' . $templateObj->getVar('name'));

		smart_collapsableBar('templateview', $templateObj->getVar('name') . $templateObj->getEditItemLink(), _AM_SMLIGHT_TEMPLATE_VIEW_DSC);

		//$templateObj->displaySingleObject();
		$xoopsTpl = new XoopsTpl();
		$xoopsTpl->assign('smartmaillight_template', $templateObj->toArray());
		$xoopsTpl->display('db:smartmaillight_template_view.html');

		echo "<br />";
		smart_close_collapsable('templateview');
		echo "<br>";

		break;

	default:

		smart_xoops_cp_header();

		smart_adminMenu($tabid, _AM_SMLIGHT_TEMPLATES);

		smart_collapsableBar('createdtemplates', _AM_SMLIGHT_TEMPLATES, _AM_SMLIGHT_TEMPLATES_DSC);

		include_once SMARTOBJECT_ROOT_PATH."class/smartobjecttable.php";
		$objectTable = new SmartObjectTable($smartmaillight_template_handler);
		$objectTable->addColumn(new SmartObjectColumn('name', 'left', 150, 'getAdminViewItemLink'));
		$objectTable->addColumn(new SmartObjectColumn('description'));

		$objectTable->addIntroButton('addtemplate', 'template.php?op=mod', _AM_SMLIGHT_TEMPLATE_CREATE);

		//$objectTable->addQuickSearch(array('name'));

		$objectTable->render();

		echo "<br />";
		smart_close_collapsable('createdtemplates');
		echo "<br>";

		break;
}

smart_modFooter();
xoops_cp_footer();

?>