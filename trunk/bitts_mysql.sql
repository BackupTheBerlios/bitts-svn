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
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `activities` (
  `activities_id` int(10) unsigned NOT NULL auto_increment,
  `activities_date` date NOT NULL default '0000-00-00',
  `tariffs_id` int(10) unsigned NOT NULL default '0',
  `activities_amount` decimal(5,2) NOT NULL default '0.00',
  `activities_travel_distance` int(11) NOT NULL default '0',
  `activities_expenses` decimal(6,2) NOT NULL default '0.00',
  `activities_ticket_number` varchar(16) NOT NULL default '',
  `activities_comment` varchar(255) NOT NULL default '',
  `timesheets_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`activities_id`),
  KEY `tariffs_id` (`tariffs_id`),
  KEY `timesheets_id` (`timesheets_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8991 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `benefits`
--

DROP TABLE IF EXISTS `benefits`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `benefits` (
  `benefits_id` int(10) unsigned NOT NULL auto_increment,
  `benefits_start_date` date NOT NULL default '0000-00-00',
  `benefits_end_date` date NOT NULL default '0000-00-00',
  `benefits_credit` decimal(5,2) NOT NULL default '0.00',
  `benefits_granted` decimal(5,2) NOT NULL default '0.00',
  `benefits_comment` varchar(256) NOT NULL default '',
  `employees_id` int(10) unsigned NOT NULL default '0',
  `roles_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`benefits_id`),
  KEY `employees_id` (`employees_id`),
  KEY `roles_id` (`roles_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `business_units`
--

DROP TABLE IF EXISTS `business_units`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `business_units` (
  `business_units_id` int(10) unsigned NOT NULL auto_increment,
  `business_units_name` varchar(64) NOT NULL default '',
  `business_units_image` varchar(32) default NULL,
  `business_units_image_position` char(1) default '',
  PRIMARY KEY  (`business_units_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `categories` (
  `categories_id` int(10) unsigned NOT NULL auto_increment,
  `categories_name` varchar(64) NOT NULL,
  PRIMARY KEY  (`categories_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `configuration` (
  `configuration_id` int(10) unsigned NOT NULL auto_increment,
  `configuration_title` varchar(64) NOT NULL default '',
  `configuration_key` varchar(64) NOT NULL default '',
  `configuration_value` varchar(255) NOT NULL default '',
  `configuration_description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`configuration_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `configuration`
--

LOCK TABLES `configuration` WRITE;
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;
INSERT INTO `configuration` VALUES (1,'Company Name','COMPANY_NAME','BART it B.V.','The Name of the Company'),(3,'Company Banner','COMPANY_BANNER','logo_Bart-it_156x71.jpg','The Banner of the Company'),(4,'Default Language','DEFAULT_LANGUAGE','nl','Default Language'),(5,'Heading Image Width','HEADING_IMAGE_WIDTH','64','Heading Image Width'),(6,'Heading Image Height','HEADING_IMAGE_HEIGHT','64','Heading Image Height'),(7,'Minimum Hours Per Day','MINIMUM_HOURS_PER_DAY','8','Minimum amount of hours that have to be administered per day'),(8,'Benefits Leave Role','BENEFITS_LEAVE_ROLE','8','The role that represents Leave under category Benefits');
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `customers` (
  `customers_id` int(10) unsigned NOT NULL default '0',
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
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `employees` (
  `employees_id` int(10) unsigned NOT NULL default '0',
  `employees_login` varchar(16) NOT NULL default '',
  `employees_fullname` varchar(64) NOT NULL default '',
  `employees_password` char(41) NOT NULL default '',
  `profiles_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`employees_id`),
  KEY `profiles_id` (`profiles_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `employees_roles`
--

DROP TABLE IF EXISTS `employees_roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `employees_roles` (
  `employees_roles_id` int(10) unsigned NOT NULL auto_increment,
  `employees_roles_start_date` date NOT NULL default '0000-00-00',
  `employees_roles_end_date` date NOT NULL default '0000-00-00',
  `roles_id` int(10) unsigned NOT NULL default '0',
  `employees_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`employees_roles_id`),
  KEY `employees_id` (`employees_id`),
  KEY `roles_id` (`roles_id`)
) ENGINE=MyISAM AUTO_INCREMENT=598 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `languages` (
  `languages_id` int(10) unsigned NOT NULL auto_increment,
  `languages_name` varchar(32) NOT NULL default '',
  `languages_code` char(2) NOT NULL default '',
  `languages_image` varchar(32) default NULL,
  `languages_directory` varchar(32) default NULL,
  PRIMARY KEY  (`languages_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'Nederlands','nl','icon.gif','dutch');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `profiles` (
  `profiles_id` int(10) unsigned NOT NULL auto_increment,
  `profiles_name` varchar(32) NOT NULL default '',
  `profiles_rights_login` tinyint(1) NOT NULL default '0',
  `profiles_rights_projectlisting` tinyint(1) NOT NULL default '0',
  `profiles_rights_timeregistration` tinyint(1) NOT NULL default '0',
  `profiles_rights_analysis` tinyint(1) NOT NULL default '0',
  `profiles_rights_administration` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`profiles_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES (5,'Beheerder',1,1,1,1,1),(4,'Rapporteur',1,1,1,1,0),(3,'Medewerker (tijdelijk dv)',1,0,1,0,0),(2,'Medewerker (vast dv)',1,1,1,0,0),(1,'Geen (uit dienst)',0,0,0,0,0);
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `projects` (
  `projects_id` int(10) unsigned NOT NULL auto_increment,
  `projects_name` varchar(64) NOT NULL default '',
  `projects_description` varchar(255) NOT NULL default '',
  `projects_customers_contact_name` varchar(64) NOT NULL default '',
  `projects_customers_reference` varchar(64) NOT NULL default '',
  `projects_start_date` date NOT NULL default '0000-00-00',
  `projects_end_date` date NOT NULL default '0000-00-00',
  `projects_calculated_hours` int(11) NOT NULL default '0',
  `projects_calculated_hours_period` char(1) NOT NULL default 'E',
  `business_units_id` int(10) unsigned NOT NULL default '0',
  `customers_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`projects_id`),
  KEY `business_units_id` (`business_units_id`),
  KEY `customers_id` (`customers_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `roles` (
  `roles_id` int(10) unsigned NOT NULL auto_increment,
  `roles_name` varchar(64) NOT NULL default '',
  `roles_description` varchar(255) NOT NULL default '',
  `roles_mandatory_ticket_entry` tinyint(1) NOT NULL default '0',
  `projects_id` int(10) unsigned NOT NULL default '0',
  `categories_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`roles_id`),
  KEY `categories_id` (`categories_id`),
  KEY `projects_id` (`projects_id`)
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sessions` (
  `sessions_key` varchar(32) NOT NULL default '',
  `sessions_expiry` int(10) unsigned NOT NULL default '0',
  `sessions_value` text NOT NULL,
  PRIMARY KEY  (`sessions_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `tariffs`
--

DROP TABLE IF EXISTS `tariffs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tariffs` (
  `tariffs_id` int(10) unsigned NOT NULL auto_increment,
  `tariffs_amount` decimal(5,2) NOT NULL default '0.00',
  `tariffs_start_date` date NOT NULL default '0000-00-00',
  `tariffs_end_date` date NOT NULL default '0000-00-00',
  `units_id` int(10) unsigned NOT NULL default '0',
  `employees_roles_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tariffs_id`),
  KEY `employees_roles_id` (`employees_roles_id`),
  KEY `units_id` (`units_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2320 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `timesheets`
--

DROP TABLE IF EXISTS `timesheets`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `timesheets` (
  `timesheets_id` int(10) unsigned NOT NULL auto_increment,
  `timesheets_start_date` date NOT NULL default '0000-00-00',
  `timesheets_end_date` date NOT NULL default '0000-00-00',
  `timesheets_locked` tinyint(1) NOT NULL default '0',
  `employees_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`timesheets_id`),
  KEY `employees_id` (`employees_id`)
) ENGINE=MyISAM AUTO_INCREMENT=282 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `units` (
  `units_id` int(10) unsigned NOT NULL auto_increment,
  `units_name` varchar(64) NOT NULL default '',
  `units_description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`units_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_benefits`
--

DROP TABLE IF EXISTS `v_benefits`;
/*!50001 DROP VIEW IF EXISTS `v_benefits`*/;
/*!50001 CREATE TABLE `v_benefits` (
  `employees_id` int(10) unsigned,
  `benefits_start_date` date,
  `benefits_end_date` date,
  `roles_id` int(10) unsigned,
  `benefits_credit` decimal(5,2),
  `benefits_granted` decimal(5,2),
  `benefits_comment` varchar(256),
  `benefits_used` decimal(27,2)
) */;

--
-- Temporary table structure for view `v_projects_total`
--

DROP TABLE IF EXISTS `v_projects_total`;
/*!50001 DROP VIEW IF EXISTS `v_projects_total`*/;
/*!50001 CREATE TABLE `v_projects_total` (
  `projects_start_date` date,
  `projects_end_date` date,
  `projects_name` varchar(64),
  `categories_name` varchar(64),
  `roles_name` varchar(64),
  `employees_roles_start_date` date,
  `employees_roles_end_date` date,
  `employees_fullname` varchar(64),
  `units_name` varchar(64),
  `tariffs_amount` decimal(5,2),
  `tariffs_start_date` date,
  `tariffs_end_date` date,
  `units_id` int(10) unsigned,
  `employees_roles_id` int(10) unsigned
) */;

--
-- Final view structure for view `v_benefits`
--

/*!50001 DROP TABLE `v_benefits`*/;
/*!50001 DROP VIEW IF EXISTS `v_benefits`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`192.168.0.%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_benefits` AS select `benefits`.`employees_id` AS `employees_id`,`benefits`.`benefits_id` AS `benefits_id`,`benefits`.`benefits_start_date` AS `benefits_start_date`,`benefits`.`benefits_end_date` AS `benefits_end_date`,`benefits`.`roles_id` AS `roles_id`,`benefits`.`benefits_credit` AS `benefits_credit`,`benefits`.`benefits_granted` AS `benefits_granted`,`benefits`.`benefits_comment` AS `benefits_comment`,ifnull(sum(`activities`.`activities_amount`),0.00) AS `benefits_used` from (`benefits` left join (((`activities` join `timesheets`) join `tariffs`) join `employees_roles`) on(((`benefits`.`employees_id` = `timesheets`.`employees_id`) and (`timesheets`.`timesheets_id` = `activities`.`timesheets_id`) and (`activities`.`tariffs_id` = `tariffs`.`tariffs_id`) and (`tariffs`.`employees_roles_id` = `employees_roles`.`employees_roles_id`) and (`benefits`.`roles_id` = `employees_roles`.`roles_id`) and (`activities`.`activities_date` >= `benefits`.`benefits_start_date`) and (`activities`.`activities_date` <= `benefits`.`benefits_end_date`)))) group by `benefits`.`employees_id`,`benefits`.`benefits_id`,`benefits`.`benefits_start_date`,`benefits`.`benefits_end_date` */;

--
-- Final view structure for view `v_projects_total`
--

/*!50001 DROP TABLE `v_projects_total`*/;
/*!50001 DROP VIEW IF EXISTS `v_projects_total`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`192.168.0.%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_projects_total` AS select `projects`.`projects_start_date` AS `projects_start_date`,`projects`.`projects_end_date` AS `projects_end_date`,`projects`.`projects_name` AS `projects_name`,`categories`.`categories_name` AS `categories_name`,`roles`.`roles_name` AS `roles_name`,`employees_roles`.`employees_roles_start_date` AS `employees_roles_start_date`,`employees_roles`.`employees_roles_end_date` AS `employees_roles_end_date`,`employees`.`employees_fullname` AS `employees_fullname`,`units`.`units_name` AS `units_name`,`tariffs`.`tariffs_amount` AS `tariffs_amount`,`tariffs`.`tariffs_start_date` AS `tariffs_start_date`,`tariffs`.`tariffs_end_date` AS `tariffs_end_date`,`units`.`units_id` AS `units_id`,`employees_roles`.`employees_roles_id` AS `employees_roles_id` from ((`projects` join (((`categories` join `roles`) join `employees`) join `employees_roles`) on(((`projects`.`projects_id` = `roles`.`projects_id`) and (`categories`.`categories_id` = `roles`.`categories_id`) and (`roles`.`roles_id` = `employees_roles`.`roles_id`) and (`employees`.`employees_id` = `employees_roles`.`employees_id`)))) left join (`tariffs` join `units`) on(((`employees_roles`.`employees_roles_id` = `tariffs`.`employees_roles_id`) and (`units`.`units_id` = `tariffs`.`units_id`)))) */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;