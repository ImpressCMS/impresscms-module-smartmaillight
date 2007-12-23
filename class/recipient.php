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
// Recipient: The XOOPS Recipient                                               //
// -------------------------------------------------------------------------//

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";

define('SMARTMAILLIGHT_RECIPIENT_STATUS_NEW', 0);
define('SMARTMAILLIGHT_RECIPIENT_STATUS_SENT', 1);

class SmartmaillightRecipient extends SmartObject {

    function SmartmaillightRecipient() {
        $this->quickInitVar('recipientid', XOBJ_DTYPE_INT, true, 'ID');
        $this->quickInitVar('userid', XOBJ_DTYPE_INT, true, _CO_SMLIGHT_RECIPIENT_USERID, _CO_SMLIGHT_RECIPIENT_USERID_DSC);
        $this->quickInitVar('status', XOBJ_DTYPE_INT, true, _CO_SMLIGHT_RECIPIENT_STATUS, _CO_SMLIGHT_RECIPIENT_STATUS_DSC, SMARTMAILLIGHT_RECIPIENT_STATUS_NEW);
        $this->quickInitVar('messageid', XOBJ_DTYPE_INT, false, _CO_SMLIGHT_RECIPIENT_MESSAGEID);
        $this->quickInitVar('ecardid', XOBJ_DTYPE_INT, false, _CO_SMLIGHT_RECIPIENT_ECARDID);
        $this->quickInitVar('email_address', XOBJ_DTYPE_TXTBOX, true, _CO_SMLIGHT_RECIPIENT_EMAIL_ADDRESS, _CO_SMLIGHT_RECIPIENT_EMAIL_ADDRESS_DSC);

        $this->initNonPersistableVar('listid', XOBJ_DTYPE_INT, 'list',_CO_SMLIGHT_RECIPIENT_LISTID);

		$this->setControl('userid', array('itemHandler' => 'user',
                                  'method' => 'getList',
                                  'module' => 'smartmaillight'));

		$this->setControl('status', array('itemHandler' => 'recipient',
                                  'method' => 'getStatus',
                                  'module' => 'smartmaillight'));
		$this->hideFieldFromForm(array('listid'));
    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('status', 'listid', 'ecardid', 'userid'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

	function getUserUid() {
		$smart_registry = SmartObjectsRegistry::getInstance();
    	$ret = $this->getVar('userid', 'e');
		$obj = $smart_registry->getSingleObject('user', $ret, 'smartmaillight');

    	if ($obj && !$obj->isNew()) {
    		$ret = $obj->getVar('uid', 'e');
    	}
    	return $ret;
	}

	function listid() {
		$smart_registry = SmartObjectsRegistry::getInstance();
    	$ret = $this->getVar('messageid', 'e');
		$obj = $smart_registry->getSingleObject('message', $ret, 'smartmaillight');

    	if ($obj && !$obj->isNew()) {
    		return $ret = $obj->getVar('listid');
    	} else {
    		return '';
    	}
	}

	function userid() {
		$smart_registry = SmartObjectsRegistry::getInstance();
    	$ret = $this->getVar('userid', 'e');
		$obj = $smart_registry->getSingleObject('user', $ret, 'smartmaillight');

    	if ($obj && !$obj->isNew()) {
    		return $obj->getVar('uid');
    	} else {
    		return '';
    	}
	}

	function ecardid() {
		$smart_registry = SmartObjectsRegistry::getInstance();
    	$ret = $this->getVar('ecardid', 'e');
		$obj = $smart_registry->getSingleObject('ecard', $ret, 'smartmaillight');

    	if ($obj && !$obj->isNew()) {
    		return $obj->getVar('subject');
    	} else {
    		return '';
    	}
	}

    function status() {
    	$smartmaillight_recipient_handler = xoops_getModuleHandler('recipient', 'smartmaillight');
    	$ret = $this->getVar('status', 'e');
    	$statusArray = $smartmaillight_recipient_handler->getStatus();
    	if (isset($statusArray[$ret])) {
    		return $statusArray[$ret];
    	} else {
    		return false;
    	}
    }
}
class SmartmaillightRecipientHandler extends SmartPersistableObjectHandler {

	var $_statusArray=false;

    function SmartmaillightRecipientHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'recipient', 'recipientid', 'uid', 'duration', 'smartmaillight');
    }

    function getStatus() {
		if (!$this->_statusArray) {
			$this->_statusArray = array(
				SMARTMAILLIGHT_RECIPIENT_STATUS_NEW => _CO_SMLIGHT_RECPIENT_STATUS_NEW,
				SMARTMAILLIGHT_RECIPIENT_STATUS_SENT => _CO_SMLIGHT_RECPIENT_STATUS_SENT
				);
		}
		return $this->_statusArray;
    }

    function getReadyRecipients($limit=100) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('status', 0));
		$ret = $this->getObjects($criteria);
		return $ret;
    }
}
?>