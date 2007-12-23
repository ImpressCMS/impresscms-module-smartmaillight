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
// Project: The XOOPS Project                                               //
// -------------------------------------------------------------------------//

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";
class SmartmaillightList extends SmartObject {

	var $_fromName=false;
	var $_fromEmail=false;

    function SmartmaillightList() {
        $this->quickInitVar('listid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true, _CO_SMLIGHT_LIST_TITLE, _CO_SMLIGHT_LIST_TITLE_DSC);
        $this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, false, _CO_SMLIGHT_LIST_DESCRIPTION, _CO_SMLIGHT_LIST_DESCRIPTION_DSC);
        $this->quickInitVar('from_name', XOBJ_DTYPE_TXTBOX, false, _CO_SMLIGHT_LIST_FROM_NAME, _CO_SMLIGHT_LIST_FROM_NAME_DSC);
        $this->quickInitVar('from_email', XOBJ_DTYPE_TXTBOX, false, _CO_SMLIGHT_LIST_FROM_EMAIL, _CO_SMLIGHT_LIST_FROM_EMAIL_DSC);
        $this->quickInitVar('templateid', XOBJ_DTYPE_INT, true, _CO_SMLIGHT_LIST_TEMPLATE, _CO_SMLIGHT_LIST_TEMPLATE_DSC);
        $this->initNonPersistableVar('import_groups', XOBJ_DTYPE_ARRAY, false, _CO_SMLIGHT_LIST_IMPORT_GROUPS, false, false, true);
        $this->setVarInfo('import_groups', 'form_dsc', _CO_SMLIGHT_LIST_IMPORT_GROUPS_DSC);

		$this->setControl('templateid', array('itemHandler' => 'template',
                                  'method' => 'getList',
                                  'module' => 'smartmaillight'));
		$this->setControl('import_groups', 'group_multi');

		$this->hideFieldFromSingleView('import_groups');
    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('templateid', 'from_email', 'from_name'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

	function from_name() {
		if (!$this->_fromName) {
			global $xoopsConfig;
			$fromName = $this->getVar('from_name', 'e');
			if ($fromName && $fromName != '') {
				$this->_fromName = $fromName;
			} else {
				$this->_fromName = $xoopsConfig['sitename'];
			}
		}
		return $this->_fromName;
	}

	function from_email() {
		if (!$this->_fromEmail) {
			global $xoopsConfig;
			$fromEmail = $this->getVar('from_email', 'e');
			if ($fromEmail && $fromEmail != '') {
				$this->_fromEmail = $fromEmail;
			} else {
				$this->_fromEmail = $xoopsConfig['adminmail'];
			}
		}
		return $this->_fromEmail;
	}

	function templateid() {
		$smart_registry = SmartObjectsRegistry::getInstance();
    	$ret = $this->getVar('templateid', 'e');
		$obj = $smart_registry->getSingleObject('template', $ret, 'smartmaillight');

    	if (!$obj->isNew()) {
    		$ret = $obj->getVar('name');
    	}
    	return $ret;
	}
}
class SmartmaillightListHandler extends SmartPersistableObjectHandler {
    function SmartmaillightListHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'list', 'listid', 'title', 'description', 'smartmaillight');
    }

    function afterSave($obj) {
		if (isset($_POST['import_groups'])) {
			$group_handler = xoops_getHandler('membership');
			$smartmaillight_user_handler = xoops_getModuleHandler('user', 'smartmaillight');
			foreach($_POST['import_groups'] as $groupid) {
				$users = $group_handler->getUsersByGroup($groupid);
				if (count($users) > 0) {
					foreach($users as $uid) {
						$listid = $obj->id();
						$subscriber = $smartmaillight_user_handler->getUserForListId($uid, $listid);
						if ($subscriber->isNew()) {
					    	$subscriber->setVar('uid', $uid);
					    	$subscriber->setVar('listid', $listid);
					    	$subscriber->setVar('active', true);
					    	$smartmaillight_user_handler->insert($subscriber);
						}
					}
				}
			}
		}
		return true;
    }
}
?>