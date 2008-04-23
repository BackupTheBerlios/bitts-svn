-- MySQL dump 10.10
--
-- Host: localhost    Database: bitts
-- ------------------------------------------------------
-- Server version	5.0.26

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
  `activities_amount` decimal(3,2) NOT NULL default '0.00',
  `activities_travel_distance` int(11) NOT NULL default '0',
  `activities_expenses` decimal(4,2) NOT NULL default '0.00',
  `activities_ticket_number` varchar(16) NOT NULL default '',
  `activities_comment` varchar(64) NOT NULL default '',
  `timesheets_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`activities_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (1,'2007-02-15',2,'8.00',129,'2.50','','Geen commentaar',3),(2,'2007-02-16',2,'7.50',129,'0.00','','Weer geen commentaar',3),(3,'2008-04-23',3,'3.00',121,'2.95','','Avondje met BitTS gespeeld',5);
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

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
  `customers_id` int(11) NOT NULL auto_increment,
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
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (4,'Beukhof Techniek','Beukhof Techniek','Afdeling inkoop','Buurtspoor 26','3994VK','Houten','Nederland','inkoop@beukhof.eu','+31 (0)30 6990910',''),(1,'BART it B.V.','BART it B.V.','t.a.v. Crediteurenadministratie','Postbus 17','2660AA','Bergschenhoek','Nederland','info@bart-it.nl','+31 (0)10 5030030','+31 (0)10 5030031');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `employees_id` int(11) NOT NULL default '0',
  `employees_login` varchar(16) NOT NULL default '',
  `employees_fullname` varchar(64) NOT NULL default '',
  `employees_password` varchar(40) NOT NULL default '',
  `employees_is_user` tinyint(1) NOT NULL default '0',
  `employees_is_analyst` tinyint(1) NOT NULL default '0',
  `employees_is_administrator` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`employees_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (10,'e.maris','Ellen Maris','',1,0,0),(2,'d.schutterop','Daniel Schutterop','',1,0,0),(9,'e.lips','Elise Lips','',1,1,0),(998,'c.vanmaanen','Carla van Maanen','',1,1,0),(999,'b.vink','Bart Vink','',1,0,0),(7,'e.beukhof','Erwin Beukhof','',1,1,1),(4,'g.malipaard','Gerard Malipaard','',1,0,0),(3,'m.roovers','Matthijs Roovers','',1,0,0),(1,'m.gijtenbeek','Marcel Gijtenbeek','',1,1,1),(11,'s.vandervelden','Stefan van der Velden','',1,0,0),(5,'b.koelstra','Bouke Koelstra','',1,1,0),(12,'a.compaan','Auke Compaan','',1,0,0);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees_roles`
--

