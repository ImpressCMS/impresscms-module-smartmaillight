<?php
// $Id$
// ------------------------------------------------------------------------ //
// 				 XOOPS - PHP Content Management System                      //
//					 Copyright (c) 2000 XOOPS.org                           //
// 						<http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //

// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //

// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// URL: http://www.xoops.org/												//
// Ecard: The XOOPS Ecard                                               //
// -------------------------------------------------------------------------//

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";

define('SMARTMAILLIGHT_ECARD_STATUS_NEW', 0);
define('SMARTMAILLIGHT_ECARD_STATUS_READY', 2);
define('SMARTMAILLIGHT_ECARD_STATUS_SENT', 1);

class SmartmaillightEcard extends SmartObject {

    function SmartmaillightEcard() {
        $this->quickInitVar('ecardid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('templateid', XOBJ_DTYPE_INT, true, _CO_SMLIGHT_ECARD_TEMPLATEID, _CO_SMLIGHT_ECARD_TEMPLATEID_DSC);
        $this->quickInitVar('subject', XOBJ_DTYPE_TXTBOX, true, _CO_SMLIGHT_ECARD_SUBJECT, _CO_SMLIGHT_ECARD_SUBJECT_DSC);
		$this->quickInitVar('message', XOBJ_DTYPE_TXTAREA, true, _CO_SMLIGHT_ECARD_MESSAGE, _CO_SMLIGHT_ECARD_MESSAGE_DSC);
		$this->quickInitVar('from_name', XOBJ_DTYPE_TXTBOX, true, _CO_SMLIGHT_ECARD_FROM_NAME, _CO_SMLIGHT_ECARD_FROM_NAME_DSC);
		$this->quickInitVar('from_email', XOBJ_DTYPE_TXTBOX, true, _CO_SMLIGHT_ECARD_FROM_EMAIL, _CO_SMLIGHT_ECARD_FROM_EMAIL_DSC);
		$this->quickInitVar('date', XOBJ_DTYPE_LTIME, true, _CO_SMLIGHT_ECARD_DATE, _CO_SMLIGHT_ECARD_DATE_DSC);
		$this->quickInitVar('emails', XOBJ_DTYPE_TXTAREA, true, _CO_SMLIGHT_ECARD_EMAILS, _CO_SMLIGHT_ECARD_EMAILS_DSC);
		$this->quickInitVar('status', XOBJ_DTYPE_INT, false, _CO_SMLIGHT_ECARD_STATUS, _CO_SMLIGHT_ECARD_STATUS_DSC);
		$this->quickInitVar('pickupid', XOBJ_DTYPE_TXTBOX, false, _CO_SMLIGHT_ECARD_PICKUPID, _CO_SMLIGHT_ECARD_PICKUPID_DSC);

		$this->setControl('templateid', array('itemHandler' => 'template',
                                  'method' => 'getList',
                                  'module' => 'smartmaillight'));

		$this->setControl('status', array('itemHandler' => 'ecard',
                                  'method' => 'getStatus',
                                  'module' => 'smartmaillight'));

		$this->setControl('message', array(
										'name' => 'textarea',
                                  		'form_editor'=>'textarea',
                                  		'rows'=>15,
                                  		'cols'=>60
                                  		));

		$this->setControl('emails', array(
										'name' => 'textarea',
                                  		'form_editor'=>'textarea',
                                  		'rows'=>15,
                                  		'cols'=>60
                                  		));
    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('templateid', 'status'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

    function getVarsToPassAsHidden() {
    	$ret = array();
    	foreach($this->vars as $key=>$var) {
    		$ret[$key] = $this->getVar($key, 'n');
    	}
    	return $ret;
    }

    function getEcardContent() {
    	$ret = 'content';

		$smartmaillight_template_handler = xoops_getModuleHandler('template', 'smartmaillight');
		$templateObj = $smartmaillight_template_handler->get( $this->getVar('templateid', 'e'));
		$template = $templateObj->getVar('ecard_template');
		if (!$template) {
			return $this->getEcardMessageContent();
		} else {
			$ret = str_replace('{MESSAGE}', $this->getVar('message'), $template);
			$ret = str_replace('{SUBJECT}', $this->getVar('subject'), $ret);
			$ret = str_replace('{FROM_NAME}', $this->getVar('from_name'), $ret);
			$ret = str_replace('{FROM_EMAIL}', $this->getVar('from_email'), $ret);
	    	return $ret;
		}
    }

    function getEcardMessageContent() {
		$smartmaillight_template_handler = xoops_getModuleHandler('template', 'smartmaillight');
		$templateObj = $smartmaillight_template_handler->get( $this->getVar('templateid', 'e'));
		$template = $templateObj->getVar('content');
		//$template = $myts->displayTarea($template, true);
		$ret = str_replace('{MESSAGE}', stripslashes($this->getVar('message')), $template);
		$ret = str_replace('{SUBJECT}', $this->getVar('subject'), $ret);
		$ret = str_replace('{FROM_NAME}', $this->getVar('from_name'), $ret);
		$ret = str_replace('{FROM_EMAIL}', $this->getVar('from_email'), $ret);
		return $ret;
    }

    function getEcardMessage() {
		include_once(XOOPS_ROOT_PATH . '/class/template.php');
		$xoopsTpl = new XoopsTpl();
		$xoopsTpl->assign('email_body', $this->getEcardMessageContent());
		$xoopsTpl->assign('email_title', $this->getVar('subject'));
		$xoopsTpl->assign('ecard_notice', sprintf(_CO_SMLIGHT_ECARD_CLICK_LINK, $this->getVar('from_name'), SMARTMAILLIGHT_URL . 'ecard.php?id=' . $this->getVar('pickupid')));
		$xoopsTpl->assign('ecard_powered', _CO_SMLIGHT_ECARD_POWERED);

    	return $xoopsTpl->fetch('db:smartmaillight_email_template.html');
    }

    function getRecipients() {
		$ret = array();
		$recipients = $this->getVar('emails', 'n');
		$recipientsArray = explode("\n", $recipients);
		foreach($recipientsArray as $v) {
    		$v = trim($v);
    		$ret[] = $v;
		}
		return $ret;
    }

	function templateid() {
		$smart_registry = SmartObjectsRegistry::getInstance();
    	$ret = $this->getVar('templateid', 'e');
		$obj = $smart_registry->getSingleObject('template', $ret, 'smartmaillight');

    	if ($obj && !$obj->isNew()) {
    		$ret = $obj->getVar('name');
    	}
    	return $ret;
	}

    function status() {
    	$smartmaillight_ecard_handler = xoops_getModuleHandler('ecard', 'smartmaillight');
    	$ret = $this->getVar('status', 'e');
		$statusArray = $smartmaillight_ecard_handler->getStatus();
    	if (isset($statusArray[$ret])) {
    		return $statusArray[$ret];
    	} else {
    		return false;
    	}
    }
}
class SmartmaillightEcardHandler extends SmartPersistableObjectHandler {

    var $_statusArray=false;

    function SmartmaillightEcardHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'ecard', 'ecardid', 'subject', '', 'smartmaillight');
//		$this->generalSQL = 'SELECT * FROM '.$this->table . " AS " . $this->_itemname . ' JOIN ' . $this->db->prefix('smartmaillight_list') . ' AS list ON ecard.listid=list.listid JOIN ' . $this->db->prefix('smartmaillight_template') . ' AS template ON list.templateid=template.templateid';
    }

	function addRecipients(&$ecardObj) {
		$smartmaillight_recipient_handler = xoops_getModuleHandler('recipient', 'smartmaillight');
		$recipientsArray = $ecardObj->getRecipients();

		$noErrors = true;
		foreach ($recipientsArray as $recipient) {
			$recipientObj = $smartmaillight_recipient_handler->create();
			$recipientObj->setVar('userid', 0);
			$recipientObj->setVar('email_address', $recipient);
			$recipientObj->setVar('ecardid', $ecardObj->id());
			if (!$smartmaillight_recipient_handler->insert($recipientObj, true)) {
				$noErrors = false;
			}
		}
		$ecardObj->setVar('status', SMARTMAILLIGHT_ECARD_STATUS_READY);
		if (!$this->insert($ecardObj, true)) {
			$noErrors = false;
		}

		return $noErrors;
	}

	function geteCardByPickupid($pickupid) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('pickupid', $pickupid));
		$ret = $this->getObjects($criteria);
		if (count($ret) > 0) {
			return $ret[0];
		} else {
			return false;
		}

	}

    function getNewEcards() {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('status', SMARTMAILLIGHT_ECARD_STATUS_NEW));
		$criteria->add(new Criteria('date', time(), '<'));
		$ret = $this->getObjects($criteria);
		return $ret;
    }

    function getReadyEcards() {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('status', SMARTMAILLIGHT_ECARD_STATUS_READY));
		$ret = $this->getObjects($criteria);
		return $ret;
    }

    function purgeSentEcards() {
		$sql =	'SELECT ecardid FROM ' .$this->db->prefix('smartmaillight_ecard') .
				' WHERE ecardid NOT IN ( ' .
				' SELECT DISTINCT recipient.ecardid FROM ' .$this->db->prefix('smartmaillight_ecard') .
				' AS ecard LEFT JOIN ' .$this->db->prefix('smartmaillight_recipient') .
				' AS recipient  ON ecard.ecardid=recipient.ecardid WHERE ecard.status=2 ' .
				' AND recipient.status=0' .
				')';
		$ret = $this->query($sql);
		if ($ret) {
			$ecardArray = array();
			foreach($ret as $ecard) {
				$ecardArray[] = $ecard['ecardid'];
			}

			if (count($ecardArray) > 0) {
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('ecardid', '(' . implode(', ', $ecardArray) . ')', 'IN'));
				$this->updateAll('status', SMARTMAILLIGHT_ECARD_STATUS_SENT, $criteria, true);
			}
		}
    }

    function getStatus() {
		if (!$this->_statusArray) {
			$this->_statusArray = array(
				SMARTMAILLIGHT_ECARD_STATUS_NEW => _CO_SMLIGHT_ECARD_STATUS_NEW,
				SMARTMAILLIGHT_ECARD_STATUS_READY => _CO_SMLIGHT_ECARD_STATUS_READY,
				SMARTMAILLIGHT_ECARD_STATUS_SENT => _CO_SMLIGHT_ECARD_STATUS_SENT
				);
		}
		return $this->_statusArray;
    }

    function getTheseEcards($ecardidArray) {
    	$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('ecardid', '(' . implode(', ', $ecardidArray) . ')', 'IN'));
		$ret = $this->getObjects($criteria, true);
		return $ret;
    }

    function beforeInsert(&$obj) {
		$pickupid = md5($obj->getVar('subject') . $obj->id() . time());
		$obj->setVar('pickupid', $pickupid);
		return true;

    }
}
?>