/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50150
Source Host           : localhost:3306
Source Database       : owh

Target Server Type    : MYSQL
Target Server Version : 50150
File Encoding         : 65001

Date: 2013-10-18 19:30:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `championships`
-- ----------------------------
DROP TABLE IF EXISTS `championships`;
CREATE TABLE `championships` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) NOT NULL,
  `ID_Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `ID_Status` (`ID_Status`),
  CONSTRAINT `championships_ibfk_1` FOREIGN KEY (`ID_Status`) REFERENCES `statuses` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of championships
-- ----------------------------
INSERT INTO `championships` VALUES ('1', '2012-13 Belgisch kampioenschap', '1');
INSERT INTO `championships` VALUES ('2', '2013 Belgische beker', '1');

-- ----------------------------
-- Table structure for `fields`
-- ----------------------------
DROP TABLE IF EXISTS `fields`;
CREATE TABLE `fields` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) NOT NULL,
  `ID_Status` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID_Status` (`ID_Status`),
  CONSTRAINT `fields_ibfk_1` FOREIGN KEY (`ID_Status`) REFERENCES `statuses` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fields
-- ----------------------------
INSERT INTO `fields` VALUES ('1', 'Field A', '1');
INSERT INTO `fields` VALUES ('2', 'Field B', '1');
INSERT INTO `fields` VALUES ('3', 'Field C', '1');

-- ----------------------------
-- Table structure for `games`
-- ----------------------------
DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Tournement` int(11) NOT NULL,
  `Time` time NOT NULL,
  `ID_TeamWhite` int(11) NOT NULL,
  `ID_TeamBlack` int(11) NOT NULL,
  `ScoreWhite` int(11) DEFAULT NULL,
  `ScoreBlack` int(11) DEFAULT NULL,
  `ID_Field` int(11) NOT NULL,
  `ID_TeamReferee1` int(11) NOT NULL,
  `ID_PlayerReferee1` int(11) DEFAULT NULL,
  `ID_TeamReferee2` int(11) NOT NULL,
  `ID_PlayerReferee2` int(11) DEFAULT NULL,
  `ID_TeamRefereeHead` int(11) NOT NULL,
  `ID_PlayerRefereeHead` int(11) DEFAULT NULL,
  `ID_Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `ID_TeamWhite` (`ID_TeamWhite`),
  KEY `ID_TeamBlack` (`ID_TeamBlack`),
  KEY `ID_Field` (`ID_Field`),
  KEY `ID_PlayerReferee1` (`ID_PlayerReferee1`),
  KEY `ID_PlayerReferee2` (`ID_PlayerReferee2`),
  KEY `ID_PlayerRefereeHead` (`ID_PlayerRefereeHead`),
  KEY `ID_Status` (`ID_Status`),
  KEY `ID_Tournement` (`ID_Tournement`),
  KEY `ID_TeamReferee1` (`ID_TeamReferee1`),
  KEY `ID_TeamReferee2` (`ID_TeamReferee2`),
  KEY `ID_TeamRefereeHead` (`ID_TeamRefereeHead`),
  CONSTRAINT `games_ibfk_1` FOREIGN KEY (`ID_TeamWhite`) REFERENCES `teams` (`ID`),
  CONSTRAINT `games_ibfk_10` FOREIGN KEY (`ID_TeamReferee2`) REFERENCES `teams` (`ID`),
  CONSTRAINT `games_ibfk_11` FOREIGN KEY (`ID_TeamRefereeHead`) REFERENCES `teams` (`ID`),
  CONSTRAINT `games_ibfk_2` FOREIGN KEY (`ID_TeamBlack`) REFERENCES `teams` (`ID`),
  CONSTRAINT `games_ibfk_3` FOREIGN KEY (`ID_Field`) REFERENCES `fields` (`ID`),
  CONSTRAINT `games_ibfk_4` FOREIGN KEY (`ID_PlayerReferee1`) REFERENCES `players` (`ID`),
  CONSTRAINT `games_ibfk_5` FOREIGN KEY (`ID_PlayerReferee2`) REFERENCES `players` (`ID`),
  CONSTRAINT `games_ibfk_6` FOREIGN KEY (`ID_PlayerRefereeHead`) REFERENCES `players` (`ID`),
  CONSTRAINT `games_ibfk_7` FOREIGN KEY (`ID_Status`) REFERENCES `statuses` (`ID`),
  CONSTRAINT `games_ibfk_8` FOREIGN KEY (`ID_Tournement`) REFERENCES `tournements` (`ID`),
  CONSTRAINT `games_ibfk_9` FOREIGN KEY (`ID_TeamReferee1`) REFERENCES `teams` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of games
