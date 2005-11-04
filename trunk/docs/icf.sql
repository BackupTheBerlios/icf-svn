CREATE TABLE `icfUser` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  `nick` varchar(80) NOT NULL default '',
  `pwd` varchar(80) NOT NULL default '',
  `attributesID` int(10) unsigned default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `UI_icfUser_name` (`name`)
) ENGINE=InnoDB;

LOCK TABLES `icfUser` WRITE;
INSERT INTO `icfUser` VALUES (1,'admin','Administrador','admin',NULL);
UNLOCK TABLES;

CREATE TABLE `icfDatatype` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `datatype` varchar(80) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB;

LOCK TABLES `icfDatatype` WRITE;
INSERT INTO `icfDatatype` VALUES (27,'Text'),(28,'Memo'),(29,'HTML'),(30,'Integer'),(31,'Decimal'),(32,'Date'),(33,'Time'),(34,'User'),(35,'Boolean'),(36,'Email'),(37,'URL'),(38,'Media'),(39,'Image'),(40,'UploadImage'),(41,'UploadMedia');
UNLOCK TABLES;

CREATE TABLE `icfAction` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `action` varchar(80) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB;

LOCK TABLES `icfAction` WRITE;
INSERT INTO `icfAction` VALUES (23,'ADD_OBJECTS'),(24,'LIST_OBJECTS'),(25,'VIEW_OBJECTS'),(26,'EDIT_OBJECTS'),(27,'REMOVE_OBJECTS'),(28,'PUBLISH_OBJECTS'),(29,'ADD_FOLDERS'),(30,'LIST_FOLDERS'),(31,'VIEW_FOLDERS'),(32,'EDIT_FOLDERS'),(33,'REMOVE_FOLDERS');
UNLOCK TABLES;

CREATE TABLE `icfClass` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(80) NOT NULL default '',
  `shortDescription` varchar(255) default NULL,
  `longDescription` text,
  `className` varchar(255) default NULL,
  `descriptor` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB;


CREATE TABLE `icfClassAttribute` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `classID` int(10) unsigned NOT NULL default '0',
  `datatypeID` int(10) unsigned NOT NULL default '0',
  `name` varchar(80) NOT NULL default '',
  `title` varchar(80) NOT NULL default '',
  `helpText` text,
  `len` int(10) unsigned default NULL,
  `defaultValue` mediumtext,
  `isRequired` tinyint(1) NOT NULL default '0',
  `isSearchable` tinyint(1) NOT NULL default '0',
  `isTranslatable` tinyint(1) NOT NULL default '0',
  `position` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ClassAttributes_FKIndex1` (`datatypeID`),
  KEY `ClassAttributes_FKIndex2` (`classID`),
  CONSTRAINT `icfClassAttribute_ibfk_1` FOREIGN KEY (`datatypeID`) REFERENCES `icfDatatype` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfClassAttribute_ibfk_2` FOREIGN KEY (`classID`) REFERENCES `icfClass` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;


