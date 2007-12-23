<?php
include_once('header.php');
//include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobjectcontroller.php";

if (!$xoopsModuleConfig['enable_ecard']) {
	redirect_header('index.php', 3, _NOPERM);
	exit;
}

if (!is_object($xoopsUser) || !$xoopsUser->isAdmin()) {
	redirect_header('index.php', 3, _NOPERM);
	exit;
}

/**
 * @todo make some gourp permission check
 */
$step = isset($_POST['step']) ? $_POST['step'] : 1;
$xoopsOption['template_main'] = 'smartmaillight_sendecard_step' . $step . '.html';
include_once(XOOPS_ROOT_PATH . "/header.php");

$smartmaillight_template_handler = xoops_getModuleHandler('template');
$smartmaillight_ecard_handler = xoops_getModuleHandler('ecard');

$templateid = isset($_POST['selected_templateid']) ? intval($_POST['selected_templateid']) : 0 ;
switch ($step) {

	case 2:
		$ecardObj = $smartmaillight_ecard_handler->create();
		if (isset($_POST['ecardid'])) {
			$controller = new SmartObjectController($smartmaillight_ecard_handler);
			$controller->postDataToObject($ecardObj);
		} else {
			$ecardObj->setVar('templateid', $templateid);
			$ecardObj->setVar('date', time());
		}
		$ecardObj->hideFieldFromForm(array('date', 'templateid', 'status', 'pickupid'));
		$sform = $ecardObj->getForm('', 'smartmaillight_ecard_form');
		$sform->addElement(new XoopsFormHidden('step', 3));
		$sform->assign($xoopsTpl);

		$xoopsTpl->assign('categoryPath', '<a href="sendecard.php">' . _MD_SMLIGHT_SEND_ECARD . '</a>' . ' > ' . _MD_SMLIGHT_ECARD_SECOND);
	break;

	case 3:
		$ecardObj = $smartmaillight_ecard_handler->create();
		$controller = new SmartObjectController($smartmaillight_ecard_handler);
		$controller->postDataToObject($ecardObj);
		$xoopsTpl->assign('smartmaillight_ecard_vars', $ecardObj->getVarsToPassAsHidden());
		$xoopsTpl->assign('smartmaillight_ecard_content', $ecardObj->getEcardContent());
		$xoopsTpl->assign('smartmaillight_ecard_message', $ecardObj->getEcardMessage());
		$xoopsTpl->assign('smartmaillight_ecard_recipients', $ecardObj->getRecipients());

		$xoopsTpl->assign('categoryPath', '<a href="sendecard.php">' . _MD_SMLIGHT_SEND_ECARD . '</a>' . ' > ' . _MD_SMLIGHT_ECARD_THIRD);
	break;

	case 4:
		$controller = new SmartObjectController($smartmaillight_ecard_handler);
		$controller->storeFromDefaultForm(_MD_SMLIGHT_ECARD_SENT);

	break;

	default:

		$xoopsTpl->assign('smartmaillight_templates', $smartmaillight_template_handler->getTemplatesForEcard());

		$xoopsTpl->assign('categoryPath', _MD_SMLIGHT_SEND_ECARD . ' > ' . _MD_SMLIGHT_ECARD_FIRST);

		break;
}
$xoopsTpl->assign('module_home', smart_getModuleName(false, true));
include_once("footer.php");
?>