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

class SmartmaillightMailer {

	var $_logs;

	function addLog($str) {
		$this->_logs[] = $str;
	}

	function execute() {
		global $xoopsConfig;

		$smartmaillight_config = smart_getModuleConfig('smartmaillight');

		$smartmaillight_message_handler = xoops_getModuleHandler('message', 'smartmaillight');
		$smartmaillight_recipient_handler = xoops_getModuleHandler('recipient', 'smartmaillight');
		$smartmaillight_ecard_handler = xoops_getModuleHandler('ecard', 'smartmaillight');

		// are there any New messages
		$newMessagesObj = $smartmaillight_message_handler->getNewMessages();
		if ($newMessagesObj) {
			foreach($newMessagesObj as $newMessageObj) {
				$this->addLog('Message : "' . $newMessageObj->getVar('subject') . '" is new');
				if (!$smartmaillight_message_handler->addRecipients($newMessageObj)) {
					$this->addLog('An error occured while adding the recipients');
				} else {
					$this->addLog('Recipients added and message ready to be sent');
				}
			}
		}

		//$smartmaillight_message_handler->purgeSentMessages();

		// are there any New ecards
		$newEcardsObj = $smartmaillight_ecard_handler->getNewEcards();
		if ($newEcardsObj) {
			foreach($newEcardsObj as $newEcardObj) {
				$this->addLog('eCard : "' . $newEcardObj->getVar('subject') . '" is new');
				if (!$smartmaillight_ecard_handler->addRecipients($newEcardObj)) {
					$this->addLog('An error occured while adding the recipients');
				} else {
					$this->addLog('Recipients added and ecard ready to be sent');
				}
			}
		}

		//$smartmaillight_ecard_handler->purgeSentEcards();

		// are there any email to send
		$recipentsObj = $smartmaillight_recipient_handler->getReadyRecipients();

		// first, let's loop throughout the recipients to get all the uid, messageid and ecardid
		$uidArray=array();
		$messageidArray=array();
		$ecardidArray=array();
		$mailArray=array();
		foreach ($recipentsObj as $recipientObj) {
			$uid = $recipientObj->getUserUid();
			if ($uid) {
				$uidArray[$uid] = $uid;
			}
			$messageid = $recipientObj->getVar('messageid', 'e');
			if ($messageid) {
				$messageidArray[$messageid] = $messageid;
			}
			$ecardid = $recipientObj->getVar('ecardid', 'e');
			if ($ecardid) {
				$ecardidArray[$ecardid] = $ecardid;
			}
		}

		// fetch the concerned messagesObj
		$messagesObj = $smartmaillight_message_handler->getTheseMessages($messageidArray);

		// fetch the concerned ecardsObj
		$ecardsObj = $smartmaillight_ecard_handler->getTheseEcards($ecardidArray);

		// fetch the concerned usersObj
		$member_handler = xoops_getHandler('member');
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('uid', '(' . implode(', ', $uidArray) . ')', 'IN'));
		$usersObj = $member_handler->getUsers($criteria, true);

		$xoopsMailer =& getMailer();

		// now loop another time in the recipientsObj array and actually get some mail sending done !
		foreach ($recipentsObj as $recipientObj) {
			$uid = $recipientObj->getUserUid();
			$messageid = $recipientObj->getVar('messageid', 'e');
			$ecardid = $recipientObj->getVar('ecardid', 'e');

			$xoopsMailer->useMail();

			if ($messageid) {
				$messageObj =& $messagesObj[$messageid];
				$toUser =& $usersObj[$uid];
				$xoopsMailer->setToUsers($toUser);
				$xoopsMailer->setFromEmail($messageObj->getFromEmail());
				$xoopsMailer->setFromName($messageObj->getFromName());
				$xoopsMailer->setSubject($messageObj->getVar('subject'));
				$xoopsMailer->setBody($messageObj->getVar('compiled_message'));
				$this->addLog('sending message ' . $messageObj->getVar('subject') . ' ' . 'to ' . $toUser->getVar('email'));
			} elseif($ecardid) {
				$ecardObj =& $ecardsObj[$ecardid];
				$toEmail = $recipientObj->getVar('email_address');
				$xoopsMailer->setToEmails($toEmail);

				$xoopsMailer->setFromEmail($ecardObj->getVar('from_email'));
				$xoopsMailer->setFromName($ecardObj->getVar('from_name'));
				$xoopsMailer->setSubject($ecardObj->getVar('subject'));

				$xoopsMailer->setBody($ecardObj->getEcardMessage());

				$this->addLog('sending ecard ' . $ecardObj->getVar('subject') . ' ' . 'to ' . $toEmail);
			}

			$xoopsMailer->multimailer->IsHTML(true);
			if (!$xoopsMailer->send(true)) {
				$this->addLog('an error occured while sending the message : ' . $xoopsMailer->getErrors(true));
			} else {
				// set the status of this recipient as sent
				$recipientObj->setVar('status', SMARTMAILLIGHT_RECIPIENT_STATUS_SENT);
				$smartmaillight_recipient_handler->insert($recipientObj, true);
			}
			$xoopsMailer->reset();
		}
	}

	function getLogs() {
		return $this->_logs;
	}
}
?>