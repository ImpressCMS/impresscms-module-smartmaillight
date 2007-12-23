<?php

if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

global $modversion;
if( ! empty( $_POST['fct'] ) && ! empty( $_POST['op'] ) && $_POST['fct'] == 'modulesadmin' && $_POST['op'] == 'update_ok' && $_POST['dirname'] == $modversion['dirname'] ) {
	// referer check
	$ref = xoops_getenv('HTTP_REFERER');
	if( $ref == '' || strpos( $ref , XOOPS_URL.'/modules/system/admin.php' ) === 0 ) {
		/* module specific part */



		/* General part */

		// Keep the values of block's options when module is updated (by nobunobu)
		include dirname( __FILE__ ) . "/updateblock.inc.php" ;

	}
}

function xoops_module_update_smartmaillight($module) {

	include_once(XOOPS_ROOT_PATH . "/modules/" . $module->getVar('dirname') . "/include/functions.php");
	include_once(XOOPS_ROOT_PATH . "/modules/smartobject/class/smartdbupdater.php");

	$dbupdater = new SmartobjectDbupdater();

    ob_start();

    $dbVersion  = smart_GetMeta('version', 'smartmaillight');
    if (!$dbVersion) {
    	$dbVersion = 0;
    }

	$dbupdater = new SmartobjectDbupdater();

	echo "<code>" . _SDU_UPDATE_UPDATING_DATABASE . "<br />";


    // db migrate version = 2
    $newDbVersion = 2;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_recipient');
    	$table->addNewField('messageid', "int(11) NOT NULL default 0");
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

    // db migrate version = 3
    $newDbVersion = 3;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_message');
    	$table->addNewField('from_name', "VARCHAR(255) NOT NULL default ''");
    	$table->addNewField('from_email', "VARCHAR(255) NOT NULL default ''");
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

    // db migrate version = 4
    $newDbVersion = 4;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_message');
    	$table->addNewField('archived', "INT(1) NOT NULL default 0");
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

    // db migrate version = 5
    $newDbVersion = 5;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_message');
    	$table->addDropedField('from_name');
    	$table->addDropedField('from_email');
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
	    $table = new SmartDbTable('smartmaillight_list');
    	$table->addNewField('from_name', "VARCHAR(255) NOT NULL default ''");
    	$table->addNewField('from_email', "VARCHAR(255) NOT NULL default ''");
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

    // db migrate version = 6
    $newDbVersion = 6;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_template');
    	$table->addNewField('enable_ecard', "INT(1) NOT NULL default 0");
    	$table->addNewField('screenshot', "VARCHAR(255) NOT NULL default ''");
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }

		// Create table smartmaillight_ecard
	    $table = new SmartDbTable('smartmaillight_ecard');
	    $table->setStructure("
		  `ecardid` int(11) NOT NULL auto_increment,
		  `templateid` int(11) NOT NULL default 0,
		  `subject` varchar(255) NOT NULL default '',
		  `body` TEXT NOT NULL default '',
		  `from_name` varchar(255) NOT NULL default '',
		  `from_email` varchar(255) NOT NULL default '',
		  `date` int(11) NOT NULL default 0,
		  `emails` TEXT NOT NULL default '',
		  `status` int(1) NOT NULL default 0,
		  PRIMARY KEY  (`ecardid`)
		");

	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	  	}
    }

    // db migrate version = 7
    $newDbVersion = 7;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_ecard');
    	$table->addAlteredField('body', "TEXT NOT NULL default ''", 'message');
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

    $newDbVersion = 8;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_template');
    	$table->addAlteredField('content', "TEXT NOT NULL default ''", 'content_html');
    	$table->addNewField('content_plain', "TEXT NOT NULL default ''");
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

    $newDbVersion = 9;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_template');
    	$table->addAlteredField('content_html', "TEXT NOT NULL default ''", 'content');
    	$table->addAlteredField('content_plain', "TEXT NOT NULL default ''", 'ecard_template');
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

    $newDbVersion = 10;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_recipient');
    	$table->addNewField('ecardid', "INT(11) NOT NULL default 0");
    	$table->addNewField('email_address', "VARCHAR(255) NOT NULL default ''");
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

    $newDbVersion = 11;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_user');
    	$table->addNewField('active', "INT(1) NOT NULL default 1");
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

    $newDbVersion = 12;

    if ($dbVersion < $newDbVersion) {
    	echo "Database migrate to version " . $newDbVersion . "<br />";

	    $table = new SmartDbTable('smartmaillight_ecard');
    	$table->addNewField('pickupid', "VARCHAR(255) NOT NULL default ''");
	    if (!$dbupdater->updateTable($table)) {
	        /**
	         * @todo trap the errors
	         */
	    }
    }

	echo "</code>";

    $feedback = ob_get_clean();
    if (method_exists($module, "setMessage")) {
        $module->setMessage($feedback);
    } else {
        echo $feedback;
    }
    smart_SetMeta("version", $newDbVersion, "smartmaillight"); //Set meta version to current
    return true;
}

function xoops_module_install_smartmaillight($module) {

    ob_start();

	include_once(XOOPS_ROOT_PATH . "/modules/" . $module->getVar('dirname') . "/include/functions.php");

	//smartmaillight_create_upload_folders();

    $feedback = ob_get_clean();
    if (method_exists($module, "setMessage")) {
        $module->setMessage($feedback);
    }
    else {
        echo $feedback;
    }

	return true;
}


?>