CREATE TABLE `icfClassRelation` (
  `ID` int(10) unsigned NOT NULL default '0',
  `parentID` int(10) unsigned NOT NULL default '0',
  `childID` int(10) unsigned NOT NULL default '0',
  `position` int(10) unsigned NOT NULL default '0',
  `cardinality` int(10) unsigned NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `helpText` text,
  `isRequired` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `Classes_has_Classes_FKIndex1` (`childID`),
  KEY `Classes_has_Classes_FKIndex2` (`parentID`),
  KEY `classRelations_IX_UNIQUE` (`parentID`,`childID`),
  CONSTRAINT `icfClassRelation_ibfk_1` FOREIGN KEY (`childID`) REFERENCES `icfClass` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfClassRelation_ibfk_2` FOREIGN KEY (`parentID`) REFERENCES `icfClass` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfClassRelation_ibfk_3` FOREIGN KEY (`childID`) REFERENCES `icfClass` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfClassRelation_ibfk_4` FOREIGN KEY (`parentID`) REFERENCES `icfClass` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;


CREATE TABLE `icfFolder` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `parentID` int(10) unsigned default NULL,
  `title` varchar(80) NOT NULL default '',
  `shortDescription` varchar(255) default NULL,
  `longDescription` text,
  `position` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `Folders_FKIndex1` (`parentID`),
  CONSTRAINT `icfFolder_ibfk_1` FOREIGN KEY (`parentID`) REFERENCES `icfFolder` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfFolder_ibfk_2` FOREIGN KEY (`parentID`) REFERENCES `icfFolder` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE `icfFolderClass` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `classID` int(10) unsigned NOT NULL default '0',
  `folderID` int(10) unsigned NOT NULL default '0',
  `position` int(10) unsigned NOT NULL default '0',
  `isDefault` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `Classes_has_Folders_FKIndex1` (`classID`),
  KEY `Classes_has_Folders_FKIndex2` (`folderID`),
  KEY `foldersClasses_IX_UNIQUE` (`classID`,`folderID`),
  CONSTRAINT `icfFolderClass_ibfk_1` FOREIGN KEY (`classID`) REFERENCES `icfClass` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfFolderClass_ibfk_2` FOREIGN KEY (`folderID`) REFERENCES `icfFolder` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB;


CREATE TABLE `icfLanguage` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(80) NOT NULL default '',
  `code` char(2) NOT NULL default '',
  `isMain` tinyint(1) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB;

LOCK TABLES `icfLanguage` WRITE;
INSERT INTO `icfLanguage` VALUES (5,'Spanish','SP',1);
UNLOCK TABLES;

CREATE TABLE `icfObject` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `updatedBy` int(10) unsigned default NULL,
  `createdBy` int(10) unsigned NOT NULL default '0',
  `classID` int(10) unsigned NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime default NULL,
  `startPublishing` datetime default NULL,
  `endPublishing` datetime default NULL,
  `hits` int(10) unsigned NOT NULL default '0',
  `fullTextIndex` mediumtext,
  `isPublished` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `Objects_FKIndex1` (`classID`),
  KEY `Objects_FKIndex3` (`createdBy`),
  KEY `Objects_FKIndex4` (`updatedBy`),
  CONSTRAINT `icfObject_ibfk_1` FOREIGN KEY (`classID`) REFERENCES `icfClass` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfObject_ibfk_2` FOREIGN KEY (`createdBy`) REFERENCES `icfUser` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfObject_ibfk_3` FOREIGN KEY (`updatedBy`) REFERENCES `icfUser` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;


CREATE TABLE `icfObjectAttribute` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `classAttributeID` int(10) unsigned NOT NULL default '0',
  `objectID` int(10) unsigned NOT NULL default '0',
  `languageID` int(10) unsigned NOT NULL default '0',
  `value` mediumtext,
  PRIMARY KEY  (`ID`),
  KEY `ObjectAttributes_FKIndex1` (`objectID`),
  KEY `ObjectAttributes_FKIndex2` (`languageID`),
  KEY `icfObjectAttributes_FKIndex3` (`classAttributeID`),
  CONSTRAINT `icfObjectAttribute_ibfk_1` FOREIGN KEY (`objectID`) REFERENCES `icfObject` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `icfObjectAttribute_ibfk_2` FOREIGN KEY (`classAttributeID`) REFERENCES `icfClassAttribute` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfObjectAttribute_ibfk_3` FOREIGN KEY (`languageID`) REFERENCES `icfLanguage` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;


CREATE TABLE `icfObjectFolder` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `objectID` int(10) unsigned NOT NULL default '0',
  `folderID` int(10) unsigned NOT NULL default '0',
  `position` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `objectLocations_IX_UNIQUE` (`objectID`,`folderID`),
  KEY `Objects_has_Folders_FKIndex1` (`objectID`),
  KEY `Objects_has_Folders_FKIndex2` (`folderID`),
  CONSTRAINT `icfObjectFolder_ibfk_1` FOREIGN KEY (`objectID`) REFERENCES `icfObject` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `icfObjectFolder_ibfk_2` FOREIGN KEY (`folderID`) REFERENCES `icfFolder` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB;


CREATE TABLE `icfObjectRelation` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `parentID` int(10) unsigned NOT NULL default '0',
  `childID` int(10) unsigned NOT NULL default '0',
  `position` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `Objects_has_Objects_FKIndex1` (`parentID`),
  KEY `Objects_has_Objects_FKIndex2` (`childID`),
  KEY `objectrelations_IX_UNIQUE` (`parentID`,`childID`),
  CONSTRAINT `icfObjectRelation_ibfk_1` FOREIGN KEY (`parentID`) REFERENCES `icfObject` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `icfObjectRelation_ibfk_2` FOREIGN KEY (`childID`) REFERENCES `icfObject` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE `icfRole` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `role` varchar(80) default NULL,
  `isDefault` tinyint(1) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB;

LOCK TABLES `icfRole` WRITE;
INSERT INTO `icfRole` VALUES (1,'admin',0);
UNLOCK TABLES;


CREATE TABLE `icfPermission` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `actionID` int(10) unsigned NOT NULL default '0',
  `folderClassID` int(10) unsigned NOT NULL default '0',
  `roleID` int(10) unsigned NOT NULL default '0',
  `includeChildren` tinyint(1) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `Permissions_FKIndex1` (`roleID`),
  KEY `Permissions_FKIndex3` (`actionID`),
  KEY `icfPermission_FKIndex3` (`folderClassID`),
  CONSTRAINT `icfPermission_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `icfRole` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfPermission_ibfk_2` FOREIGN KEY (`folderClassID`) REFERENCES `icfFolderClass` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfPermission_ibfk_3` FOREIGN KEY (`actionID`) REFERENCES `icfAction` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfPermission_ibfk_4` FOREIGN KEY (`roleID`) REFERENCES `icfRole` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfPermission_ibfk_5` FOREIGN KEY (`folderClassID`) REFERENCES `icfFolderClass` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfPermission_ibfk_6` FOREIGN KEY (`actionID`) REFERENCES `icfAction` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;


CREATE TABLE `icfRoleUser` (
  `ID` int(10) unsigned NOT NULL default '0',
  `userID` int(10) unsigned NOT NULL default '0',
  `roleID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `icfRoleUser_FKIndex1` (`roleID`),
  KEY `icfRoleUser_FKIndex2` (`userID`),
  CONSTRAINT `icfRoleUser_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `icfRole` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfRoleUser_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `icfUser` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfRoleUser_ibfk_3` FOREIGN KEY (`roleID`) REFERENCES `icfRole` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `icfRoleUser_ibfk_4` FOREIGN KEY (`userID`) REFERENCES `icfUser` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;


LOCK TABLES `icfRoleUser` WRITE;
INSERT INTO `icfRoleUser` VALUES (1,1,1);
UNLOCK TABLES;

