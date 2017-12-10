-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: db_droidetime
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.28-MariaDB

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
-- Table structure for table `aaa_adms`
--

DROP TABLE IF EXISTS `aaa_adms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aaa_adms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) NOT NULL,
  `senha` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aaa_adms`
--

LOCK TABLES `aaa_adms` WRITE;
/*!40000 ALTER TABLE `aaa_adms` DISABLE KEYS */;
/*!40000 ALTER TABLE `aaa_adms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aaa_files_projetos`
--

DROP TABLE IF EXISTS `aaa_files_projetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aaa_files_projetos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(45) NOT NULL,
  `language` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `date_init` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `aaa_projetos_id` int(11) NOT NULL,
  `aaa_projetos_users_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`aaa_projetos_id`,`aaa_projetos_users_id`),
  KEY `fk_aaa_files_projetos_aaa_projetos1_idx` (`aaa_projetos_id`,`aaa_projetos_users_id`),
  CONSTRAINT `fk_aaa_files_projetos_aaa_projetos1` FOREIGN KEY (`aaa_projetos_id`, `aaa_projetos_users_id`) REFERENCES `aaa_projetos` (`id`, `users_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aaa_files_projetos`
--

LOCK TABLES `aaa_files_projetos` WRITE;
/*!40000 ALTER TABLE `aaa_files_projetos` DISABLE KEYS */;
INSERT INTO `aaa_files_projetos` VALUES (2,'index.php','php',0,'2017-12-10 17:37:54','0000-00-00 00:00:00',3,1),(3,'item teste mysql','sql',5,'2017-10-15 17:38:25','2017-12-10 17:55:12',3,1);
/*!40000 ALTER TABLE `aaa_files_projetos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aaa_projetos`
--

DROP TABLE IF EXISTS `aaa_projetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aaa_projetos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `time_total` int(11) NOT NULL,
  `date_init` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`users_id`),
  KEY `fk_aaa_projetos_aaa_users_idx` (`users_id`),
  CONSTRAINT `fk_aaa_projetos_aaa_users` FOREIGN KEY (`users_id`) REFERENCES `aaa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aaa_projetos`
--

LOCK TABLES `aaa_projetos` WRITE;
/*!40000 ALTER TABLE `aaa_projetos` DISABLE KEYS */;
INSERT INTO `aaa_projetos` VALUES (3,1,'Desenvolvimento DroideTime',5,'0000-00-00 00:00:00','2017-12-10 17:55:12');
/*!40000 ALTER TABLE `aaa_projetos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aaa_users`
--

DROP TABLE IF EXISTS `aaa_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aaa_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) DEFAULT NULL,
  `senha` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aaa_users`
--

LOCK TABLES `aaa_users` WRITE;
/*!40000 ALTER TABLE `aaa_users` DISABLE KEYS */;
INSERT INTO `aaa_users` VALUES (1,'uninove','$2y$13$0xpihE5mKJAt8SCbOzA8TO3RD31n431ApSjNZ7ir8ksczA5hPq53m');
/*!40000 ALTER TABLE `aaa_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-10 18:11:38
