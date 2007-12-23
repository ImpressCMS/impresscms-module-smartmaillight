CREATE TABLE `smartmaillight_list` (
  `listid` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `description` TEXT NOT NULL default '',
  `templateid` int(11) NOT NULL default 0,
  PRIMARY KEY  (`listid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartmaillight_template` (
  `templateid` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` TEXT NOT NULL default '',
  `content` TEXT NOT NULL default '',
  `template_dohtml` int(11) NOT NULL default 0,
  `template_dobr` int(11) NOT NULL default 0,
  PRIMARY KEY  (`templateid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;


CREATE TABLE `smartmaillight_user` (
  `userid` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default 0,
  `listid` int(11) NOT NULL default 0,
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartmaillight_message` (
  `messageid` int(11) NOT NULL auto_increment,
  `listid` int(11) NOT NULL default 0,
  `subject` VARCHAR(255) NOT NULL default '',
  `body` TEXT NOT NULL default '',
  `compiled_message` TEXT NOT NULL default '',
  `date` int(11) NOT NULL default 0,
  `status` int(1) NOT NULL default 0,
  `counter` int(11) NOT NULL default 0,
  `dohtml` int(11) NOT NULL default 0,
  `dobr` int(11) NOT NULL default 0,
  PRIMARY KEY  (`messageid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartmaillight_recipient` (
  `recipientid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default 0,
  `status` INT(1) NOT NULL default 0,
  `messageid` INT(1) NOT NULL default 0,
  PRIMARY KEY  (`recipientid`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartmaillight_meta` (
  `metakey` varchar(50) NOT NULL default '',
  `metavalue` varchar(255) NOT NULL default '',
  PRIMARY KEY (`metakey`)
) TYPE=MyISAM COMMENT='SmartMailLight by The SmartFactory <www.smartfactory.ca>' ;

INSERT INTO `smartmaillight_meta` VALUES ('version',1);
