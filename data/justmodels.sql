-- MySQL dump 10.11
--
-- Host: localhost    Database: rbu
-- ------------------------------------------------------
-- Server version	5.0.51b

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
-- Table structure for table `auth_group`
--

DROP TABLE IF EXISTS `auth_group`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `auth_group`
--

LOCK TABLES `auth_group` WRITE;
/*!40000 ALTER TABLE `auth_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_group_permissions`
--

DROP TABLE IF EXISTS `auth_group_permissions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_group_permissions` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `group_id` (`group_id`,`permission_id`),
  KEY `permission_id_refs_id_5886d21f` (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `auth_group_permissions`
--

LOCK TABLES `auth_group_permissions` WRITE;
/*!40000 ALTER TABLE `auth_group_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_group_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_message`
--

DROP TABLE IF EXISTS `auth_message`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_message` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `auth_message_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `auth_message`
--

LOCK TABLES `auth_message` WRITE;
/*!40000 ALTER TABLE `auth_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_permission`
--

DROP TABLE IF EXISTS `auth_permission`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_permission` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `codename` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `content_type_id` (`content_type_id`,`codename`),
  KEY `auth_permission_content_type_id` (`content_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `auth_permission`
--

LOCK TABLES `auth_permission` WRITE;
/*!40000 ALTER TABLE `auth_permission` DISABLE KEYS */;
INSERT INTO `auth_permission` VALUES (1,'Can add permission',1,'add_permission'),(2,'Can change permission',1,'change_permission'),(3,'Can delete permission',1,'delete_permission'),(4,'Can add group',2,'add_group'),(5,'Can change group',2,'change_group'),(6,'Can delete group',2,'delete_group'),(7,'Can add user',3,'add_user'),(8,'Can change user',3,'change_user'),(9,'Can delete user',3,'delete_user'),(10,'Can add message',4,'add_message'),(11,'Can change message',4,'change_message'),(12,'Can delete message',4,'delete_message'),(13,'Can add content type',5,'add_contenttype'),(14,'Can change content type',5,'change_contenttype'),(15,'Can delete content type',5,'delete_contenttype'),(16,'Can add session',6,'add_session'),(17,'Can change session',6,'change_session'),(18,'Can delete session',6,'delete_session'),(19,'Can add nonce',7,'add_nonce'),(20,'Can change nonce',7,'change_nonce'),(21,'Can delete nonce',7,'delete_nonce'),(22,'Can add association',8,'add_association'),(23,'Can change association',8,'change_association'),(24,'Can delete association',8,'delete_association'),(25,'Can add profile',9,'add_profile'),(26,'Can change profile',9,'change_profile'),(27,'Can delete profile',9,'delete_profile'),(28,'Can add country',10,'add_country'),(29,'Can change country',10,'change_country'),(30,'Can delete country',10,'delete_country'),(31,'Can add state',11,'add_state'),(32,'Can change state',11,'change_state'),(33,'Can delete state',11,'delete_state'),(34,'Can add restaurant version',12,'add_restaurantversion'),(35,'Can change restaurant version',12,'change_restaurantversion'),(36,'Can delete restaurant version',12,'delete_restaurantversion'),(37,'Can add restaurant',13,'add_restaurant'),(38,'Can change restaurant',13,'change_restaurant'),(39,'Can delete restaurant',13,'delete_restaurant'),(40,'Can add location',14,'add_location'),(41,'Can change location',14,'change_location'),(42,'Can delete location',14,'delete_location'),(43,'Can add menu item',15,'add_menuitem'),(44,'Can change menu item',15,'change_menuitem'),(45,'Can delete menu item',15,'delete_menuitem'),(46,'Can add menuitem version',16,'add_menuitemversion'),(47,'Can change menuitem version',16,'change_menuitemversion'),(48,'Can delete menuitem version',16,'delete_menuitemversion'),(49,'Can add menu item image',17,'add_menuitemimage'),(50,'Can change menu item image',17,'change_menuitemimage'),(51,'Can delete menu item image',17,'delete_menuitemimage'),(52,'Can add restaurant rating',18,'add_restaurantrating'),(53,'Can change restaurant rating',18,'change_restaurantrating'),(54,'Can delete restaurant rating',18,'delete_restaurantrating'),(55,'Can add menuitem rating',19,'add_menuitemrating'),(56,'Can change menuitem rating',19,'change_menuitemrating'),(57,'Can delete menuitem rating',19,'delete_menuitemrating'),(58,'Can add restaurant note',20,'add_restaurantnote'),(59,'Can change restaurant note',20,'change_restaurantnote'),(60,'Can delete restaurant note',20,'delete_restaurantnote');
/*!40000 ALTER TABLE `auth_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_user`
--

DROP TABLE IF EXISTS `auth_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_user` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(75) NOT NULL,
  `password` varchar(128) NOT NULL,
  `is_staff` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_superuser` tinyint(1) NOT NULL,
  `last_login` datetime NOT NULL,
  `date_joined` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `auth_user`
--

LOCK TABLES `auth_user` WRITE;
/*!40000 ALTER TABLE `auth_user` DISABLE KEYS */;
INSERT INTO `auth_user` VALUES (1,'davedash','','','dd@davedash.com','sha1$fcd2e$9473ec155d67a8b198c3085aad086a4a44395f90',1,1,1,'2008-10-21 20:42:40','2008-10-21 20:42:40');
/*!40000 ALTER TABLE `auth_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_user_groups`
--

DROP TABLE IF EXISTS `auth_user_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_user_groups` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_id` (`user_id`,`group_id`),
  KEY `group_id_refs_id_f116770` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `auth_user_groups`
--

LOCK TABLES `auth_user_groups` WRITE;
/*!40000 ALTER TABLE `auth_user_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_user_user_permissions`
--

DROP TABLE IF EXISTS `auth_user_user_permissions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `auth_user_user_permissions` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_id` (`user_id`,`permission_id`),
  KEY `permission_id_refs_id_67e79cb` (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `auth_user_user_permissions`
--

LOCK TABLES `auth_user_user_permissions` WRITE;
/*!40000 ALTER TABLE `auth_user_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_user_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consumer_association`
--

DROP TABLE IF EXISTS `consumer_association`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `consumer_association` (
  `id` int(11) NOT NULL auto_increment,
  `server_url` longtext NOT NULL,
  `handle` varchar(255) NOT NULL,
  `secret` longtext NOT NULL,
  `issued` int(11) NOT NULL,
  `lifetime` int(11) NOT NULL,
  `assoc_type` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `consumer_association`
--

LOCK TABLES `consumer_association` WRITE;
/*!40000 ALTER TABLE `consumer_association` DISABLE KEYS */;
/*!40000 ALTER TABLE `consumer_association` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consumer_nonce`
--

DROP TABLE IF EXISTS `consumer_nonce`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `consumer_nonce` (
  `id` int(11) NOT NULL auto_increment,
  `server_url` varchar(128) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `salt` varchar(40) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `server_url` (`server_url`,`timestamp`,`salt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `consumer_nonce`
--

LOCK TABLES `consumer_nonce` WRITE;
/*!40000 ALTER TABLE `consumer_nonce` DISABLE KEYS */;
/*!40000 ALTER TABLE `consumer_nonce` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `country` (
  `iso` varchar(6) NOT NULL,
  `name` varchar(240) NOT NULL,
  `printable_name` varchar(240) NOT NULL,
  `iso3` varchar(9) NOT NULL,
  `numcode` int(11) default NULL,
  PRIMARY KEY  (`iso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `django_content_type`
--

DROP TABLE IF EXISTS `django_content_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `django_content_type` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `app_label` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `app_label` (`app_label`,`model`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `django_content_type`
--

LOCK TABLES `django_content_type` WRITE;
/*!40000 ALTER TABLE `django_content_type` DISABLE KEYS */;
INSERT INTO `django_content_type` VALUES (1,'permission','auth','permission'),(2,'group','auth','group'),(3,'user','auth','user'),(4,'message','auth','message'),(5,'content type','contenttypes','contenttype'),(6,'session','sessions','session'),(7,'nonce','consumer','nonce'),(8,'association','consumer','association'),(9,'profile','common','profile'),(10,'country','common','country'),(11,'state','common','state'),(12,'restaurant version','restaurant','restaurantversion'),(13,'restaurant','restaurant','restaurant'),(14,'location','restaurant','location'),(15,'menu item','restaurant','menuitem'),(16,'menuitem version','restaurant','menuitemversion'),(17,'menu item image','restaurant','menuitemimage'),(18,'restaurant rating','restaurant','restaurantrating'),(19,'menuitem rating','restaurant','menuitemrating'),(20,'restaurant note','restaurant','restaurantnote');
/*!40000 ALTER TABLE `django_content_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `django_session`
--

DROP TABLE IF EXISTS `django_session`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `django_session` (
  `session_key` varchar(40) NOT NULL,
  `session_data` longtext NOT NULL,
  `expire_date` datetime NOT NULL,
  PRIMARY KEY  (`session_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `django_session`
--

LOCK TABLES `django_session` WRITE;
/*!40000 ALTER TABLE `django_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `django_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `location` (
  `id` int(11) NOT NULL auto_increment,
  `restaurant_id` int(11) NOT NULL,
  `data_source` varchar(96) NOT NULL,
  `data_source_key` varchar(765) NOT NULL,
  `name` varchar(765) NOT NULL,
  `stripped_title` varchar(765) NOT NULL,
  `address` varchar(765) NOT NULL,
  `city` varchar(384) NOT NULL,
  `state` varchar(48) NOT NULL,
  `zip` varchar(30) NOT NULL,
  `country_id` varchar(6) NOT NULL,
  `latitude` double default NULL,
  `longitude` double default NULL,
  `phone` varchar(48) NOT NULL,
  `approved` int(11) default NULL,
  `updated_at` datetime default NULL,
  `created_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `data_source` (`data_source`,`data_source_key`),
  KEY `location_restaurant_id` (`restaurant_id`),
  KEY `location_country_id` (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_item`
--

DROP TABLE IF EXISTS `menu_item`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `menu_item` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(765) NOT NULL,
  `url` varchar(765) NOT NULL,
  `version_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `approved` int(11) default NULL,
  `average_rating` double default NULL,
  `num_ratings` int(11) default NULL,
  `updated_at` datetime default NULL,
  `created_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `menu_item_version_id` (`version_id`),
  KEY `menu_item_restaurant_id` (`restaurant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `menu_item`
--

LOCK TABLES `menu_item` WRITE;
/*!40000 ALTER TABLE `menu_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_item_image`
--

DROP TABLE IF EXISTS `menu_item_image`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `menu_item_image` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `menu_item_id` int(11) NOT NULL,
  `data` longtext NOT NULL,
  `md5sum` varchar(96) NOT NULL,
  `height` int(11) default NULL,
  `width` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `menu_item_image_user_id` (`user_id`),
  KEY `menu_item_image_menu_item_id` (`menu_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `menu_item_image`
--

LOCK TABLES `menu_item_image` WRITE;
/*!40000 ALTER TABLE `menu_item_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_item_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menuitem_rating`
--

DROP TABLE IF EXISTS `menuitem_rating`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `menuitem_rating` (
  `id` int(11) NOT NULL auto_increment,
  `menu_item_id` int(11) default NULL,
  `user_id` int(11) default NULL,
  `value` int(11) default NULL,
  `created_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `menuitem_rating_menu_item_id` (`menu_item_id`),
  KEY `menuitem_rating_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `menuitem_rating`
--

LOCK TABLES `menuitem_rating` WRITE;
/*!40000 ALTER TABLE `menuitem_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `menuitem_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menuitem_version`
--

DROP TABLE IF EXISTS `menuitem_version`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `menuitem_version` (
  `id` int(11) NOT NULL auto_increment,
  `description` longtext NOT NULL,
  `html_description` longtext NOT NULL,
  `location_id` int(11) default NULL,
  `menuitem_id` int(11) NOT NULL,
  `user_id` int(11) default NULL,
  `price` varchar(48) NOT NULL,
  `created_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `menuitem_version_location_id` (`location_id`),
  KEY `menuitem_version_menuitem_id` (`menuitem_id`),
  KEY `menuitem_version_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `menuitem_version`
--

LOCK TABLES `menuitem_version` WRITE;
/*!40000 ALTER TABLE `menuitem_version` DISABLE KEYS */;
/*!40000 ALTER TABLE `menuitem_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `profile` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL,
  `email` varchar(384) NOT NULL,
  `openid` tinyint(1) default NULL,
  `preferences` longtext NOT NULL,
  `about_text` longtext NOT NULL,
  `updated_at` datetime default NULL,
  `created_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant`
--

DROP TABLE IF EXISTS `restaurant`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `restaurant` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(765) NOT NULL,
  `stripped_title` varchar(384) NOT NULL,
  `approved` int(11) default NULL,
  `average_rating` double default NULL,
  `num_ratings` int(11) default NULL,
  `version_id` int(11) NOT NULL,
  `updated_at` datetime default NULL,
  `created_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `restaurant_version_id` (`version_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `restaurant`
--

LOCK TABLES `restaurant` WRITE;
/*!40000 ALTER TABLE `restaurant` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_note`
--

DROP TABLE IF EXISTS `restaurant_note`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `restaurant_note` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `note` longtext NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `location_id` int(11) default NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `html_note` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `restaurant_note_user_id` (`user_id`),
  KEY `restaurant_note_restaurant_id` (`restaurant_id`),
  KEY `restaurant_note_location_id` (`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `restaurant_note`
--

LOCK TABLES `restaurant_note` WRITE;
/*!40000 ALTER TABLE `restaurant_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_rating`
--

DROP TABLE IF EXISTS `restaurant_rating`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `restaurant_rating` (
  `id` int(11) NOT NULL auto_increment,
  `restaurant_id` int(11) NOT NULL,
  `value` int(11) default NULL,
  `location_id` int(11) default NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `restaurant_rating_restaurant_id` (`restaurant_id`),
  KEY `restaurant_rating_location_id` (`location_id`),
  KEY `restaurant_rating_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `restaurant_rating`
--

LOCK TABLES `restaurant_rating` WRITE;
/*!40000 ALTER TABLE `restaurant_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurant_version`
--

DROP TABLE IF EXISTS `restaurant_version`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `restaurant_version` (
  `id` int(11) NOT NULL auto_increment,
  `chain` int(11) default NULL,
  `description` longtext NOT NULL,
  `url` varchar(765) NOT NULL,
  `created_at` datetime default NULL,
  `restaurant_id` int(11) default NULL,
  `user_id` int(11) default NULL,
  `html_description` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `restaurant_version_restaurant_id` (`restaurant_id`),
  KEY `restaurant_version_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `restaurant_version`
--

LOCK TABLES `restaurant_version` WRITE;
/*!40000 ALTER TABLE `restaurant_version` DISABLE KEYS */;
/*!40000 ALTER TABLE `restaurant_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `state`
--

DROP TABLE IF EXISTS `state`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `state` (
  `usps` varchar(6) NOT NULL,
  `name` varchar(240) NOT NULL,
  PRIMARY KEY  (`usps`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `state`
--

LOCK TABLES `state` WRITE;
/*!40000 ALTER TABLE `state` DISABLE KEYS */;
/*!40000 ALTER TABLE `state` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-10-22  3:43:33
