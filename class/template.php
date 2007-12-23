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
include_once XOOPS_ROOT_PATH . "/modules/smartobject/class/smartobject.php";
class SmartmaillightTemplate extends SmartObject {
	function SmartmaillightTemplate() {
		global $xoopsModuleConfig;

		$this->quickInitVar('templateid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('name', XOBJ_DTYPE_TXTBOX, true, _CO_SMLIGHT_TEMPLATE_NAME, _CO_SMLIGHT_TEMPLATE_NAME_DSC);
		$this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, false, _CO_SMLIGHT_TEMPLATE_DESCRIPTION, _CO_SMLIGHT_TEMPLATE_DESCRIPTION_DSC);
		$this->quickInitVar('content', XOBJ_DTYPE_TXTAREA, true, _CO_SMLIGHT_TEMPLATE_CONTENT, _CO_SMLIGHT_TEMPLATE_CONTENT_DSC);
		if ($xoopsModuleConfig['enable_ecard']) {
			$this->quickInitVar('enable_ecard', XOBJ_DTYPE_INT, false, _CO_SMLIGHT_TEMPLATE_ENABLE_ECARD, _CO_SMLIGHT_TEMPLATE_ENABLE_ECARD_DSC);
			$this->quickInitVar('screenshot', XOBJ_DTYPE_TXTBOX, false, _CO_SMLIGHT_TEMPLATE_SCREENSHOT, _CO_SMLIGHT_TEMPLATE_SCREENSHOT_DSC);
			$this->quickInitVar('ecard_template', XOBJ_DTYPE_TXTAREA, false, _CO_SMLIGHT_TEMPLATE_ECARD_TEMPLATE, _CO_SMLIGHT_TEMPLATE_ECARD_TEMPLATE_DSC);
		}

		// Since Message is join to Template in SQL, the dobr and dohtml field need to be prefixed
		global $smartmaillightConfig;
		$this->quickInitVar('template_dohtml', XOBJ_DTYPE_INT, false, _CO_SOBJECT_DOHTML_FORM_CAPTION, '', $smartmaillightConfig['default_dohtml']);
        $this->quickInitVar('template_dobr', XOBJ_DTYPE_INT, false, _CO_SOBJECT_DOBR_FORM_CAPTION, '', $smartmaillightConfig['default_dobr']);

		$this->initNonPersistableVar('dohtml', XOBJ_DTYPE_INT);
		$this->initNonPersistableVar('dobr', XOBJ_DTYPE_INT);

        $this->setControl('template_dohtml', "yesno");
        $this->setControl('template_dobr', "yesno");

        $this->setControl('content_plain', array('name' => 'textarea',
                                        'form_editor' => 'textarea',
                                        'rows' => 15,
                                        'cols' => 100));

        $this->setControl('description', array('name' => 'textarea',
                                        'form_editor' => 'textarea'));

		$this->setControl('enable_ecard', "yesno");
		$this->setControl('screenshot', "image");
	}

	function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array ('content', 'ecard_template'))) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}

	function getTemplateLink() {
		return $this->getVar('name');
	}

	function content() {
		$ret = $this->getVar('content', 'n');
		$myts = MyTextSanitizer::getInstance();
		$ret = $myts->displayTarea($ret, $this->getVar('template_dohtml'), true, true, true, $this->getVar('template_dobr'));
		return $ret;
	}

	function ecard_template() {
		$ret = $this->getVar('ecard_template', 'n');
		$myts = MyTextSanitizer::getInstance();
		$ret = $myts->displayTarea($ret, $this->getVar('template_dohtml'), true, true, true, $this->getVar('template_dobr'));
		return $ret;
	}
}
class SmartmaillightTemplateHandler extends SmartPersistableObjectHandler {
	function SmartmaillightTemplateHandler($db) {
		$this->SmartPersistableObjectHandler($db, 'template', 'templateid', 'name', 'description', 'smartmaillight');

		$mimetypes= array(
						'text/comma-separated-values',
						'text/csv',
						'application/csv',
						'application/excel',
						'application/vnd.ms-excel',
						'application/vnd.msexcel',
						'text/anytext',
						'application/x-csv'
						);
		$this->setUploaderConfig(false, $mimetypes, 300000, false, false);
	}

	function getTemplatesForEcard() {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('enable_ecard', 1));
		return $this->getObjects($criteria, false, false);
	}
}
?>