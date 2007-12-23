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
// Message: The XOOPS Message                                               //
// -------------------------------------------------------------------------//

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";

define('SMARTMAILLIGHT_MESSAGE_STATUS_NEW', 0);
define('SMARTMAILLIGHT_MESSAGE_STATUS_READY', 2);
define('SMARTMAILLIGHT_MESSAGE_STATUS_SENT', 1);

class SmartmaillightMessage extends SmartObject {

    function SmartmaillightMessage() {
        $this->quickInitVar('messageid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('listid', XOBJ_DTYPE_INT, true, _CO_SMLIGHT_MESSAGE_LISTID, _CO_SMLIGHT_MESSAGE_LISTID_DSC);
        $this->quickInitVar('subject', XOBJ_DTYPE_TXTBOX, true, _CO_SMLIGHT_MESSAGE_SUBJECT, _CO_SMLIGHT_MESSAGE_SUBJECT_DSC);
		$this->quickInitVar('body', XOBJ_DTYPE_TXTAREA, true, _CO_SMLIGHT_MESSAGE_BODY, _CO_SMLIGHT_MESSAGE_BODY_DSC);
		$this->quickInitVar('date', XOBJ_DTYPE_LTIME, true, _CO_SMLIGHT_MESSAGE_DATE, _CO_SMLIGHT_MESSAGE_DATE_DSC);
		$this->quickInitVar('status', XOBJ_DTYPE_INT, false, _CO_SMLIGHT_MESSAGE_STATUS, _CO_SMLIGHT_MESSAGE_STATUS_DSC);
		$this->quickInitVar('archived', XOBJ_DTYPE_INT, false, _CO_SMLIGHT_MESSAGE_ARCHIVED, _CO_SMLIGHT_MESSAGE_ARCHIVED_DSC);
		$this->quickInitVar('compiled_message', XOBJ_DTYPE_TXTAREA, false);

		$this->initCommonVar('counter', false);

		$this->initNonPersistableVar('content', XOBJ_DTYPE_TXTAREA, 'template');

		global $smartmaillightConfig;
		$this->initCommonVar('dohtml', true, $smartmaillightConfig['default_dohtml']);
		$this->initCommonVar('dobr', true, $smartmaillightConfig['default_dobr']);

		$this->setControl('listid', array('itemHandler' => 'list',
                                  'method' => 'getList',
                                  'module' => 'smartmaillight'));

		$this->setControl('status', array('itemHandler' => 'message',
                                  'method' => 'getStatus',
                                  'module' => 'smartmaillight'));

		$this->setControl('archived', 'yesno');

		$this->hideFieldFromForm(array('compiled_message'));
		//$this->makeFieldReadOnly(array('status'));
    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('listid', 'status'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

	function listid() {
		$smart_registry = SmartObjectsRegistry::getInstance();
    	$ret = $this->getVar('listid', 'e');
		$obj = $smart_registry->getSingleObject('list', $ret, 'smartmaillight');

    	if (!$obj->isNew()) {
    		$ret = $obj->getVar('title');
    	}
    	return $ret;
	}

	function compile() {
		$listid = $this->getVar('listid', 'e');
		$messageBody = $this->getVar('body');
		$template = $this->getVar('content');
		if (!$template) {
			// then we are creating the item so the "JOIN" query in the getobjects was not use. We need to manually retreive the template
			$smartmaillight_list_handler = xoops_getModuleHandler('list', 'smartmaillight');
			$smartmaillight_template_handler = xoops_getModuleHandler('template', 'smartmaillight');
			$listObj = $smartmaillight_list_handler->get($listid);
			$templateObj = $smartmaillight_template_handler->get($listObj->getVar('templateid', 'e'));
			$template = $templateObj->getVar('content');
		}
		$ret = str_replace('{MESSAGE}', $messageBody, $template);
		return $ret;
	}

	function getFromName() {
		$smart_registry = SmartObjectsRegistry::getInstance();
    	$ret = $this->getVar('listid', 'e');
		$obj = $smart_registry->getSingleObject('list', $ret, 'smartmaillight');

    	if (!$obj->isNew()) {
    		$ret = $obj->getVar('from_name');
    	}
    	return $ret;
	}

	function getFromEmail() {
		$smart_registry = SmartObjectsRegistry::getInstance();
    	$ret = $this->getVar('listid', 'e');
		$obj = $smart_registry->getSingleObject('list', $ret, 'smartmaillight');

    	if (!$obj->isNew()) {
    		$ret = $obj->getVar('from_email');
    	}
    	return $ret;
	}

    function status() {
    	$smartmaillight_message_handler = xoops_getModuleHandler('message', 'smartmaillight');
    	$ret = $this->getVar('status', 'e');
		$statusArray = $smartmaillight_message_handler->getStatus();
    	if (isset($statusArray[$ret])) {
    		return $statusArray[$ret];
    	} else {
    		return false;
    	}
    }
}
class SmartmaillightMessageHandler extends SmartPersistableObjectHandler {

    var $_statusArray=false;

    function SmartmaillightMessageHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'message', 'messageid', 'subject', '', 'smartmaillight');
		$this->generalSQL = 'SELECT * FROM '.$this->table . " AS " . $this->_itemname . ' JOIN ' . $this->db->prefix('smartmaillight_list') . ' AS list ON message.listid=list.listid JOIN ' . $this->db->prefix('smartmaillight_template') . ' AS template ON list.templateid=template.templateid';
    }

	function addRecipients(&$messageObj) {
		$smartmaillight_user_handler = xoops_getModuleHandler('user', 'smartmaillight');
		$smartmaillight_recipient_handler = xoops_getModuleHandler('recipient', 'smartmaillight');

		// find all the users registered to the related mailing list
		$listid = $messageObj->getVar('listid', 'e');
		$usersObj = $smartmaillight_user_handler->getUsersForList($listid);
		$noErrors = true;
		foreach ($usersObj as $userObj) {
			$recipientObj = $smartmaillight_recipient_handler->create();
			$recipientObj->setVar('userid', $userObj->getVar('userid'));
			$recipientObj->setVar('messageid', $messageObj->id());
			if (!$smartmaillight_recipient_handler->insert($recipientObj, true)) {
				$noErrors = false;
			}
		}
		$messageObj->setVar('status', SMARTMAILLIGHT_MESSAGE_STATUS_READY);
		if (!$this->insert($messageObj, true)) {
			$noErrors = false;
		}

		return $noErrors;
	}

    function beforeSave(&$obj) {
		$obj->setVar('compiled_message', $obj->compile());
    	return true;
    }

    function getNewMessages() {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('status', SMARTMAILLIGHT_MESSAGE_STATUS_NEW));
		$criteria->add(new Criteria('date', time(), '<'));
		$ret = $this->getObjects($criteria);
		return $ret;
    }

