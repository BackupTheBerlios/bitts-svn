-- MySQL dump 10.11
--
-- Host: localhost    Database: bitts
-- ------------------------------------------------------
-- Server version	5.0.32-Debian_7etch6-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
CREATE TABLE `activities` (
  `activities_id` int(11) NOT NULL auto_increment,
  `activities_date` date NOT NULL default '0000-00-00',
  `tariffs_id` int(11) NOT NULL default '0',
  `activities_amount` decimal(5,2) NOT NULL default '0.00',
  `activities_travel_distance` int(11) NOT NULL default '0',
  `activities_expenses` decimal(6,2) NOT NULL default '0.00',
  `activities_ticket_number` varchar(16) NOT NULL default '',
  `activities_comment` varchar(64) NOT NULL default '',
  `timesheets_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`activities_id`)
) ENGINE=MyISAM AUTO_INCREMENT=237 DEFAULT CHARSET=latin1;

--
-- Table structure for table `business_units`
--

DROP TABLE IF EXISTS `business_units`;
CREATE TABLE `business_units` (
  `business_units_id` int(11) NOT NULL auto_increment,
  `business_units_name` varchar(64) NOT NULL default '',
  `business_units_image` varchar(32) default NULL,
  PRIMARY KEY  (`business_units_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
CREATE TABLE `configuration` (
  `configuration_id` int(11) NOT NULL auto_increment,
  `configuration_title` varchar(64) NOT NULL default '',
  `configuration_key` varchar(64) NOT NULL default '',
  `configuration_value` varchar(255) NOT NULL default '',
  `configuration_description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`configuration_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configuration`
--

LOCK TABLES `configuration` WRITE;
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;
INSERT INTO `configuration` VALUES (1,'Company Name','COMPANY_NAME','BART it B.V.','The Name of the Company'),(3,'Company Banner','COMPANY_BANNER','logo_Bart-it_156x71.jpg','The Banner of the Company');
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `customers_id` int(11) NOT NULL default '0',
  `customers_name` varchar(64) NOT NULL default '',
  `customers_billing_name1` varchar(64) NOT NULL default '',
  `customers_billing_name2` varchar(64) NOT NULL default '',
  `customers_billing_address` varchar(64) NOT NULL default '',
  `customers_billing_postcode` varchar(8) NOT NULL default '',
  `customers_billing_city` varchar(64) NOT NULL default '',
  `customers_billing_country` varchar(64) NOT NULL default '',
  `customers_billing_email_address` varchar(64) NOT NULL default '',
  `customers_billing_phone` varchar(32) NOT NULL default '',
  `customers_billing_fax` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`customers_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `employees_id` int(11) NOT NULL default '0',
  `employees_login` varchar(16) NOT NULL default '',
  `employees_fullname` varchar(64) NOT NULL default '',
  `employees_password` char(41) NOT NULL default '',
  `employees_is_user` tinyint(1) NOT NULL default '0',
  `employees_is_analyst` tinyint(1) NOT NULL default '0',
  `employees_is_administrator` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`employees_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Table structure for table `employees_roles`
--

DROP TABLE IF EXISTS `employees_roles`;
CREATE TABLE `employees_roles` (
  `employees_roles_id` int(11) NOT NULL auto_increment,
  `employees_roles_start_date` date NOT NULL default '0000-00-00',
  `employees_roles_end_date` date NOT NULL default '0000-00-00',
  `roles_id` int(11) NOT NULL default '0',
  `employees_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`employees_roles_id`)
) ENGINE=MyISAM AUTO_INCREMENT=270 DEFAULT CHARSET=latin1;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `languages_id` int(11) NOT NULL auto_increment,
  `languages_name` varchar(32) NOT NULL default '',
  `languages_code` char(2) NOT NULL default '',
  `languages_image` varchar(32) default NULL,
  `languages_directory` varchar(32) default NULL,
  PRIMARY KEY  (`languages_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'Nederlands','nl','icon.gif','dutch');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `projects_id` int(11) NOT NULL auto_increment,
  `projects_name` varchar(64) NOT NULL default '',
  `projects_description` varchar(255) NOT NULL default '',
  `projects_customers_contact_name` varchar(64) NOT NULL default '',
  `projects_customers_reference` varchar(64) NOT NULL default '',
  `projects_start_date` date NOT NULL default '0000-00-00',
  `projects_end_date` date NOT NULL default '0000-00-00',
  `projects_calculated_hours` int(11) NOT NULL default '0',
  `business_units_id` int(11) NOT NULL default '0',
  `customers_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`projects_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `roles_id` int(11) NOT NULL auto_increment,
  `roles_name` varchar(64) NOT NULL default '',
  `roles_description` varchar(255) NOT NULL default '',
  `roles_mandatory_ticket_entry` tinyint(1) NOT NULL default '0',
  `projects_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`roles_id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `sessions_key` varchar(32) NOT NULL default '',
  `sessions_expiry` int(10) unsigned NOT NULL default '0',
  `sessions_value` text NOT NULL,
  PRIMARY KEY  (`sessions_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tariffs`
--

DROP TABLE IF EXISTS `tariffs`;
CREATE TABLE `tariffs` (
  `tariffs_id` int(11) NOT NULL auto_increment,
  `tariffs_amount` decimal(5,2) NOT NULL default '0.00',
  `units_id` int(11) NOT NULL default '0',
  `employees_roles_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`tariffs_id`)
) ENGINE=MyISAM AUTO_INCREMENT=588 DEFAULT CHARSET=latin1;

--
-- Table structure for table `timesheets`
--

DROP TABLE IF EXISTS `timesheets`;
CREATE TABLE `timesheets` (
  `timesheets_id` int(11) NOT NULL auto_increment,
  `timesheets_start_date` date NOT NULL default '0000-00-00',
  `timesheets_end_date` date NOT NULL default '0000-00-00',
  `timesheets_locked` tinyint(1) NOT NULL default '0',
  `employees_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`timesheets_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `units_id` int(11) NOT NULL auto_increment,
  `units_name` varchar(64) NOT NULL default '',
  `units_description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`units_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-09-16 20:45:01
