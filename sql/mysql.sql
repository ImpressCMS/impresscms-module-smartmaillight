CREATE TABLE `smartmaillight_list` (
  `listid` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `description` TEXT NOT NULL default '',
  `templateid` int(11) NOT NULL default 0,
    `from_name` varchar(255) NOT NULL default '',
  `from_email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`listid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartmaillight_template` (
    `templateid` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `content` text NOT NULL,
  `template_dohtml` int(11) NOT NULL default '0',
  `template_dobr` int(11) NOT NULL default '0',
  `enable_ecard` int(1) NOT NULL default '0',
  `screenshot` varchar(255) NOT NULL default '',
  `ecard_template` text NOT NULL,
  PRIMARY KEY  (`templateid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;


CREATE TABLE `smartmaillight_user` (
  `userid` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `listid` int(11) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartmaillight_message` (
  `messageid` int(11) NOT NULL auto_increment,
  `listid` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `compiled_message` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  `status` int(1) NOT NULL default '0',
  `counter` int(11) NOT NULL default '0',
  `dohtml` int(11) NOT NULL default '0',
  `dobr` int(11) NOT NULL default '0',
  `archived` int(1) NOT NULL default '0',
  PRIMARY KEY  (`messageid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartmaillight_recipient` (
 `recipientid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `status` int(1) NOT NULL default '0',
  `messageid` int(1) NOT NULL default '0',
  `ecardid` int(11) NOT NULL default '0',
  `email_address` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`recipientid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartmaillight_meta` (
  `metakey` varchar(50) NOT NULL default '',
  `metavalue` varchar(255) NOT NULL default '',
  PRIMARY KEY (`metakey`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' ;

INSERT INTO `smartmaillight_meta` VALUES ('version',1);