    function getReadyMessages() {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('status', SMARTMAILLIGHT_MESSAGE_STATUS_READY));
		$ret = $this->getObjects($criteria);
		return $ret;
    }

    function purgeSentMessages() {
		$sql =	'SELECT messageid FROM ' .$this->db->prefix('smartmaillight_message') .
				' WHERE messageid NOT IN ( ' .
				' SELECT DISTINCT recipient.messageid FROM ' .$this->db->prefix('smartmaillight_message') .
				' AS message LEFT JOIN ' .$this->db->prefix('smartmaillight_recipient') .
				' AS recipient  ON message.messageid=recipient.messageid WHERE message.status=2 ' .
				' AND recipient.status=0' .
				')';
		$ret = $this->query($sql);
		if ($ret) {
			$messageArray = array();
			foreach($ret as $message) {
				$messageArray[] = $message['messageid'];
			}

			if (count($messageArray) > 0) {
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('messageid', '(' . implode(', ', $messageArray) . ')', 'IN'));
				$this->updateAll('status', SMARTMAILLIGHT_MESSAGE_STATUS_SENT, $criteria, true);
			}
		}
    }

    function getStatus() {
		if (!$this->_statusArray) {
			$this->_statusArray = array(
				SMARTMAILLIGHT_MESSAGE_STATUS_NEW => _CO_SMLIGHT_MESSAGE_STATUS_NEW,
				SMARTMAILLIGHT_MESSAGE_STATUS_READY => _CO_SMLIGHT_MESSAGE_STATUS_READY,
				SMARTMAILLIGHT_MESSAGE_STATUS_SENT => _CO_SMLIGHT_MESSAGE_STATUS_SENT
				);
		}
		return $this->_statusArray;
    }

    function getTheseMessages($messageidArray) {
    	$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('messageid', '(' . implode(', ', $messageidArray) . ')', 'IN'));
		$ret = $this->getObjects($criteria, true);
		return $ret;
    }
}
?>