-- ----------------------------
INSERT INTO `games` VALUES ('1', '2', '11:45:00', '7', '3', '4', '4', '1', '1', '3', '6', null, '2', null, '1');
INSERT INTO `games` VALUES ('3', '1', '11:15:00', '7', '7', '5', '5', '1', '1', '4', '3', null, '7', null, '1');

-- ----------------------------
-- Table structure for `permissions`
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('1', 'Administrator');
INSERT INTO `permissions` VALUES ('2', 'Teamuser');

-- ----------------------------
-- Table structure for `players`
-- ----------------------------
DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Team` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Player` tinyint(4) NOT NULL,
  `Referee` tinyint(4) NOT NULL,
  `Coach` tinyint(4) NOT NULL,
  `Captain` tinyint(4) NOT NULL,
  `ID_Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `ID_Status` (`ID_Status`),
  KEY `ID_Team` (`ID_Team`),
  CONSTRAINT `players_ibfk_1` FOREIGN KEY (`ID_Status`) REFERENCES `statuses` (`ID`),
  CONSTRAINT `players_ibfk_2` FOREIGN KEY (`ID_Team`) REFERENCES `teams` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of players
-- ----------------------------
INSERT INTO `players` VALUES ('3', '1', 'kris', 'krisleen@telenet.be', '1', '1', '1', '0', '1');
INSERT INTO `players` VALUES ('4', '1', 'jeroen', 'jeroen@?', '1', '1', '0', '1', '1');
INSERT INTO `players` VALUES ('5', '1', 'steven', 'steven@?', '1', '1', '0', '0', '1');

-- ----------------------------
-- Table structure for `registrationplayers`
-- ----------------------------
DROP TABLE IF EXISTS `registrationplayers`;
CREATE TABLE `registrationplayers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Registration` int(11) NOT NULL,
  `ID_Player` int(11) NOT NULL,
  `ID_Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `ID_Registration` (`ID_Registration`),
  KEY `ID_Player` (`ID_Player`),
  KEY `ID_Status` (`ID_Status`),
  CONSTRAINT `registrationplayers_ibfk_1` FOREIGN KEY (`ID_Registration`) REFERENCES `registrations` (`ID`),
  CONSTRAINT `registrationplayers_ibfk_2` FOREIGN KEY (`ID_Player`) REFERENCES `players` (`ID`),
  CONSTRAINT `registrationplayers_ibfk_3` FOREIGN KEY (`ID_Status`) REFERENCES `statuses` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of registrationplayers
-- ----------------------------
INSERT INTO `registrationplayers` VALUES ('16', '12', '4', '2');
INSERT INTO `registrationplayers` VALUES ('17', '12', '3', '2');
INSERT INTO `registrationplayers` VALUES ('18', '7', '3', '1');
INSERT INTO `registrationplayers` VALUES ('19', '7', '5', '1');
INSERT INTO `registrationplayers` VALUES ('20', '12', '4', '2');
INSERT INTO `registrationplayers` VALUES ('21', '12', '3', '2');
INSERT INTO `registrationplayers` VALUES ('22', '12', '4', '2');
INSERT INTO `registrationplayers` VALUES ('23', '12', '3', '2');
INSERT INTO `registrationplayers` VALUES ('24', '12', '4', '2');
INSERT INTO `registrationplayers` VALUES ('25', '12', '3', '2');
INSERT INTO `registrationplayers` VALUES ('26', '12', '4', '2');
INSERT INTO `registrationplayers` VALUES ('27', '12', '3', '2');
INSERT INTO `registrationplayers` VALUES ('28', '12', '3', '2');
INSERT INTO `registrationplayers` VALUES ('29', '12', '5', '2');
INSERT INTO `registrationplayers` VALUES ('30', '12', '3', '1');
INSERT INTO `registrationplayers` VALUES ('31', '12', '5', '1');

