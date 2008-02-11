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
// User: The XOOPS User                                               //
// -------------------------------------------------------------------------//

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";
class SmartmaillightUser extends SmartObject {

    function SmartmaillightUser() {
        $this->quickInitVar('userid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('uid', XOBJ_DTYPE_INT, false, _CO_SMLIGHT_USER_UID, _CO_SMLIGHT_USER_UID_DSC);
        $this->quickInitVar('listid', XOBJ_DTYPE_INT, false, _CO_SMLIGHT_USER_LISTID, _CO_SMLIGHT_USER_LISTID_DSC);
        $this->quickInitVar('email', XOBJ_DTYPE_TXTBOX, false, _CO_SMLIGHT_USER_EMAIL, _CO_SMLIGHT_USER_EMAIL_DSC);
       	$this->quickInitVar('active', XOBJ_DTYPE_INT, false, _CO_SMLIGHT_USER_ACTIVE, _CO_SMLIGHT_USER_ACTIVE_DSC, true);

		$this->setControl('uid', 'user');
		$this->setControl('listid', array('itemHandler' => 'list',
                                  'method' => 'getList',
                                  'module' => 'smartmaillight'));

		$this->setControl('active', 'yesno');
    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('listid', 'uid'))) {
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

    function uid() {
        return smart_getLinkedUnameFromId($this->getVar('uid', 'e'), false);
    }
}
class SmartmaillightUserHandler extends SmartPersistableObjectHandler {
    function SmartmaillightUserHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'user', 'userid', 'uid', 'listid', 'smartmaillight');
    }

    function addUserToList($uid, $listid, $is_subscribed=true, $email='') {
    	if($uid){
    		$userObj = $this->getUserForListId($uid, $listid);
    	}else{
    		$userObj = $this->getUserForListIdEmail($email, $listid);
    	}
    	$userObj->setVar('uid', $uid);
    	$userObj->setVar('listid', $listid);
    	$userObj->setVar('active', $is_subscribed);
    	$userObj->setVar('email', $email);
    	return $this->insert($userObj);
    }

    function deleteListsByUid($uid) {
    	$criteria = new CriteriaCompo();
    	$criteria->add(new Criteria('uid', $uid));
    	$ret = $this->deleteAll($criteria);
    	return $ret;
    }

    function getListidsForUid($uid) {
    	$criteria = new CriteriaCompo();
    	$criteria->add(new Criteria('uid', $uid));
    	$criteria->add(new Criteria('active', true));
    	$usersObj = $this->getObjects($criteria);
    	$ret = array();
    	foreach($usersObj as $userObj) {
    		$listid = $userObj->getVar('listid', 'e');
    		$ret[$listid] = $listid;
    	}
    	return $ret;
    }

     function getListidsForEmail($email) {
    	$criteria = new CriteriaCompo();
    	$criteria->add(new Criteria('email', $email));
    	$criteria->add(new Criteria('active', true));
    	$usersObj = $this->getObjects($criteria);
    	$ret = array();
    	foreach($usersObj as $userObj) {
    		$listid = $userObj->getVar('listid', 'e');
    		$ret[$listid] = $listid;
    	}
    	return $ret;
    }

    function getUsersForList($listid) {
    	$criteria = new CriteriaCompo();
    	$criteria->add(new Criteria('listid', $listid));
    	$criteria->add(new Criteria('active', true));
    	return $this->getObjects($criteria);
    }



	function getUserForListIdEmail($email, $listid) {
    	$criteria = new CriteriaCompo();
    	$criteria->add(new Criteria('email', $email));
    	$criteria->add(new Criteria('listid', $listid));
    	$ret = $this->getObjects($criteria);
    	if (count($ret) == 0) {
    		return $this->create();
    	} else {
    		return $ret[0];
    	}
    }

    function getUserForListId($uid, $listid) {
    	$criteria = new CriteriaCompo();
    	$criteria->add(new Criteria('uid', $uid));
    	$criteria->add(new Criteria('listid', $listid));
    	$ret = $this->getObjects($criteria);
    	if (count($ret) == 0) {
    		return $this->create();
    	} else {
    		return $ret[0];
    	}
    }
}
?>