LOCK TABLES `employees_roles` WRITE;
/*!40000 ALTER TABLE `employees_roles` DISABLE KEYS */;
INSERT INTO `employees_roles` VALUES (1,'2006-10-01','2007-12-31',1,1),(2,'2006-10-01','2007-10-31',2,7),(3,'2007-05-01','2007-12-31',3,10),(4,'2007-04-01','2007-04-30',3,9),(5,'2007-01-01','2007-03-31',3,2),(6,'2007-10-01','2008-12-31',4,7);
/*!40000 ALTER TABLE `employees_roles` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `languages` VALUES (1,'Nederlands','nl','icon.gif','dutch'),(2,'English','en','icon.gif','english');
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
  `customers_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`projects_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'Inrichting kantoor','Inrichting kantoorruimten in het pand aan de Leeuwenhoekweg 18a','Dirk-Jan vd Las','Wortel 13','2006-10-01','2007-12-31',9999,1),(2,'BitTS','Ontwikkeling timesheet applicatie','Marcel','Tijdschrijven','2007-10-01','2008-12-31',9999,2);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Projectleider','Regelneef en aanspreekpunt',0,1),(2,'Elektra installatie','Spelen met elektriciteit',0,1),(3,'Schilderen','Spelen met kleur',0,1),(4,'Ontwikkelen','Software ontwikkeling',0,2);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('fpp8bi829jrh8eub667li2qqj4',1197384184,'language|N;languages_id|N;'),('p87jsh7mj08r7h3m2p629h0cc6',1197385122,'language|N;languages_id|N;'),('qqfhn4dm6qs8neoo0bmnk9f4u4',1197385334,'language|N;languages_id|N;'),('0s555fp4pjr2j5lqne7f9kell0',1197385438,'language|N;languages_id|N;'),('6rphsf7gfv2mdvg1r28vnrlb40',1197385579,'language|N;languages_id|N;'),('87mne7ov3vb55oot5bsrrjt642',1197385693,'language|N;languages_id|N;'),('dd71r5b03eqif9r4eifsrab865',1197385883,'language|N;languages_id|N;'),('4f65ur4r9hbghr8qrgb8lliuv4',1197385984,'language|N;languages_id|N;'),('mcmbnbc9s77iruje12c95ru7d0',1197386110,'language|N;languages_id|N;'),('bfqds1d14olmq78maspno92uc0',1197386292,'language|N;languages_id|N;'),('089u8h54lne90nmcvgp7rungu1',1197386394,'language|N;languages_id|N;'),('k17rlg57fes6dcstssfvoa71d7',1197386439,'language|s:5:\"dutch\";languages_id|s:1:\"1\";'),('maid6vsrv9ptggfqk87raqp4g6',1197386590,'language|s:5:\"dutch\";languages_id|s:1:\"1\";'),('ohlvlon2f1j2lb7b8mmc9a7es1',1197386672,'language|s:5:\"dutch\";languages_id|s:1:\"1\";'),('uhfrcqljumbu8vl8volfhfmuk0',1197386984,'language|s:5:\"dutch\";languages_id|s:1:\"1\";'),('uqnrj2fs7fqd1npr9ojqcanlb0',1197387161,'language|s:5:\"dutch\";languages_id|s:1:\"1\";'),('3fv52j3fur2a9lbjrsogl6htv1',1197387391,'language|s:5:\"dutch\";languages_id|s:1:\"1\";'),('joa9tohf3dfa6hh33j2mof0kh5',1197387428,'language|s:5:\"dutch\";languages_id|s:1:\"1\";'),('htiq32b3ukl133dg5a7qc6u8d7',1197387612,'language|s:5:\"dutch\";languages_id|s:1:\"1\";'),('6kkdmf4euv8ilnu1sjgad7pir2',1197679068,'language|s:5:\"dutch\";languages_id|s:1:\"1\";'),('5gb7bq3bk6tnto00610u566051',1197679138,'language|s:5:\"dutch\";languages_id|s:1:\"1\";');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tariffs`
--

DROP TABLE IF EXISTS `tariffs`;
CREATE TABLE `tariffs` (
  `tariffs_id` int(11) NOT NULL auto_increment,
  `tariffs_amount` decimal(4,2) NOT NULL default '0.00',
  `units_id` int(11) NOT NULL default '0',
  `employees_roles_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`tariffs_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tariffs`
--

LOCK TABLES `tariffs` WRITE;
/*!40000 ALTER TABLE `tariffs` DISABLE KEYS */;
INSERT INTO `tariffs` VALUES (1,'25.00',6,2),(2,'75.00',5,2),(3,'0.00',4,6);
/*!40000 ALTER TABLE `tariffs` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timesheets`
--

LOCK TABLES `timesheets` WRITE;
/*!40000 ALTER TABLE `timesheets` DISABLE KEYS */;
INSERT INTO `timesheets` VALUES (2,'2007-03-01','2007-03-31',0,6),(3,'2007-02-01','2007-02-28',0,6),(4,'2007-01-01','2007-01-31',0,6),(5,'2008-04-01','2008-04-30',0,7);
/*!40000 ALTER TABLE `timesheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `units_id` int(11) NOT NULL auto_increment,
  `units_name` varchar(64) NOT NULL default '',
  `units_description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`units_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'uur (zon- & feestdagen 00u-00u)','Uren zon- & feestdagen'),(2,'uur (zaterdag 00u-00u)','Uren zaterdag'),(3,'uur (nacht 21u-06u)','Uren nacht (21:00u - 06:00u)'),(4,'uur (avond 18u-21u)','Uren avond (18:00u - 21:00u)'),(5,'uur (dag 06u-18u)','Uren overdag (06:00u - 18:00u)'),(6,'voorrijkosten','Voorrijkosten');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-04-23 23:15:54
