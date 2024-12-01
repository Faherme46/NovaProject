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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asambleas`
--

LOCK TABLES `asambleas` WRITE;
/*!40000 ALTER TABLE `asambleas` DISABLE KEYS */;
INSERT INTO `asambleas` VALUES (1,'Aqua Tower_2024.11.29','Aqua Tower','Un lugar bonito','Bucaramanga','2024-11-29','10:00:00',100,NULL,NULL,NULL,0,0,'15:51:00',NULL,'2024-11-29 01:50:58','2024-11-29 01:51:14'),(2,'Rincon_De_Los_Caballeros_2024.11.29','Rincon De Los Caballeros','Un lugar bonito','Bucaramanga','2024-11-29','10:00:00',100,NULL,NULL,NULL,0,0,NULL,NULL,'2024-11-29 02:41:04','2024-11-29 02:41:04');
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
INSERT INTO `cache` VALUES ('asamblea','O:19:\"App\\Models\\Asamblea\":30:{s:13:\"\0*\0connection\";s:7:\"mariadb\";s:8:\"\0*\0table\";s:9:\"asambleas\";s:13:\"\0*\0primaryKey\";s:11:\"id_asamblea\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:6:\"folder\";s:24:\"Rincon De Los Caballeros\";s:4:\"name\";s:35:\"Rincon De Los Caballeros_2024.11.29\";s:5:\"lugar\";s:15:\"Un lugar bonito\";s:5:\"fecha\";s:10:\"2024-11-29\";s:4:\"hora\";s:5:\"10:00\";s:6:\"ciudad\";s:11:\"Bucaramanga\";s:9:\"controles\";s:3:\"100\";s:8:\"registro\";b:0;s:10:\"updated_at\";s:19:\"2024-11-28 21:41:04\";s:10:\"created_at\";s:19:\"2024-11-28 21:41:04\";s:11:\"id_asamblea\";i:2;}s:11:\"\0*\0original\";a:11:{s:6:\"folder\";s:24:\"Rincon De Los Caballeros\";s:4:\"name\";s:35:\"Rincon De Los Caballeros_2024.11.29\";s:5:\"lugar\";s:15:\"Un lugar bonito\";s:5:\"fecha\";s:10:\"2024-11-29\";s:4:\"hora\";s:5:\"10:00\";s:6:\"ciudad\";s:11:\"Bucaramanga\";s:9:\"controles\";s:3:\"100\";s:8:\"registro\";b:0;s:10:\"updated_at\";s:19:\"2024-11-28 21:41:04\";s:10:\"created_at\";s:19:\"2024-11-28 21:41:04\";s:11:\"id_asamblea\";i:2;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}',2048190064),('id_asamblea','i:2;',2048190064),('inRegistro','b:0;',2048190064);
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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `controls`
--

LOCK TABLES `controls` WRITE;
/*!40000 ALTER TABLE `controls` DISABLE KEYS */;
INSERT INTO `controls` VALUES (1,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(2,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(3,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(4,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(5,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(6,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(7,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(8,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(9,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(10,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(11,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(12,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(13,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(14,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(15,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(16,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(17,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(18,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(19,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(20,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(21,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(22,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(23,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(24,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(25,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(26,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(27,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(28,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(29,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(30,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(31,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(32,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(33,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(34,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(35,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(36,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(37,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(38,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(39,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(40,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(41,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(42,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(43,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(44,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(45,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(46,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(47,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(48,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(49,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(50,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(51,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(52,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(53,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(54,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(55,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(56,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(57,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(58,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(59,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(60,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(61,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(62,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(63,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(64,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(65,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(66,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(67,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(68,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(69,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(70,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(71,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(72,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(73,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(74,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(75,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(76,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(77,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(78,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(79,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(80,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(81,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(82,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(83,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(84,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(85,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(86,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(87,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(88,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(89,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(90,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(91,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(92,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(93,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(94,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(95,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(96,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:04','2024-11-29 02:41:04',NULL,NULL,'0'),(97,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:05','2024-11-29 02:41:05',NULL,NULL,'0'),(98,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:05','2024-11-29 02:41:05',NULL,NULL,'0'),(99,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:05','2024-11-29 02:41:05',NULL,NULL,'0'),(100,4,NULL,0.000000,0,0.000000,'2024-11-29 02:41:05','2024-11-29 02:41:05',NULL,NULL,'0');
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
INSERT INTO `predios` VALUES (1,'Torre','Don Andres','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(2,'Torre','Don Andres','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(3,'Torre','Don Andres','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(4,'Torre','Don Andres','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(5,'Torre','Don Andres','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(6,'Torre','Don Andres','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(7,'Torre','Don Andres','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(8,'Torre','Don Andres','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(9,'Torre','Don Andres','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(10,'Torre','Don Andres','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(11,'Torre','Don Andres','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(12,'Torre','Don Andres','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(13,'Torre','Don Andres','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(14,'Torre','Don Andres','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(15,'Torre','Don Andres','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(16,'Torre','Don Andres','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(17,'Torre','Don Andres','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(18,'Torre','Don Andres','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(19,'Torre','Don Andres','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(20,'Torre','Don Andres','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(21,'Torre','Don Camilo','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(22,'Torre','Don Camilo','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(23,'Torre','Don Camilo','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(24,'Torre','Don Camilo','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(25,'Torre','Don Camilo','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(26,'Torre','Don Camilo','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(27,'Torre','Don Camilo','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(28,'Torre','Don Camilo','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(29,'Torre','Don Camilo','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(30,'Torre','Don Camilo','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(31,'Torre','Don Camilo','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(32,'Torre','Don Camilo','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(33,'Torre','Don Camilo','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(34,'Torre','Don Camilo','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(35,'Torre','Don Camilo','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(36,'Torre','Don Camilo','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(37,'Torre','Don Camilo','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(38,'Torre','Don Camilo','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(39,'Torre','Don Camilo','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(40,'Torre','Don Camilo','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(41,'Torre','Don Carlos','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(42,'Torre','Don Carlos','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(43,'Torre','Don Carlos','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(44,'Torre','Don Carlos','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(45,'Torre','Don Carlos','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(46,'Torre','Don Carlos','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(47,'Torre','Don Carlos','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(48,'Torre','Don Carlos','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(49,'Torre','Don Carlos','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(50,'Torre','Don Carlos','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(51,'Torre','Don Carlos','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(52,'Torre','Don Carlos','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(53,'Torre','Don Carlos','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(54,'Torre','Don Carlos','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(55,'Torre','Don Carlos','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(56,'Torre','Don Carlos','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(57,'Torre','Don Carlos','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(58,'Torre','Don Carlos','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(59,'Torre','Don Carlos','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(60,'Torre','Don Carlos','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(61,'Torre','Don Felipe','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(62,'Torre','Don Felipe','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(63,'Torre','Don Felipe','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(64,'Torre','Don Felipe','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(65,'Torre','Don Felipe','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(66,'Torre','Don Felipe','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(67,'Torre','Don Felipe','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(68,'Torre','Don Felipe','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(69,'Torre','Don Felipe','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(70,'Torre','Don Felipe','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(71,'Torre','Don Felipe','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(72,'Torre','Don Felipe','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(73,'Torre','Don Felipe','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(74,'Torre','Don Felipe','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(75,'Torre','Don Felipe','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(76,'Torre','Don Felipe','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(77,'Torre','Don Felipe','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(78,'Torre','Don Felipe','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(79,'Torre','Don Felipe','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(80,'Torre','Don Felipe','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(81,'Torre','Don Fernando','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(82,'Torre','Don Fernando','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(83,'Torre','Don Fernando','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(84,'Torre','Don Fernando','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(85,'Torre','Don Fernando','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(86,'Torre','Don Fernando','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(87,'Torre','Don Fernando','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(88,'Torre','Don Fernando','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(89,'Torre','Don Fernando','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(90,'Torre','Don Fernando','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(91,'Torre','Don Fernando','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(92,'Torre','Don Fernando','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(93,'Torre','Don Fernando','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(94,'Torre','Don Fernando','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(95,'Torre','Don Fernando','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(96,'Torre','Don Fernando','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(97,'Torre','Don Fernando','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(98,'Torre','Don Fernando','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(99,'Torre','Don Fernando','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(100,'Torre','Don Fernando','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(101,'Torre','Don Gabrriel','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(102,'Torre','Don Gabrriel','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(103,'Torre','Don Gabrriel','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(104,'Torre','Don Gabrriel','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(105,'Torre','Don Gabrriel','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(106,'Torre','Don Gabrriel','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(107,'Torre','Don Gabrriel','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(108,'Torre','Don Gabrriel','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(109,'Torre','Don Gabrriel','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(110,'Torre','Don Gabrriel','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(111,'Torre','Don Gabrriel','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(112,'Torre','Don Gabrriel','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(113,'Torre','Don Gabrriel','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(114,'Torre','Don Gabrriel','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(115,'Torre','Don Gabrriel','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(116,'Torre','Don Gabrriel','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(117,'Torre','Don Gabrriel','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(118,'Torre','Don Gabrriel','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(119,'Torre','Don Gabrriel','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(120,'Torre','Don Gabrriel','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(121,'Torre','Don Gonzalo','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(122,'Torre','Don Gonzalo','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(123,'Torre','Don Gonzalo','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(124,'Torre','Don Gonzalo','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(125,'Torre','Don Gonzalo','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(126,'Torre','Don Gonzalo','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(127,'Torre','Don Gonzalo','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(128,'Torre','Don Gonzalo','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(129,'Torre','Don Gonzalo','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(130,'Torre','Don Gonzalo','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(131,'Torre','Don Gonzalo','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(132,'Torre','Don Gonzalo','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(133,'Torre','Don Gonzalo','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(134,'Torre','Don Gonzalo','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(135,'Torre','Don Gonzalo','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(136,'Torre','Don Gonzalo','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(137,'Torre','Don Gonzalo','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(138,'Torre','Don Gonzalo','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(139,'Torre','Don Gonzalo','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(140,'Torre','Don Gonzalo','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(141,'Torre','Don Guillermo','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(142,'Torre','Don Guillermo','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(143,'Torre','Don Guillermo','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(144,'Torre','Don Guillermo','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(145,'Torre','Don Guillermo','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(146,'Torre','Don Guillermo','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(147,'Torre','Don Guillermo','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(148,'Torre','Don Guillermo','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(149,'Torre','Don Guillermo','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(150,'Torre','Don Guillermo','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(151,'Torre','Don Guillermo','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(152,'Torre','Don Guillermo','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(153,'Torre','Don Guillermo','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(154,'Torre','Don Guillermo','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(155,'Torre','Don Guillermo','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(156,'Torre','Don Guillermo','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:40:59','2024-11-29 02:40:59'),(157,'Torre','Don Guillermo','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(158,'Torre','Don Guillermo','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(159,'Torre','Don Guillermo','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(160,'Torre','Don Guillermo','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(161,'Torre','Don Jorge','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(162,'Torre','Don Jorge','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(163,'Torre','Don Jorge','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(164,'Torre','Don Jorge','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(165,'Torre','Don Jorge','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(166,'Torre','Don Jorge','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(167,'Torre','Don Jorge','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(168,'Torre','Don Jorge','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(169,'Torre','Don Jorge','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(170,'Torre','Don Jorge','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(171,'Torre','Don Jorge','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(172,'Torre','Don Jorge','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(173,'Torre','Don Jorge','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(174,'Torre','Don Jorge','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(175,'Torre','Don Jorge','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(176,'Torre','Don Jorge','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(177,'Torre','Don Jorge','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(178,'Torre','Don Jorge','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(179,'Torre','Don Jorge','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(180,'Torre','Don Jorge','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(181,'Torre','Don Pedro','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(182,'Torre','Don Pedro','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(183,'Torre','Don Pedro','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(184,'Torre','Don Pedro','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(185,'Torre','Don Pedro','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(186,'Torre','Don Pedro','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(187,'Torre','Don Pedro','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(188,'Torre','Don Pedro','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(189,'Torre','Don Pedro','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(190,'Torre','Don Pedro','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(191,'Torre','Don Pedro','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(192,'Torre','Don Pedro','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(193,'Torre','Don Pedro','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(194,'Torre','Don Pedro','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(195,'Torre','Don Pedro','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(196,'Torre','Don Pedro','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(197,'Torre','Don Pedro','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(198,'Torre','Don Pedro','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(199,'Torre','Don Pedro','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(200,'Torre','Don Pedro','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(201,'Torre','Don Vasco','Apartamento','101',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(202,'Torre','Don Vasco','Apartamento','102',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(203,'Torre','Don Vasco','Apartamento','103',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(204,'Torre','Don Vasco','Apartamento','104',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(205,'Torre','Don Vasco','Apartamento','201',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(206,'Torre','Don Vasco','Apartamento','202',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(207,'Torre','Don Vasco','Apartamento','203',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(208,'Torre','Don Vasco','Apartamento','204',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(209,'Torre','Don Vasco','Apartamento','301',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(210,'Torre','Don Vasco','Apartamento','302',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(211,'Torre','Don Vasco','Apartamento','303',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(212,'Torre','Don Vasco','Apartamento','304',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(213,'Torre','Don Vasco','Apartamento','401',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(214,'Torre','Don Vasco','Apartamento','402',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(215,'Torre','Don Vasco','Apartamento','403',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(216,'Torre','Don Vasco','Apartamento','404',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(217,'Torre','Don Vasco','Apartamento','501',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(218,'Torre','Don Vasco','Apartamento','502',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(219,'Torre','Don Vasco','Apartamento','503',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00'),(220,'Torre','Don Vasco','Apartamento','504',0.45455,1,NULL,0,0,NULL,'2024-11-29 02:41:00','2024-11-29 02:41:00');
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
INSERT INTO `question_types` VALUES (1,'Quorum','2024-11-29 02:40:14','2024-11-29 02:40:14'),(2,'Seleccion','2024-11-29 02:40:14','2024-11-29 02:40:14'),(3,'Aprobacion','2024-11-29 02:40:14','2024-11-29 02:40:14'),(4,'SI/NO','2024-11-29 02:40:14','2024-11-29 02:40:14'),(5,'TD','2024-11-29 02:40:14','2024-11-29 02:40:14'),(6,'Plancha','2024-11-29 02:40:14','2024-11-29 02:40:14');
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
  `title` varchar(255) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,3,'Aprobacion de estados Financieros','','','','Aprobado','No Aprobado','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(2,3,'Aprobacion de orden del dia','','','','Aprobado','No Aprobado','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(3,3,'Aprobacion de Presupuestos','','','','Aprobado','No Aprobado','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(4,3,'Aprobacion del Acta','','','','Aprobado','No Aprobado','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(5,2,'Comite de Convivencia','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(6,2,'Consejo de Administracion','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(7,5,'Consentimiento de datos personales','Voto Publico','Voto Privado','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(8,2,'Eleccion de Presidente','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(9,2,'Eleccion de revisor fiscal','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(10,2,'Eleccion de secretario','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(11,1,'Verificación de Quorum','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(12,4,'Proposicion','SI','NO','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14'),(13,2,'Prueba','','','','','','',1,0,NULL,NULL,0,NULL,1,'2024-11-29 02:40:14','2024-11-29 02:40:14');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
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
INSERT INTO `session` VALUES (1,2,'Rincon De Los Caballeros','2024-11-29 02:41:04','2024-11-29 02:41:04');
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
INSERT INTO `sessions` VALUES ('lZ9CHQ079NZDrgzsScjlUvOtYGpZVAmnvOchKGeV',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 OPR/114.0.0.0','YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YTowOnt9fXM6NjoiX3Rva2VuIjtzOjQwOiI4OHJXUTdzYWpHWUFsQ3JIb1Z6M1VPWWs5cFNFUjlRdFJOSjRTSUR6IjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovL25vdmEubG9jYWwvZ2VzdGlvbi9hc2FtYmxlYSI7fX0=',1732832448);
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
INSERT INTO `users` VALUES (1,'emilton','hernandez','ehernandez','$2y$12$QeUI50w9tZlj.F3y4Aj38eoUsGEO08JghYUXRBucRO4zFG3JqYbAC','ehernandez',NULL,NULL,'Admin','2024-11-29 01:50:38','2024-11-29 01:50:38'),(2,'German','Gualdron','german.gualdron','$2y$12$1ykADWaFwt2T92Nu44m/qupia2LSzaTmvJTyyJFG1HlTfAKvEgezC','Manch1n1',NULL,NULL,'Admin','2024-11-29 01:50:38','2024-11-29 02:41:00'),(3,'Robinson','Leon','rleon','$2y$12$3Fpt.GA5VcMCAtfJrKN4Dur9/wUNzp/If5Kj4/spvS.A22ejuA7N6','Tecnovis2024','1362599','21','Operario','2024-11-29 01:50:55','2024-11-29 02:41:00'),(4,'Daniel Ricardo','Ayala Garcia','daniel.ayala','$2y$12$YHkMgrU2zoPhEzyZ3S.2auGNyHwDMuLBsp8kfzZ0Z4Wboz3D3Uf0m','Tecnovis2024','1143','1231','Lider','2024-11-29 01:50:55','2024-11-29 02:41:00'),(5,'Daniela','Delgado','daniela.delgado','$2y$12$DWSeh7a/XoYkiT/VKjCfF.HWIgxazopfmYZgsl9NwBvB4iRm5UlXa','Tecnovis2024','1212123','31231223','Operario','2024-11-29 01:50:55','2024-11-29 02:41:01'),(6,'Maria Fernanda','Garcia','maria.garcia','$2y$12$WYwsWV8yNxF4Xtanb3ZPTu7i2HTFCYIqspOs/ysw14BEFmBljrr8K','Tecnovis2024','31324','2123123','Operario','2024-11-29 01:50:55','2024-11-29 02:41:01'),(7,'Erika','Quintero','erika.quintero','$2y$12$uUx4lAae9ItkTRehmPQM4eDB39aFUssSU3AmT1cb//mu5f/hWnvtC','Tecnovis2024','213123','1231312','Operario','2024-11-29 01:50:56','2024-11-29 02:41:01'),(8,'Alejandra','Agudelo','alejandra.agudelo','$2y$12$x3BW6HzHTIvYlctVamwKG.mddvnmPSRaz5R1RSf/7kVKVbsZSU9Ca','Tecnovis2024','12213218','123132','Operario','2024-11-29 01:50:56','2024-11-29 02:41:01'),(9,'Jhon','Fragrozo','jhon.fragozo','$2y$12$/yzbcbTepnwNQISxmKmDX..3B.iwuP62mvuNr55YjLVqK7iY/3Z/m','Tecnovis2024','1238321','123213','Operario','2024-11-29 01:50:56','2024-11-29 02:41:02'),(10,'Juan Diego','Esteban','juan.esteban','$2y$12$.5xLRkhfKfkTKGbICAAg.uuuRDN6iJ7ylfRD4qzQCZjvu3WbFLuwa','Tecnovis2024','11231241432','123123','Lider','2024-11-29 01:50:56','2024-11-29 02:41:02'),(11,'Jhoan Sebastian','Caballero','jhoan.caballero','$2y$12$6v2aX6qJjFM4DFPx6okT4ue9cwNKIzYmoSGDXOLT4N8O5wUdhsZhm','Tecnovis2024','12312378','1232341','Lider','2024-11-29 01:50:57','2024-11-29 02:41:02'),(12,'Leonardo','Orjuela','leonardo.orjuela','$2y$12$43q8gfSR/deW1Awd0tAD8Ouz9iBo5VItLOQJA26vRIfp.jLxr/ZZ6','Tecnovis2024','1234','1234','Lider','2024-11-29 01:50:57','2024-11-29 02:41:03'),(13,'Daniel Santiago','Joya Caballero','daniel.joya','$2y$12$fLDXX4f6LoaZ5CU.6KbnHOBy5svlNVAvQaZXnLXWoVuT/xbHF3fHm','Tecnovis2024','113','1231','Lider','2024-11-29 01:50:57','2024-11-29 02:41:03'),(14,'Anderson Ivan ','Gonzalez Nova','anderson.gonzalez','$2y$12$5SjfhFPVKG4wJoF524Iv8.qW72ZwSpfrIp78hSR/tckHG/I4ypf7G','Tecnovis2024','998987','352562','Lider','2024-11-29 01:50:57','2024-11-29 02:41:03'),(15,'Leonardo','Gonzalez Trillos','leonardo.gonzales','$2y$12$lC4pB11sEWv9KsGBnby4HOZaR7G8BFXxGo7AIUQdQpeNM6UUXe2.S','Tecnovis2024','888383','8329','Lider','2024-11-29 01:50:58','2024-11-29 02:41:04'),(16,'Angélica','Silva','angélica.silva','$2y$12$bxj3uuIx13CioOrz9np47O4iLPCkjrPHO7A4v15b3Sl8Brx.hi7ge','Tecnovis2024','990900','9090','Lider','2024-11-29 01:50:58','2024-11-29 02:41:04'),(17,'Camila','Amorocho','camila.amorocho','$2y$12$EQtVzFySqAJMbBFnb9d2DefSAyVNwwkuSGLbSOXjFIPn9AcVs.oMi','Tecnovis2024','9009212','132112','Lider','2024-11-29 01:50:58','2024-11-29 02:41:04');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
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

-- Dump completed on 2024-11-28 17:20:54
