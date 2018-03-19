-- MySQL dump 10.13  Distrib 5.6.35, for FreeBSD10.3 (amd64)
--
-- Host: 127.0.0.1    Database: ds
-- ------------------------------------------------------
-- Server version	8.0.0-dmr-log

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
-- Table structure for table `ctrl_projects_tasks`
--

DROP TABLE IF EXISTS `ctrl_projects_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ctrl_projects_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ctrl_projects_tasks`
--

LOCK TABLES `ctrl_projects_tasks` WRITE;
/*!40000 ALTER TABLE `ctrl_projects_tasks` DISABLE KEYS */;
INSERT INTO `ctrl_projects_tasks` VALUES (1,0,'Интеграция с платёжной системой'),(2,1,'Интеграция с API банка-эквайера'),(3,1,'Графический интерфейс в клиентской части сайта'),(4,1,'Контрольный интерфейс в административной части сайта'),(5,0,'Интеграция со службой доставки'),(6,5,'Интеграция с API логистической компании'),(7,5,'Скрипты Cron синхронизации справочников'),(8,5,'Управляющий интерфейс в административной части сайта');
/*!40000 ALTER TABLE `ctrl_projects_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ctrl_timeranges`
--

DROP TABLE IF EXISTS `ctrl_timeranges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ctrl_timeranges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `task_id` int(11) NOT NULL DEFAULT '0',
  `datefrom` datetime DEFAULT NULL,
  `dateto` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`user_id`),
  KEY `task_id` (`task_id`),
  KEY `date` (`datefrom`,`dateto`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ctrl_timeranges`
--

LOCK TABLES `ctrl_timeranges` WRITE;
/*!40000 ALTER TABLE `ctrl_timeranges` DISABLE KEYS */;
INSERT INTO `ctrl_timeranges` VALUES (1,1,1,'2018-03-16 10:01:00','2018-03-16 16:00:00'),(2,1,4,'2018-03-16 16:15:00','2018-03-16 18:00:00'),(3,1,8,'2018-03-17 09:30:00','2018-03-17 12:05:00'),(4,2,2,'2018-03-16 09:30:00','2018-03-16 13:02:00'),(5,2,5,'2018-03-16 14:00:00','2018-03-16 18:00:00'),(6,2,5,'2018-03-17 09:30:00','2018-03-17 12:14:00'),(7,3,3,'2018-03-16 09:01:00','2018-03-16 10:00:00'),(8,3,4,'2018-03-16 10:10:00','2018-03-16 11:06:00'),(9,3,8,'2018-03-16 11:10:00','2018-03-16 12:30:00'),(10,4,6,'2018-03-16 10:00:00','2018-03-16 16:00:00'),(11,4,7,'2018-03-16 16:05:00','2018-03-16 18:00:00'),(12,4,7,'2018-03-17 09:30:00','2018-03-17 12:00:00'),(13,4,8,'2018-03-16 12:10:00','2018-03-16 12:15:00');
/*!40000 ALTER TABLE `ctrl_timeranges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ctrl_users`
--

DROP TABLE IF EXISTS `ctrl_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ctrl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `family` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `second_name` varchar(255) NOT NULL DEFAULT '',
  `workposition_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `workposition_id` (`workposition_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ctrl_users`
--

LOCK TABLES `ctrl_users` WRITE;
/*!40000 ALTER TABLE `ctrl_users` DISABLE KEYS */;
INSERT INTO `ctrl_users` VALUES (1,'Иванов','Иван','Иванович',1),(2,'Петров','Игорь','Владимирович',2),(3,'Сидоров','Николай','Андреевич',2),(4,'Васильева','Светлана','Витальевна',3);
/*!40000 ALTER TABLE `ctrl_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ctrl_workpositions`
--

DROP TABLE IF EXISTS `ctrl_workpositions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ctrl_workpositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ctrl_workpositions`
--

LOCK TABLES `ctrl_workpositions` WRITE;
/*!40000 ALTER TABLE `ctrl_workpositions` DISABLE KEYS */;
INSERT INTO `ctrl_workpositions` VALUES (1,'Web-дизайнер'),(2,'PHP-программист'),(3,'Графический дизайнер');
/*!40000 ALTER TABLE `ctrl_workpositions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-19 10:13:55
