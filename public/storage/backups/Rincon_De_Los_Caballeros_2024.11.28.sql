-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: novaDB
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asambleas`
--

DROP TABLE IF EXISTS `asambleas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asambleas` (
  `id_asamblea` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `lugar` varchar(255) NOT NULL,
  `ciudad` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `controles` int(11) NOT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `ordenDia` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ordenDia`)),
  `registro` tinyint(1) NOT NULL,
  `signature` tinyint(1) NOT NULL DEFAULT 0,
  `h_inicio` time DEFAULT NULL,
  `h_cierre` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_asamblea`),
  UNIQUE KEY `asambleas_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asambleas`
--

LOCK TABLES `asambleas` WRITE;
/*!40000 ALTER TABLE `asambleas` DISABLE KEYS */;
INSERT INTO `asambleas` VALUES (1,'Aqua Tower_2024.11.29','Aqua Tower','Un lugar bonito','Bucaramanga','2024-11-29','10:00:00',100,NULL,NULL,NULL,0,0,'15:51:00',NULL,'2024-11-29 01:50:58','2024-11-29 01:51:14'),(2,'Rincon_De_Los_Caballeros_2024.11.29','Rincon De Los Caballeros','Un lugar bonito','Bucaramanga','2024-11-29','10:00:00',200,NULL,NULL,NULL,0,0,NULL,NULL,'2024-11-29 02:41:04','2024-11-29 04:43:15'),(3,'Rincon_De_Los_Caballeros_2024.11.28','Rincon De Los Caballeros','Un lugar bonito','Bucaramanga','2024-11-28','10:00:00',200,'Asamblea Extraordinaria','Presencial',NULL,0,0,'18:45:00','21:30:00','2024-11-29 04:44:33','2024-11-29 23:43:20');
/*!40000 ALTER TABLE `asambleas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignacions`
--

DROP TABLE IF EXISTS `asignacions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignacions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `control_id` bigint(20) unsigned NOT NULL,
  `cc_asistente` bigint(20) unsigned DEFAULT NULL,
  `estado` bigint(20) unsigned NOT NULL DEFAULT 0,
  `sum_coef` decimal(8,6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asignacions_control_id_unique` (`control_id`),
  KEY `asignacions_cc_asistente_foreign` (`cc_asistente`),
  CONSTRAINT `asignacions_cc_asistente_foreign` FOREIGN KEY (`cc_asistente`) REFERENCES `personas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `asignacions_control_id_foreign` FOREIGN KEY (`control_id`) REFERENCES `controls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignacions`
--

LOCK TABLES `asignacions` WRITE;
/*!40000 ALTER TABLE `asignacions` DISABLE KEYS */;
/*!40000 ALTER TABLE `asignacions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('asamblea','O:19:\"App\\Models\\Asamblea\":30:{s:13:\"\0*\0connection\";s:7:\"mariadb\";s:8:\"\0*\0table\";s:9:\"asambleas\";s:13:\"\0*\0primaryKey\";s:11:\"id_asamblea\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:17:{s:11:\"id_asamblea\";s:1:\"3\";s:4:\"name\";s:35:\"Rincon_De_Los_Caballeros_2024.11.28\";s:6:\"folder\";s:24:\"Rincon De Los Caballeros\";s:5:\"lugar\";s:15:\"Un lugar bonito\";s:6:\"ciudad\";s:11:\"Bucaramanga\";s:5:\"fecha\";s:10:\"2024-11-28\";s:4:\"hora\";s:8:\"10:00:00\";s:9:\"controles\";s:3:\"200\";s:10:\"referencia\";s:23:\"Asamblea Extraordinaria\";s:4:\"tipo\";s:10:\"Presencial\";s:8:\"ordenDia\";N;s:8:\"registro\";i:0;s:9:\"signature\";b:0;s:8:\"h_inicio\";s:8:\"18:45:00\";s:8:\"h_cierre\";s:5:\"21:30\";s:10:\"created_at\";s:19:\"2024-11-28 23:44:33\";s:10:\"updated_at\";s:19:\"2024-11-29 18:43:20\";}s:11:\"\0*\0original\";a:17:{s:11:\"id_asamblea\";s:1:\"3\";s:4:\"name\";s:35:\"Rincon_De_Los_Caballeros_2024.11.28\";s:6:\"folder\";s:24:\"Rincon De Los Caballeros\";s:5:\"lugar\";s:15:\"Un lugar bonito\";s:6:\"ciudad\";s:11:\"Bucaramanga\";s:5:\"fecha\";s:10:\"2024-11-28\";s:4:\"hora\";s:8:\"10:00:00\";s:9:\"controles\";s:3:\"200\";s:10:\"referencia\";s:23:\"Asamblea Extraordinaria\";s:4:\"tipo\";s:10:\"Presencial\";s:8:\"ordenDia\";N;s:8:\"registro\";i:0;s:9:\"signature\";b:0;s:8:\"h_inicio\";s:8:\"18:45:00\";s:8:\"h_cierre\";s:5:\"21:30\";s:10:\"created_at\";s:19:\"2024-11-28 23:44:33\";s:10:\"updated_at\";s:19:\"2024-11-29 18:43:20\";}s:10:\"\0*\0changes\";a:1:{s:9:\"signature\";b:0;}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}',2048265800),('id_asamblea','i:3;',2048197473),('inRegistro','b:0;',2048197473),('predios_end','i:-1;',2048254469),('predios_init','i:3;',2048197548),('questionsPrefabCount','i:13;',2048197548),('quorum_end','s:8:\"0.000000\";',2048207462),('quorum_init','s:8:\"1.363650\";',2048197548);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `controls`
--

DROP TABLE IF EXISTS `controls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `controls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `state` bigint(20) unsigned NOT NULL DEFAULT 4,
  `cc_asistente` bigint(20) unsigned DEFAULT NULL,
  `sum_coef_can` decimal(8,6) NOT NULL,
  `predios_vote` int(11) NOT NULL,
  `sum_coef` decimal(8,6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `h_entrega` varchar(255) DEFAULT NULL,
  `h_recibe` varchar(255) DEFAULT NULL,
  `t_publico` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `controls_state_foreign` (`state`),
  KEY `controls_cc_asistente_foreign` (`cc_asistente`),
  CONSTRAINT `controls_cc_asistente_foreign` FOREIGN KEY (`cc_asistente`) REFERENCES `personas` (`id`),
  CONSTRAINT `controls_state_foreign` FOREIGN KEY (`state`) REFERENCES `states` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `controls`
--

LOCK TABLES `controls` WRITE;
/*!40000 ALTER TABLE `controls` DISABLE KEYS */;
INSERT INTO `controls` VALUES (1,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:45','21:31','1'),(2,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:59','21:31','1'),(3,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:58','21:31','1'),(4,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:57','21:31','1'),(5,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:57','21:31','1'),(6,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:57','21:31','1'),(7,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:56','21:31','1'),(8,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:16','21:31','1'),(9,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:56','21:31','1'),(10,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:40','21:31','0'),(11,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:56','21:31','1'),(12,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:01','21:31','1'),(13,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:02','21:31','0'),(14,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:02','21:31','0'),(15,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:01','21:31','1'),(16,1,NULL,1.363650,3,1.363650,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:52','21:31','1'),(17,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:06','21:31','1'),(18,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:06','21:31','1'),(19,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:05','21:31','1'),(20,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:05','21:31','0'),(21,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:04','21:31','1'),(22,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','18:55','21:31','1'),(23,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:01','21:31','1'),(24,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:03','21:31','1'),(25,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:04','21:31','1'),(26,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:07','21:31','1'),(27,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18',NULL,'21:31','1'),(28,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:04','21:31','0'),(29,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:05','21:31','0'),(30,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:08','21:31','0'),(31,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:05','21:31','1'),(32,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:05','21:31','0'),(33,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:04','21:31','1'),(34,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:04','21:31','1'),(35,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:07','21:31','1'),(36,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:07','21:31','0'),(37,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:10','21:31','1'),(38,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:09','21:31','0'),(39,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:08','21:31','0'),(40,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:10','21:31','1'),(41,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:08','21:31','1'),(42,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:09','21:31','0'),(43,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:09','21:31','1'),(44,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:13','21:31','1'),(45,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:10','21:31','1'),(46,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:10','21:31','0'),(47,1,NULL,1.363650,3,1.363650,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:11','21:31','1'),(48,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:14','21:31','1'),(49,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18',NULL,'21:31','1'),(50,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:13','21:31','0'),(51,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:10','21:31','1'),(52,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:14','21:31','1'),(53,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:12','21:31','1'),(54,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:11','21:31','1'),(55,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:15','21:31','1'),(56,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:15','21:31','1'),(57,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:14','21:31','1'),(58,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:18','21:31','1'),(59,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:16','21:31','1'),(60,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:22','21:31','1'),(61,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:11','21:31','1'),(62,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:17','21:31','1'),(63,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:17','21:31','1'),(64,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:17','21:31','0'),(65,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:15','21:31','1'),(66,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:18','21:31','1'),(67,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:19','21:31','0'),(68,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02','19:17','21:31','0'),(69,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:22','21:31','1'),(70,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:23','21:31','1'),(71,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 07:31:02',NULL,'21:31','0'),(72,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:33','2024-11-29 23:41:18','19:31','21:31','1'),(73,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:26','21:31','0'),(74,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:20','21:31','1'),(75,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:30','21:31','1'),(76,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:29','21:31','0'),(77,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:30','21:31','1'),(78,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:29','21:31','1'),(79,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:27','21:31','1'),(80,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:26','21:31','0'),(81,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:18','21:31','0'),(82,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:25','21:31','0'),(83,1,NULL,1.363650,3,1.363650,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:25','21:31','0'),(84,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18',NULL,'21:31','1'),(85,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:26','21:31','1'),(86,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:24','21:31','1'),(87,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:24','21:31','1'),(88,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:24','21:31','1'),(89,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:23','21:31','1'),(90,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:23','21:31','0'),(91,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:26','21:31','1'),(92,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:33','21:31','1'),(93,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:33','21:31','1'),(94,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 06:49:50','19:32',NULL,'0'),(95,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:32','21:31','1'),(96,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:36','21:31','1'),(97,1,NULL,1.363650,3,1.363650,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:36','21:31','1'),(98,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:36','21:31','1'),(99,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:38','21:31','0'),(100,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:39','21:31','1'),(101,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:28','21:31','1'),(102,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:27','21:31','1'),(103,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:34','21:31','1'),(104,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:35','21:31','1'),(105,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:34','21:31','1'),(106,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:33','21:31','1'),(107,1,NULL,0.909100,2,0.909100,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:34','21:31','0'),(108,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 06:49:43','19:32',NULL,'0'),(109,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:38','21:31','0'),(110,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:39','21:31','0'),(111,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:45','21:31','1'),(112,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:45','21:31','0'),(113,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:46','21:31','1'),(114,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:54','21:31','1'),(115,1,NULL,1.363650,3,1.363650,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:53','21:31','1'),(116,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 23:41:18','19:54','21:31','1'),(117,1,NULL,1.818200,4,1.818200,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:59','21:31','0'),(118,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','19:59','21:31','0'),(119,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','20:30','21:31','0'),(120,1,NULL,0.454550,1,0.454550,'2024-11-29 04:44:34','2024-11-29 07:31:02','20:42','21:31','0'),(121,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(122,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(123,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(124,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(125,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(126,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(127,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(128,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(129,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(130,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(131,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(132,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(133,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(134,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(135,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(136,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(137,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(138,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(139,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(140,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(141,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(142,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(143,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(144,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(145,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(146,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(147,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(148,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(149,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(150,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(151,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(152,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(153,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(154,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(155,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(156,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(157,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(158,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(159,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(160,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(161,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(162,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(163,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(164,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(165,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(166,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(167,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(168,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(169,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(170,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(171,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(172,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(173,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(174,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(175,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(176,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(177,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(178,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(179,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(180,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(181,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(182,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(183,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(184,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(185,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(186,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(187,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(188,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(189,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(190,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(191,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(192,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(193,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(194,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(195,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(196,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(197,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(198,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(199,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0'),(200,4,NULL,0.000000,0,0.000000,'2024-11-29 04:44:34','2024-11-29 04:44:34',NULL,NULL,'0');
/*!40000 ALTER TABLE `controls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `generals`
--

DROP TABLE IF EXISTS `generals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `generals` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `generals`
--

LOCK TABLES `generals` WRITE;
/*!40000 ALTER TABLE `generals` DISABLE KEYS */;
INSERT INTO `generals` VALUES ('themeId','5','2024-11-29 01:50:37','2024-11-29 01:50:37');
/*!40000 ALTER TABLE `generals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_05_10_141154_create_personas_table',1),(5,'2024_05_12_200203_create_states_table',1),(6,'2024_05_14_124518_create_controls_table',1),(7,'2024_06_06_145527_create_session_table',1),(8,'2024_06_06_180428_create_asambleas__table',1),(9,'2024_06_07_211004_create_predios_table',1),(10,'2024_06_12_201527_create_asignacions_table',1),(11,'2024_06_24_021421_create_permission_tables',1),(12,'2024_07_16_180218_create_question_types_table',1),(13,'2024_07_17_224550_create_questions_table',1),(14,'2024_07_24_035334_create_results_table',1),(15,'2024_08_07_191303_create_predios_personas_table',1),(16,'2024_08_22_214330_create_signatures_table',1),(17,'2024_09_18_031334_create_votes_table',1),(18,'2024_09_19_204136_create_generals_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',2),(2,'App\\Models\\User',4),(2,'App\\Models\\User',10),(2,'App\\Models\\User',11),(2,'App\\Models\\User',12),(2,'App\\Models\\User',13),(2,'App\\Models\\User',14),(2,'App\\Models\\User',15),(2,'App\\Models\\User',16),(2,'App\\Models\\User',17),(3,'App\\Models\\User',3),(3,'App\\Models\\User',5),(3,'App\\Models\\User',6),(3,'App\\Models\\User',7),(3,'App\\Models\\User',8),(3,'App\\Models\\User',9);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'users.create','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(2,'users.index','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(3,'users.update','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(4,'users.delete','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(5,'asignaciones.create','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(6,'asignaciones.index','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(7,'asignaciones.update','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(8,'asignaciones.delete','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(9,'predios.create','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(10,'predios.index','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(11,'predios.update','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(12,'predios.delete','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(13,'personas.create','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(14,'personas.index','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(15,'personas.update','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(16,'personas.delete','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(17,'asambleas.create','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(18,'asambleas.index','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(19,'asambleas.update','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(20,'asambleas.delete','web','2024-11-29 01:50:37','2024-11-29 01:50:37');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_id` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `registered` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personas_user_id_foreign` (`user_id`),
  CONSTRAINT `personas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personas`
--

LOCK TABLES `personas` WRITE;
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `predios`
--

DROP TABLE IF EXISTS `predios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `predios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `descriptor1` varchar(255) NOT NULL,
  `numeral1` varchar(255) NOT NULL,
  `descriptor2` varchar(255) NOT NULL,
  `numeral2` varchar(255) NOT NULL,
  `coeficiente` double NOT NULL,
  `vota` tinyint(1) NOT NULL,
  `control_id` bigint(20) unsigned DEFAULT NULL,
  `quorum_start` tinyint(1) NOT NULL DEFAULT 0,
  `quorum_end` tinyint(1) NOT NULL DEFAULT 0,
  `cc_apoderado` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `predios_cc_apoderado_foreign` (`cc_apoderado`),
  KEY `predios_control_id_foreign` (`control_id`),
  CONSTRAINT `predios_cc_apoderado_foreign` FOREIGN KEY (`cc_apoderado`) REFERENCES `personas` (`id`),
  CONSTRAINT `predios_control_id_foreign` FOREIGN KEY (`control_id`) REFERENCES `controls` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `predios`
--

LOCK TABLES `predios` WRITE;
/*!40000 ALTER TABLE `predios` DISABLE KEYS */;
INSERT INTO `predios` VALUES (1,'Torre','Don Andres','Apartamento','101',0.45455,1,84,0,1,NULL,NULL,'2024-11-29 07:31:02'),(2,'Torre','Don Andres','Apartamento','102',0.45455,1,88,0,1,NULL,NULL,'2024-11-29 07:31:02'),(3,'Torre','Don Andres','Apartamento','103',0.45455,1,76,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(4,'Torre','Don Andres','Apartamento','104',0.45455,1,83,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(5,'Torre','Don Andres','Apartamento','201',0.45455,1,79,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(6,'Torre','Don Andres','Apartamento','202',0.45455,1,101,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(7,'Torre','Don Andres','Apartamento','203',0.45455,1,114,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(8,'Torre','Don Andres','Apartamento','204',0.45455,1,48,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(9,'Torre','Don Andres','Apartamento','301',0.45455,1,87,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(10,'Torre','Don Andres','Apartamento','302',0.45455,1,22,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(11,'Torre','Don Andres','Apartamento','303',0.45455,1,104,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(12,'Torre','Don Andres','Apartamento','304',0.45455,1,80,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(13,'Torre','Don Andres','Apartamento','401',0.45455,1,99,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(14,'Torre','Don Andres','Apartamento','402',0.45455,1,105,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(15,'Torre','Don Andres','Apartamento','403',0.45455,1,106,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(16,'Torre','Don Andres','Apartamento','404',0.45455,1,93,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(17,'Torre','Don Andres','Apartamento','501',0.45455,1,81,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(18,'Torre','Don Andres','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(19,'Torre','Don Andres','Apartamento','503',0.45455,1,63,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(20,'Torre','Don Andres','Apartamento','504',0.45455,1,81,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(21,'Torre','Don Camilo','Apartamento','101',0.45455,1,49,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(22,'Torre','Don Camilo','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(23,'Torre','Don Camilo','Apartamento','103',0.45455,1,112,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(24,'Torre','Don Camilo','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(25,'Torre','Don Camilo','Apartamento','201',0.45455,1,31,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(26,'Torre','Don Camilo','Apartamento','202',0.45455,1,4,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(27,'Torre','Don Camilo','Apartamento','203',0.45455,1,21,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(28,'Torre','Don Camilo','Apartamento','204',0.45455,1,15,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(29,'Torre','Don Camilo','Apartamento','301',0.45455,1,85,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(30,'Torre','Don Camilo','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(31,'Torre','Don Camilo','Apartamento','303',0.45455,1,23,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(32,'Torre','Don Camilo','Apartamento','304',0.45455,1,65,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(33,'Torre','Don Camilo','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(34,'Torre','Don Camilo','Apartamento','402',0.45455,1,19,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(35,'Torre','Don Camilo','Apartamento','403',0.45455,1,58,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(36,'Torre','Don Camilo','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(37,'Torre','Don Camilo','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(38,'Torre','Don Camilo','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(39,'Torre','Don Camilo','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(40,'Torre','Don Camilo','Apartamento','504',0.45455,1,103,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(41,'Torre','Don Carlos','Apartamento','101',0.45455,1,14,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(42,'Torre','Don Carlos','Apartamento','102',0.45455,1,37,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(43,'Torre','Don Carlos','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(44,'Torre','Don Carlos','Apartamento','104',0.45455,1,83,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(45,'Torre','Don Carlos','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(46,'Torre','Don Carlos','Apartamento','202',0.45455,1,111,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(47,'Torre','Don Carlos','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(48,'Torre','Don Carlos','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(49,'Torre','Don Carlos','Apartamento','301',0.45455,1,47,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(50,'Torre','Don Carlos','Apartamento','302',0.45455,1,47,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(51,'Torre','Don Carlos','Apartamento','303',0.45455,1,34,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(52,'Torre','Don Carlos','Apartamento','304',0.45455,1,50,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(53,'Torre','Don Carlos','Apartamento','401',0.45455,1,50,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(54,'Torre','Don Carlos','Apartamento','402',0.45455,1,104,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(55,'Torre','Don Carlos','Apartamento','403',0.45455,1,118,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(56,'Torre','Don Carlos','Apartamento','404',0.45455,1,47,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(57,'Torre','Don Carlos','Apartamento','501',0.45455,1,51,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(58,'Torre','Don Carlos','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(59,'Torre','Don Carlos','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(60,'Torre','Don Carlos','Apartamento','504',0.45455,1,51,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(61,'Torre','Don Felipe','Apartamento','101',0.45455,1,28,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(62,'Torre','Don Felipe','Apartamento','102',0.45455,1,36,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(63,'Torre','Don Felipe','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(64,'Torre','Don Felipe','Apartamento','104',0.45455,1,9,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(65,'Torre','Don Felipe','Apartamento','201',0.45455,1,13,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(66,'Torre','Don Felipe','Apartamento','202',0.45455,1,117,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(67,'Torre','Don Felipe','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(68,'Torre','Don Felipe','Apartamento','204',0.45455,1,75,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(69,'Torre','Don Felipe','Apartamento','301',0.45455,1,24,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(70,'Torre','Don Felipe','Apartamento','302',0.45455,1,60,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(71,'Torre','Don Felipe','Apartamento','303',0.45455,1,36,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(72,'Torre','Don Felipe','Apartamento','304',0.45455,1,52,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(73,'Torre','Don Felipe','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(74,'Torre','Don Felipe','Apartamento','402',0.45455,1,71,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(75,'Torre','Don Felipe','Apartamento','403',0.45455,1,53,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(76,'Torre','Don Felipe','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(77,'Torre','Don Felipe','Apartamento','501',0.45455,1,62,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(78,'Torre','Don Felipe','Apartamento','502',0.45455,1,43,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(79,'Torre','Don Felipe','Apartamento','503',0.45455,1,43,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(80,'Torre','Don Felipe','Apartamento','504',0.45455,1,115,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(81,'Torre','Don Fernando','Apartamento','101',0.45455,1,69,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(82,'Torre','Don Fernando','Apartamento','102',0.45455,1,55,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(83,'Torre','Don Fernando','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(84,'Torre','Don Fernando','Apartamento','104',0.45455,1,20,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(85,'Torre','Don Fernando','Apartamento','201',0.45455,1,74,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(86,'Torre','Don Fernando','Apartamento','202',0.45455,1,16,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(87,'Torre','Don Fernando','Apartamento','203',0.45455,1,83,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(88,'Torre','Don Fernando','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(89,'Torre','Don Fernando','Apartamento','301',0.45455,1,113,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(90,'Torre','Don Fernando','Apartamento','302',0.45455,1,7,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(91,'Torre','Don Fernando','Apartamento','303',0.45455,1,69,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(92,'Torre','Don Fernando','Apartamento','304',0.45455,1,68,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(93,'Torre','Don Fernando','Apartamento','401',0.45455,1,96,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(94,'Torre','Don Fernando','Apartamento','402',0.45455,1,98,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(95,'Torre','Don Fernando','Apartamento','403',0.45455,1,77,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(96,'Torre','Don Fernando','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:29','2024-11-29 04:44:29'),(97,'Torre','Don Fernando','Apartamento','501',0.45455,1,97,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(98,'Torre','Don Fernando','Apartamento','502',0.45455,1,97,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(99,'Torre','Don Fernando','Apartamento','503',0.45455,1,110,0,1,NULL,'2024-11-29 04:44:29','2024-11-29 07:31:02'),(100,'Torre','Don Fernando','Apartamento','504',0.45455,1,89,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(101,'Torre','Don Gabrriel','Apartamento','101',0.45455,1,72,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(102,'Torre','Don Gabrriel','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 05:16:21'),(103,'Torre','Don Gabrriel','Apartamento','103',0.45455,1,39,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(104,'Torre','Don Gabrriel','Apartamento','104',0.45455,1,38,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(105,'Torre','Don Gabrriel','Apartamento','201',0.45455,1,38,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(106,'Torre','Don Gabrriel','Apartamento','202',0.45455,1,8,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(107,'Torre','Don Gabrriel','Apartamento','203',0.45455,1,25,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(108,'Torre','Don Gabrriel','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(109,'Torre','Don Gabrriel','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(110,'Torre','Don Gabrriel','Apartamento','302',0.45455,1,78,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(111,'Torre','Don Gabrriel','Apartamento','303',0.45455,1,78,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(112,'Torre','Don Gabrriel','Apartamento','304',0.45455,1,3,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(113,'Torre','Don Gabrriel','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(114,'Torre','Don Gabrriel','Apartamento','402',0.45455,1,45,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(115,'Torre','Don Gabrriel','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(116,'Torre','Don Gabrriel','Apartamento','404',0.45455,1,107,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(117,'Torre','Don Gabrriel','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(118,'Torre','Don Gabrriel','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(119,'Torre','Don Gabrriel','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(120,'Torre','Don Gabrriel','Apartamento','504',0.45455,1,73,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(121,'Torre','Don Gonzalo','Apartamento','101',0.45455,1,46,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(122,'Torre','Don Gonzalo','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(123,'Torre','Don Gonzalo','Apartamento','103',0.45455,1,5,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(124,'Torre','Don Gonzalo','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(125,'Torre','Don Gonzalo','Apartamento','201',0.45455,1,90,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(126,'Torre','Don Gonzalo','Apartamento','202',0.45455,1,40,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(127,'Torre','Don Gonzalo','Apartamento','203',0.45455,1,27,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(128,'Torre','Don Gonzalo','Apartamento','204',0.45455,1,30,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(130,'Torre','Don Gonzalo','Apartamento','302',0.45455,1,95,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(132,'Torre','Don Gonzalo','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(133,'Torre','Don Gonzalo','Apartamento','401',0.45455,1,66,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(134,'Torre','Don Gonzalo','Apartamento','402',0.45455,1,16,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(135,'Torre','Don Gonzalo','Apartamento','403',0.45455,1,67,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(136,'Torre','Don Gonzalo','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(137,'Torre','Don Gonzalo','Apartamento','501',0.45455,1,16,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(138,'Torre','Don Gonzalo','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(139,'Torre','Don Gonzalo','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(140,'Torre','Don Gonzalo','Apartamento','504',0.45455,1,100,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(141,'Torre','Don Guillermo','Apartamento','101',0.45455,1,11,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(142,'Torre','Don Guillermo','Apartamento','102',0.45455,1,44,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(143,'Torre','Don Guillermo','Apartamento','103',0.45455,1,70,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(144,'Torre','Don Guillermo','Apartamento','104',0.45455,1,27,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(145,'Torre','Don Guillermo','Apartamento','201',0.45455,1,11,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(146,'Torre','Don Guillermo','Apartamento','202',0.45455,1,102,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(147,'Torre','Don Guillermo','Apartamento','203',0.45455,1,35,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(148,'Torre','Don Guillermo','Apartamento','204',0.45455,1,65,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(149,'Torre','Don Guillermo','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(150,'Torre','Don Guillermo','Apartamento','302',0.45455,1,30,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(151,'Torre','Don Guillermo','Apartamento','303',0.45455,1,86,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(152,'Torre','Don Guillermo','Apartamento','304',0.45455,1,5,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(153,'Torre','Don Guillermo','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(154,'Torre','Don Guillermo','Apartamento','402',0.45455,1,64,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(155,'Torre','Don Guillermo','Apartamento','403',0.45455,1,1,1,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(156,'Torre','Don Guillermo','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(157,'Torre','Don Guillermo','Apartamento','501',0.45455,1,12,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(158,'Torre','Don Guillermo','Apartamento','502',0.45455,1,12,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(159,'Torre','Don Guillermo','Apartamento','503',0.45455,1,29,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(160,'Torre','Don Guillermo','Apartamento','504',0.45455,1,119,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(161,'Torre','Don Jorge','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(162,'Torre','Don Jorge','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(163,'Torre','Don Jorge','Apartamento','103',0.45455,1,10,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(164,'Torre','Don Jorge','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(165,'Torre','Don Jorge','Apartamento','201',0.45455,1,10,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(166,'Torre','Don Jorge','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(167,'Torre','Don Jorge','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(168,'Torre','Don Jorge','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(169,'Torre','Don Jorge','Apartamento','301',0.45455,1,32,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(170,'Torre','Don Jorge','Apartamento','302',0.45455,1,3,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(171,'Torre','Don Jorge','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(172,'Torre','Don Jorge','Apartamento','304',0.45455,1,61,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(173,'Torre','Don Jorge','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(174,'Torre','Don Jorge','Apartamento','402',0.45455,1,91,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(175,'Torre','Don Jorge','Apartamento','403',0.45455,1,92,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(176,'Torre','Don Jorge','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(177,'Torre','Don Jorge','Apartamento','501',0.45455,1,56,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(178,'Torre','Don Jorge','Apartamento','502',0.45455,1,2,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(180,'Torre','Don Jorge','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(181,'Torre','Don Pedro','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(182,'Torre','Don Pedro','Apartamento','102',0.45455,1,54,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(183,'Torre','Don Pedro','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(184,'Torre','Don Pedro','Apartamento','104',0.45455,1,17,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(185,'Torre','Don Pedro','Apartamento','201',0.45455,1,18,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(186,'Torre','Don Pedro','Apartamento','202',0.45455,1,53,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(187,'Torre','Don Pedro','Apartamento','203',0.45455,1,107,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(188,'Torre','Don Pedro','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(189,'Torre','Don Pedro','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(190,'Torre','Don Pedro','Apartamento','302',0.45455,1,24,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(191,'Torre','Don Pedro','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(192,'Torre','Don Pedro','Apartamento','304',0.45455,1,13,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(193,'Torre','Don Pedro','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(194,'Torre','Don Pedro','Apartamento','402',0.45455,1,117,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(195,'Torre','Don Pedro','Apartamento','403',0.45455,1,117,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(196,'Torre','Don Pedro','Apartamento','404',0.45455,1,117,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(197,'Torre','Don Pedro','Apartamento','501',0.45455,1,26,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(198,'Torre','Don Pedro','Apartamento','502',0.45455,1,115,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(199,'Torre','Don Pedro','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(200,'Torre','Don Pedro','Apartamento','504',0.45455,1,115,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(201,'Torre','Don Vasco','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(202,'Torre','Don Vasco','Apartamento','102',0.45455,1,57,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(203,'Torre','Don Vasco','Apartamento','103',0.45455,1,6,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(204,'Torre','Don Vasco','Apartamento','104',0.45455,1,59,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(205,'Torre','Don Vasco','Apartamento','201',0.45455,1,109,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(206,'Torre','Don Vasco','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(207,'Torre','Don Vasco','Apartamento','203',0.45455,1,33,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(208,'Torre','Don Vasco','Apartamento','204',0.45455,1,82,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(209,'Torre','Don Vasco','Apartamento','301',0.45455,1,6,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(210,'Torre','Don Vasco','Apartamento','302',0.45455,1,97,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(211,'Torre','Don Vasco','Apartamento','303',0.45455,1,82,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(212,'Torre','Don Vasco','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(213,'Torre','Don Vasco','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(214,'Torre','Don Vasco','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(215,'Torre','Don Vasco','Apartamento','403',0.45455,1,120,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(216,'Torre','Don Vasco','Apartamento','404',0.45455,1,41,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(217,'Torre','Don Vasco','Apartamento','501',0.45455,1,42,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(218,'Torre','Don Vasco','Apartamento','502',0.45455,1,116,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02'),(219,'Torre','Don Vasco','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 04:44:30','2024-11-29 04:44:30'),(220,'Torre','Don Vasco','Apartamento','504',0.45455,1,57,0,1,NULL,'2024-11-29 04:44:30','2024-11-29 07:31:02');
/*!40000 ALTER TABLE `predios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `predios_personas`
--

DROP TABLE IF EXISTS `predios_personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `predios_personas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` bigint(20) unsigned NOT NULL,
  `predio_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `predios_personas_persona_id_foreign` (`persona_id`),
  KEY `predios_personas_predio_id_foreign` (`predio_id`),
  CONSTRAINT `predios_personas_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  CONSTRAINT `predios_personas_predio_id_foreign` FOREIGN KEY (`predio_id`) REFERENCES `predios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `predios_personas`
--

LOCK TABLES `predios_personas` WRITE;
/*!40000 ALTER TABLE `predios_personas` DISABLE KEYS */;
/*!40000 ALTER TABLE `predios_personas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question_types`
--

DROP TABLE IF EXISTS `question_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_types`
--

LOCK TABLES `question_types` WRITE;
/*!40000 ALTER TABLE `question_types` DISABLE KEYS */;
INSERT INTO `question_types` VALUES (1,'Quorum','2024-11-29 04:44:05','2024-11-29 04:44:05'),(2,'Seleccion','2024-11-29 04:44:05','2024-11-29 04:44:05'),(3,'Aprobacion','2024-11-29 04:44:05','2024-11-29 04:44:05'),(4,'SI/NO','2024-11-29 04:44:05','2024-11-29 04:44:05'),(5,'TD','2024-11-29 04:44:05','2024-11-29 04:44:05'),(6,'Plancha','2024-11-29 04:44:05','2024-11-29 04:44:05');
/*!40000 ALTER TABLE `question_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` bigint(20) unsigned NOT NULL,
  `title` longtext DEFAULT NULL,
  `optionA` varchar(255) DEFAULT NULL,
  `optionB` varchar(255) DEFAULT NULL,
  `optionC` varchar(255) DEFAULT NULL,
  `optionD` varchar(255) DEFAULT NULL,
  `optionE` varchar(255) DEFAULT NULL,
  `optionF` varchar(255) DEFAULT NULL,
  `prefab` tinyint(1) NOT NULL,
  `isValid` tinyint(1) NOT NULL DEFAULT 0,
  `quorum` double DEFAULT NULL,
  `predios` int(11) DEFAULT NULL,
  `seconds` int(11) NOT NULL DEFAULT 0,
  `resultTxt` varchar(255) DEFAULT NULL,
  `coefGraph` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_type_foreign` (`type`),
  CONSTRAINT `questions_type_foreign` FOREIGN KEY (`type`) REFERENCES `question_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,3,'Aprobacion de estados Financieros','','','','Aprobado','No Aprobado','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(2,3,'Aprobacion de orden del dia','','','','Aprobado','No Aprobado','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(3,3,'Aprobacion de Presupuestos','','','','Aprobado','No Aprobado','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(4,3,'Aprobacion del Acta','','','','Aprobado','No Aprobado','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(5,2,'Comite de Convivencia','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(6,2,'Consejo de Administracion','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(7,5,'Consentimiento de datos personales','Voto Publico','Voto Privado','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(8,2,'Eleccion de Presidente','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(9,2,'Eleccion de revisor fiscal','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(10,2,'Eleccion de secretario','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(11,1,'Verificación de Quorum','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(12,4,'Proposicion','SI','NO','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(13,2,'Prueba','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 04:44:05','2024-11-29 04:44:05'),(14,2,'PRUEBA','IGNACIO','JAVIER',NULL,NULL,NULL,NULL,0,0,1.36365,3,120,NULL,1,'2024-11-29 04:49:36','2024-11-29 19:39:13'),(15,2,'PRUEBA','IGNACIO','FERMIN',NULL,NULL,NULL,NULL,0,0,1.36365,3,120,NULL,1,'2024-11-29 04:53:18','2024-11-29 19:39:15'),(16,5,'CONSENTIMIENTO DE DATOS PERSONALES','VOTO PUBLICO','VOTO PRIVADO',NULL,NULL,NULL,NULL,0,1,65.90975,145,120,NULL,0,'2024-11-29 05:48:59','2024-11-29 05:48:59'),(18,4,'PARA EL DíA 28 DE NOVIEMBRE DE 2024, LAS ADMINISTRADORAS DEL CONJUNTO RINCóN DE LOS CABALLEROS CITARON A ASAMBLEA EXTRAORDINARIA, PARA DETERMINAR EL PAGO DE 110’000.000 MILLONES DE PESOS AL ABOGADO LITIGANTE CARLOS ARTURO AMAYA POR DEMANDA PRESENTADA A URVIVIENDAS SAS EN FECHA XXXXXXX DEL AñO XXX Y EN LA CUAL SE PERDIó Y FUIMOS CONDENADOS EN COSTAS PROCESALES. DECIDIMOS CANCELAR CONFORME A LO PROPUESTO DE PARTE DEL ABOGADO EN MENCIóN Y LOS REPRESENTANTES DE LAS TORRES XXXX Y XXXX ENTRE ELLAS LAS ADMINISTRADORAS Y ALGUNOS CONSEJEROS POR UNICO VALOR DE 110’000’000 DE PESOS CUBRIENDO AGENCIAS, COSTAS, HONORARIOS Y TODOS LOS CONCEPTOS QUE SE GENERARON EN EL PROCESO DE REFERENCIA EN DOS CONTADOS CADA UNO POR 55’000.0000 DE PESOS EN LAS FECHAS 16 DE DICIEMBRE DE 2024 Y 31 DE ENERO DE 2025. ','SI','NO',NULL,NULL,NULL,NULL,0,1,70.45525,155,120,'SE APRUEBA EL PAGO DE LA CUOTA EXTRAORDINARIA',0,'2024-11-29 07:20:48','2024-11-29 19:42:43');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) unsigned NOT NULL,
  `optionA` double DEFAULT NULL,
  `optionB` double DEFAULT NULL,
  `optionC` double DEFAULT NULL,
  `optionD` double DEFAULT NULL,
  `optionE` double DEFAULT NULL,
  `optionF` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `abstainted` double NOT NULL,
  `absent` double NOT NULL,
  `nule` double NOT NULL,
  `isCoef` tinyint(1) NOT NULL,
  `chartPath` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `results_question_id_foreign` (`question_id`),
  CONSTRAINT `results_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
INSERT INTO `results` VALUES (1,15,1,1,0,0,0,0,3,1,0,0,0,'2/nominalChart.png','2024-11-29 04:53:52','2024-11-29 04:53:54'),(2,15,0.45455,0.45455,0,0,0,0,1.36365,0.45455,0,0,1,'2/coefChart.png','2024-11-29 04:53:52','2024-11-29 04:53:55'),(3,18,137,5,0,0,0,0,155,9,0,4,0,'5/nominalChart.png','2024-11-29 07:26:44','2024-11-29 07:27:53'),(4,18,62.27335,2.27275,0,0,0,0,70.45525,4.09095,0,1.8182,1,'5/coefChart.png','2024-11-29 07:26:44','2024-11-29 07:27:54'),(9,16,107,32,0,0,0,0,155,9,0,7,0,'3/nominalChart.png','2024-11-29 23:42:04','2024-11-29 23:42:05'),(10,16,48.63685,14.5456,0,0,0,0,75.3643,9,0,3.18185,1,'3/coefChart.png','2024-11-29 23:42:04','2024-11-29 23:42:07');
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(2,2),(3,1),(4,1),(5,1),(5,2),(5,3),(6,1),(6,2),(6,3),(7,1),(7,2),(7,3),(8,1),(8,2),(8,3),(9,1),(10,1),(10,2),(10,3),(11,1),(11,2),(11,3),(12,1),(12,2),(13,1),(13,2),(14,1),(14,2),(14,3),(15,1),(15,2),(15,3),(16,1),(16,2),(17,1),(18,1),(18,2),(18,3),(19,1),(19,2),(20,1);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(2,'Lider','web','2024-11-29 01:50:37','2024-11-29 01:50:37'),(3,'Operario','web','2024-11-29 01:50:37','2024-11-29 01:50:37');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_asamblea` int(11) NOT NULL,
  `name_asamblea` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
INSERT INTO `session` VALUES (1,3,'Rincon De Los Caballeros','2024-11-29 04:44:33','2024-11-29 04:44:33');
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('hwGJe7Qe4qtMJNRegq6YKoehg1D9slboWSbRlVPr',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 OPR/114.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVHZWTlRXM0J6ZEpVNnNzNm5NaUV4ZmFwOW1ialhmM2FrQzRoZHN6WSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9ub3ZhLmxvY2FsL2dlc3Rpb24vYXNhbWJsZWEiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1732905857);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `signatures`
--

DROP TABLE IF EXISTS `signatures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `signatures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `signature` longtext NOT NULL,
  `persona_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `signatures_persona_id_foreign` (`persona_id`),
  CONSTRAINT `signatures_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `signatures`
--

LOCK TABLES `signatures` WRITE;
/*!40000 ALTER TABLE `signatures` DISABLE KEYS */;
/*!40000 ALTER TABLE `signatures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `states` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states`
--

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
INSERT INTO `states` VALUES (1,'Activo','2024-11-29 01:50:37','2024-11-29 01:50:37'),(2,'Ausente','2024-11-29 01:50:37','2024-11-29 01:50:37'),(4,'Unsigned','2024-11-29 01:50:37','2024-11-29 01:50:37'),(5,'Entregado','2024-11-29 01:50:37','2024-11-29 01:50:37');
/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `passwordTxt` varchar(255) NOT NULL,
  `cedula` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `roleTxt` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'emilton','hernandez','ehernandez','$2y$12$QeUI50w9tZlj.F3y4Aj38eoUsGEO08JghYUXRBucRO4zFG3JqYbAC','ehernandez',NULL,NULL,'Admin','2024-11-29 01:50:38','2024-11-29 01:50:38'),(2,'German','Gualdron','german.gualdron','$2y$12$7HNDztkzHUriZ4EJcRytJOaRFgj86FtGce4xikJHAbbCOb9xhQzDe','Manch1n1',NULL,NULL,'Admin','2024-11-29 01:50:38','2024-11-29 04:44:30'),(3,'Robinson','Leon','rleon','$2y$12$1VD8aqELTrr7vZQVerICXu8Mj6WQS28QzSPfzq8q6eATR5t0ldCR2','Tecnovis2024','1362599','21','Operario','2024-11-29 01:50:55','2024-11-29 04:44:30'),(4,'Daniel Ricardo','Ayala Garcia','daniel.ayala','$2y$12$mYx3uX8IYx9kOfZ83Jxal.EA3oWKY1c.qphINE6aUNq9EfJ6wWXX2','Tecnovis2024','1143','1231','Lider','2024-11-29 01:50:55','2024-11-29 04:44:30'),(5,'Daniela','Delgado','daniela.delgado','$2y$12$bkF43Hd963HzcpvIy.DHB.HfYSZscLH98DSbpP1p2wM1QQUNC4B0q','Tecnovis2024','1212123','31231223','Operario','2024-11-29 01:50:55','2024-11-29 04:44:31'),(6,'Maria Fernanda','Garcia','maria.garcia','$2y$12$92KILupkIkhCaacaj1G3x.HAdbiR41OvDKsB9sMR4HzROCPgAx4q2','Tecnovis2024','31324','2123123','Operario','2024-11-29 01:50:55','2024-11-29 04:44:31'),(7,'Erika','Quintero','erika.quintero','$2y$12$F2e6vaByjkf7bzG1i8V03edDYLJn8er1JubhiQqES2pK4o6TqYnqa','Tecnovis2024','213123','1231312','Operario','2024-11-29 01:50:56','2024-11-29 04:44:31'),(8,'Alejandra','Agudelo','alejandra.agudelo','$2y$12$Fosq2oJQEIOCzM7ujplUHudnUmtRcZ8tUCJyNxlzL7dBCqiB0Ty/S','Tecnovis2024','12213218','123132','Operario','2024-11-29 01:50:56','2024-11-29 04:44:31'),(9,'Jhon','Fragrozo','jhon.fragozo','$2y$12$cKCKrgrq/MhK5cKlxCrLkOfAvWFZhymcwRzEnilslDLC6y1/.mjC.','Tecnovis2024','1238321','123213','Operario','2024-11-29 01:50:56','2024-11-29 04:44:31'),(10,'Juan Diego','Esteban','juan.esteban','$2y$12$NAEAmyYTCpymA2GPoNLLy.0DNnNg7ScPzQ.cN2Y6i5CHjLung5QSy','Tecnovis2024','11231241432','123123','Lider','2024-11-29 01:50:56','2024-11-29 04:44:32'),(11,'Jhoan Sebastian','Caballero','jhoan.caballero','$2y$12$I7wIpZcmN1NQ04nIfajpouy27ySX5f2eoBGFhFfDVQaptznXzPnqK','Tecnovis2024','12312378','1232341','Lider','2024-11-29 01:50:57','2024-11-29 04:44:32'),(12,'Leonardo','Orjuela','leonardo.orjuela','$2y$12$g.i9TDTJ62M0.kcOoIupbOc8lL5T0IkL5eFUDGBq5fBWaeMlu9OYu','Tecnovis2024','1234','1234','Lider','2024-11-29 01:50:57','2024-11-29 04:44:32'),(13,'Daniel Santiago','Joya Caballero','daniel.joya','$2y$12$zv5ayMUoHOUS9Re2/XpTLehGrI.u4XFW8kU2jauN97oiTF33uyEQe','Tecnovis2024','113','1231','Lider','2024-11-29 01:50:57','2024-11-29 04:44:32'),(14,'Anderson Ivan ','Gonzalez Nova','anderson.gonzalez','$2y$12$cgx98I2GugvBYQqiOehVJeAlNcCe2W7N1WnL2Iqm7VROnFpKRtE/O','Tecnovis2024','998987','352562','Lider','2024-11-29 01:50:57','2024-11-29 04:44:33'),(15,'Leonardo','Gonzalez Trillos','leonardo.gonzales','$2y$12$NFyxC.lVjwYmsM2BqxtSqe8W0Ujzm8AdcVk04hdIWA04W/tUiKQWy','Tecnovis2024','888383','8329','Lider','2024-11-29 01:50:58','2024-11-29 04:44:33'),(16,'Angélica','Silva','angélica.silva','$2y$12$dzhh.g/sVIFiqH3oe1yGb.2ZSQmXlpJa9SiT2AW3gFarF5f5oJNNi','Tecnovis2024','990900','9090','Lider','2024-11-29 01:50:58','2024-11-29 04:44:33'),(17,'Camila','Amorocho','camila.amorocho','$2y$12$JRgwJNW3FEzzLUgVsvVM6uHd48DAy2XOOrEFIWvwSJc1QJQd1YZY6','Tecnovis2024','9009212','132112','Lider','2024-11-29 01:50:58','2024-11-29 04:44:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `control` int(11) NOT NULL,
  `vote` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `votes_control_unique` (`control`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
INSERT INTO `votes` VALUES (1,1,'A',NULL,NULL),(2,2,'A',NULL,NULL),(3,3,'A',NULL,NULL),(4,4,'A',NULL,NULL),(5,5,'A',NULL,NULL),(6,6,'A',NULL,NULL),(7,7,'A',NULL,NULL),(8,8,'A',NULL,NULL),(9,9,'A',NULL,NULL),(10,10,'B',NULL,NULL),(11,11,'A',NULL,NULL),(12,12,'A',NULL,NULL),(13,13,'B',NULL,NULL),(14,14,'F',NULL,NULL),(15,15,'A',NULL,NULL),(16,16,'A',NULL,NULL),(17,17,'A',NULL,NULL),(18,18,'A',NULL,NULL),(19,19,'A',NULL,NULL),(20,20,'B',NULL,NULL),(21,21,'A',NULL,NULL),(22,22,'A',NULL,NULL),(23,23,'A',NULL,NULL),(24,24,'A',NULL,NULL),(25,25,'A',NULL,NULL),(26,26,'A',NULL,NULL),(27,27,'A',NULL,NULL),(28,28,'B',NULL,NULL),(29,29,'B',NULL,NULL),(30,30,'B',NULL,NULL),(31,31,'A',NULL,NULL),(32,32,'B',NULL,NULL),(33,33,'A',NULL,NULL),(34,34,'A',NULL,NULL),(35,35,'A',NULL,NULL),(36,36,'E',NULL,NULL),(37,37,'A',NULL,NULL),(38,38,'B',NULL,NULL),(39,39,'B',NULL,NULL),(40,40,'A',NULL,NULL),(41,41,'A',NULL,NULL),(42,42,'B',NULL,NULL),(43,43,'A',NULL,NULL),(44,44,'A',NULL,NULL),(45,45,'A',NULL,NULL),(46,46,'B',NULL,NULL),(47,47,'A',NULL,NULL),(48,48,'A',NULL,NULL),(49,49,'A',NULL,NULL),(50,50,'B',NULL,NULL),(51,51,'A',NULL,NULL),(52,52,'A',NULL,NULL),(53,53,'A',NULL,NULL),(54,54,'A',NULL,NULL),(55,55,'A',NULL,NULL),(56,56,'A',NULL,NULL),(57,57,'A',NULL,NULL),(58,58,'A',NULL,NULL),(59,59,'A',NULL,NULL),(60,60,'A',NULL,NULL),(61,61,'A',NULL,NULL),(62,62,'A',NULL,NULL),(63,63,'A',NULL,NULL),(64,64,'B',NULL,NULL),(65,65,'A',NULL,NULL),(66,66,'A',NULL,NULL),(67,67,'B',NULL,NULL),(68,68,'B',NULL,NULL),(69,69,'A',NULL,NULL),(70,70,'A',NULL,NULL),(71,72,'A',NULL,NULL),(72,73,'B',NULL,NULL),(73,74,'A',NULL,NULL),(74,75,'A',NULL,NULL),(75,76,'B',NULL,NULL),(76,77,'A',NULL,NULL),(77,78,'A',NULL,NULL),(78,79,'A',NULL,NULL),(79,80,'B',NULL,NULL),(80,82,'B',NULL,NULL),(81,83,'B',NULL,NULL),(82,84,'A',NULL,NULL),(83,85,'A',NULL,NULL),(84,86,'A',NULL,NULL),(85,87,'A',NULL,NULL),(86,88,'A',NULL,NULL),(87,89,'A',NULL,NULL),(88,90,'B',NULL,NULL),(89,91,'A',NULL,NULL),(90,92,'A',NULL,NULL),(91,93,'A',NULL,NULL),(92,94,'B',NULL,NULL),(93,95,'A',NULL,NULL),(94,96,'A',NULL,NULL),(95,97,'A',NULL,NULL),(96,98,'A',NULL,NULL),(97,100,'A',NULL,NULL),(98,101,'A',NULL,NULL),(99,102,'A',NULL,NULL),(100,103,'A',NULL,NULL),(101,104,'A',NULL,NULL),(102,105,'A',NULL,NULL),(103,106,'A',NULL,NULL),(104,108,'A',NULL,NULL),(105,110,'B',NULL,NULL),(106,111,'A',NULL,NULL),(107,112,'B',NULL,NULL),(108,113,'A',NULL,NULL),(109,114,'A',NULL,NULL),(110,115,'A',NULL,NULL),(111,116,'A',NULL,NULL),(112,117,'C',NULL,NULL),(113,118,'B',NULL,NULL);
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-29 13:44:19