-- ----------------------------
-- Table structure for `registrations`
-- ----------------------------
DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Tournement` int(11) NOT NULL,
  `ID_Team` int(11) NOT NULL,
  `ID_Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_Tournement` (`ID_Tournement`,`ID_Team`),
  KEY `ID_Team` (`ID_Team`),
  KEY `ID_Status` (`ID_Status`),
  CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`ID_Tournement`) REFERENCES `tournements` (`ID`),
  CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`ID_Team`) REFERENCES `teams` (`ID`),
  CONSTRAINT `registrations_ibfk_3` FOREIGN KEY (`ID_Status`) REFERENCES `statuses` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of registrations
-- ----------------------------
INSERT INTO `registrations` VALUES ('1', '1', '3', '1');
INSERT INTO `registrations` VALUES ('7', '1', '1', '1');
INSERT INTO `registrations` VALUES ('10', '1', '2', '1');
INSERT INTO `registrations` VALUES ('12', '2', '1', '1');

-- ----------------------------
-- Table structure for `statuses`
-- ----------------------------
DROP TABLE IF EXISTS `statuses`;
CREATE TABLE `statuses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of statuses
-- ----------------------------
INSERT INTO `statuses` VALUES ('1', 'Active');
INSERT INTO `statuses` VALUES ('2', 'Deleted');

-- ----------------------------
-- Table structure for `teams`
-- ----------------------------
DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) NOT NULL,
  `ID_Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `ID_Status` (`ID_Status`),
  CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`ID_Status`) REFERENCES `statuses` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of teams
-- ----------------------------
INSERT INTO `teams` VALUES ('1', 'mantis', '1');
INSERT INTO `teams` VALUES ('2', 'Genk', '1');
INSERT INTO `teams` VALUES ('3', 'BUWH', '1');
INSERT INTO `teams` VALUES ('4', 'CVD', '1');
INSERT INTO `teams` VALUES ('6', 'EPO', '1');
INSERT INTO `teams` VALUES ('7', 'Bilzen', '1');
INSERT INTO `teams` VALUES ('8', 'Zuunse karpers', '1');

-- ----------------------------
-- Table structure for `teamusers`
-- ----------------------------
DROP TABLE IF EXISTS `teamusers`;
CREATE TABLE `teamusers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Team` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `ID_Permission` int(11) NOT NULL DEFAULT '2',
  `ID_Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `ID_Team` (`ID_Team`),
  KEY `ID_Status` (`ID_Status`) USING BTREE,
  KEY `ID_Permission` (`ID_Permission`),
  CONSTRAINT `teamusers_ibfk_1` FOREIGN KEY (`ID_Team`) REFERENCES `teams` (`ID`),
  CONSTRAINT `teamusers_ibfk_2` FOREIGN KEY (`ID_Status`) REFERENCES `statuses` (`ID`),
  CONSTRAINT `teamusers_ibfk_3` FOREIGN KEY (`ID_Permission`) REFERENCES `permissions` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of teamusers
-- ----------------------------
INSERT INTO `teamusers` VALUES ('1', '1', 'kris.planckaert.admin@telenet.be', '3bfd258844524c16d1544cd18a25b287', '1', '1');
INSERT INTO `teamusers` VALUES ('2', '1', 'kris.planckaert@telenet.be', '3bfd258844524c16d1544cd18a25b287', '2', '1');
INSERT INTO `teamusers` VALUES ('4', '2', 'massimo@pastrani.be', '6423361b0413cb5a2c91f9404b64ecd4', '2', '1');

-- ----------------------------
-- Table structure for `tournements`
-- ----------------------------
DROP TABLE IF EXISTS `tournements`;
CREATE TABLE `tournements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Championship` int(11) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `ID_Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `ID_Status` (`ID_Status`),
  KEY `ID_Championship` (`ID_Championship`),
  CONSTRAINT `tournements_ibfk_1` FOREIGN KEY (`ID_Status`) REFERENCES `statuses` (`ID`),
  CONSTRAINT `tournements_ibfk_2` FOREIGN KEY (`ID_Championship`) REFERENCES `championships` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tournements
-- ----------------------------
INSERT INTO `tournements` VALUES ('1', '1', '1ste speeldag 2012-13', '2012-10-14', '1');
INSERT INTO `tournements` VALUES ('2', '1', '2de speeldag 2012-13', '2012-11-18', '1');
