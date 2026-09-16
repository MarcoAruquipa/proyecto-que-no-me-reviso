-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: maletin_movilidades
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
-- Current Database: `maletin_movilidades`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `maletin_movilidades` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `maletin_movilidades`;

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
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
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
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `ci` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `vehiculo_interes` varchar(255) DEFAULT NULL,
  `metodo_pago` varchar(255) DEFAULT NULL,
  `fuente` varchar(255) DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'En negociación',
  `notas` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'2026-07-03 09:55:49','2026-07-03 09:55:49','juanito','14450264','67128324','marcoantonioaruquipaloza0@gmail.com','Calle Virgen de Punata',NULL,NULL,NULL,'En negociación',NULL),(2,'2026-07-03 09:58:43','2026-07-03 09:58:43','juan','14450264','+59167128324',NULL,'Calle Virgen de Punata','SUV','Contado','Referido','En negociación','eata medio indesiso'),(3,'2026-07-03 17:41:22','2026-07-03 17:41:22','david condori','14450265','3232','david@gamil.com',NULL,'Sedán',NULL,NULL,'En negociación',NULL),(4,'2026-07-03 19:17:59','2026-07-03 19:17:59','osavaldo',NULL,'454545','osvaldo@itboliviamar.edu.bo',NULL,NULL,NULL,NULL,'En negociación',NULL),(5,'2026-09-10 00:26:22','2026-09-10 00:26:22','marco','14459264','67128324','marco@gmail.com','zona villa paulina','toyota rush','eectivo',NULL,'En negociacion','quiere de color rojo'),(6,'2026-09-10 00:48:35','2026-09-10 00:48:35','Cliente Prueba','12345678','04140001111',NULL,NULL,NULL,NULL,NULL,'En negociacion',NULL),(7,'2026-09-16 01:10:33','2026-09-16 01:10:33','Ximena','525262','67128324','Ximena@gmail.com','tarapaca d','sususki blanco','efectivo',NULL,'En negociacion','quiere ya nomás');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contratos`
--

DROP TABLE IF EXISTS `contratos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contratos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `vehicle_id` bigint(20) unsigned DEFAULT NULL,
  `estado` enum('Pendiente','En revisión','Firmado') NOT NULL DEFAULT 'Pendiente',
  `fecha_firma` date DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contratos_user_id_foreign` (`user_id`),
  KEY `contratos_client_id_foreign` (`client_id`),
  KEY `contratos_vehicle_id_foreign` (`vehicle_id`),
  CONSTRAINT `contratos_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `contratos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contratos_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contratos`
--

LOCK TABLES `contratos` WRITE;
/*!40000 ALTER TABLE `contratos` DISABLE KEYS */;
/*!40000 ALTER TABLE `contratos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos`
--

DROP TABLE IF EXISTS `documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `tipo` varchar(100) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documentos_user_id_foreign` (`user_id`),
  KEY `documentos_client_id_foreign` (`client_id`),
  CONSTRAINT `documentos_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `documentos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos`
--

LOCK TABLES `documentos` WRITE;
/*!40000 ALTER TABLE `documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos` ENABLE KEYS */;
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
-- Table structure for table `maletins`
--

DROP TABLE IF EXISTS `maletins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maletins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maletins`
--

LOCK TABLES `maletins` WRITE;
/*!40000 ALTER TABLE `maletins` DISABLE KEYS */;
/*!40000 ALTER TABLE `maletins` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_06_29_011844_add_datos_to_users_table',1),(5,'2026_06_29_011844_create_vehicles_table',1),(6,'2026_06_29_011845_create_clients_table',1),(7,'2026_06_29_011845_create_maletins_table',1),(8,'2026_06_29_011845_create_sales_table',1),(9,'2026_06_29_013445_add_datos_to_users_table',1),(10,'2026_07_03_051020_corregir_tablas_maletin_digital',2),(11,'2026_09_04_000001_create_personal_access_tokens_table',3),(12,'2026_09_15_100000_add_jefe_role_to_users_table',4),(13,'2026_09_15_100001_create_documentos_table',4),(14,'2026_09_15_100002_create_contratos_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('marcoantonioaruquipaloza@gmail.com','$2y$12$z7KW1dLN6HpysbcvB2uRXecJEs4x4CSSLpKEY.7bOJ2NCgT3KQGhq','2026-08-24 19:12:42'),('marcoantonioaruquipaloza0@gmail.com','$2y$12$VodLUmJzzzgIY2DDpOh9Xuv49xdIcvkRgbpv9Wav4b6XNFmT9.MYq','2026-08-24 19:46:20');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (3,'App\\Models\\User',1,'auth-token','899e09238fd6cf38053db64fa6a00496b86220bff0eb6654d1a7c208959a28aa','[\"*\"]',NULL,NULL,'2026-09-10 00:08:29','2026-09-10 00:08:29'),(4,'App\\Models\\User',1,'auth-token','341db6d74c9e3d88f053f18aa61c205fce4635659b9ba6619862bdfe630b9bff','[\"*\"]',NULL,NULL,'2026-09-10 00:08:36','2026-09-10 00:08:36'),(5,'App\\Models\\User',18,'auth-token','7d91cbf1fe1bf029b55c5e335b7d659564b891030cc39a0001e56a6a64dd3bff','[\"*\"]','2026-09-10 00:32:19',NULL,'2026-09-10 00:20:05','2026-09-10 00:32:19'),(7,'App\\Models\\User',1,'auth-token','62597ea4afd3954dde76a655866a23eaafa1d16c9c606fa1a27c98033a4229d0','[\"*\"]','2026-09-10 00:41:43',NULL,'2026-09-10 00:41:42','2026-09-10 00:41:43'),(8,'App\\Models\\User',1,'auth-token','a693bfe438dc6a8532f0dcf8735b2bf3cebe56d1b399a896d024bd5a57ec0271','[\"*\"]','2026-09-10 00:48:35',NULL,'2026-09-10 00:48:35','2026-09-10 00:48:35'),(10,'App\\Models\\User',3,'auth-token','83ac3b2f75d28cf80296639482d6bd2277600028a855ad9427ed4d2305f98729','[\"*\"]','2026-09-10 01:07:07',NULL,'2026-09-10 01:03:14','2026-09-10 01:07:07'),(14,'App\\Models\\User',18,'auth-token','46a2f2453f042a1b787f9e192138778f39c56ec294173f26d5329c7a90057453','[\"*\"]','2026-09-16 01:17:48',NULL,'2026-09-16 01:06:23','2026-09-16 01:17:48'),(15,'App\\Models\\User',2,'auth-token','96e4191cf7cd28f851fa326c061496bfa333ce6919be84a74984c357567eabd9','[\"*\"]','2026-09-16 02:14:56',NULL,'2026-09-16 02:14:54','2026-09-16 02:14:56'),(16,'App\\Models\\User',2,'auth-token','1121df402a9c4b123ee05f3cdafa320b930b8070700e9a090c79ecc208a844f4','[\"*\"]',NULL,NULL,'2026-09-16 02:14:55','2026-09-16 02:14:55'),(17,'App\\Models\\User',15,'auth-token','8d6a8d7a83800b44f8606c034848440e3844215e212e0b9285afe8760965a1a1','[\"*\"]','2026-09-16 02:51:23',NULL,'2026-09-16 02:49:27','2026-09-16 02:51:23'),(18,'App\\Models\\User',21,'auth-token','e9f90bf7f26b542f50a2a72b9423d79d6ad1a49ea9584f0e577434d6ab474b6b','[\"*\"]','2026-09-16 06:11:31',NULL,'2026-09-16 06:11:28','2026-09-16 06:11:31'),(19,'App\\Models\\User',2,'auth-token','f4254e64b244a035f3db013125e623e99e06f7d3670bfc8c3c64a853553fa94c','[\"*\"]','2026-09-16 06:11:42',NULL,'2026-09-16 06:11:42','2026-09-16 06:11:42'),(20,'App\\Models\\User',1,'auth-token','c13de1c53b130f3c54a1ab6a0b7cb575c3bcae7d459a356d80572b7f62787188','[\"*\"]','2026-09-16 06:11:50',NULL,'2026-09-16 06:11:50','2026-09-16 06:11:50'),(21,'App\\Models\\User',1,'auth-token','4dfe5e39d1c3fe2292847a80723307bab376279e8dd427e23bb6d3c917003c32','[\"*\"]','2026-09-16 06:15:31',NULL,'2026-09-16 06:15:24','2026-09-16 06:15:31'),(22,'App\\Models\\User',1,'auth-token','e9cba4c1d6ba5435d325e8dca02ba5cae9624fea5f9fa54a63ca0d8c4c0b8caf','[\"*\"]','2026-09-16 06:15:47',NULL,'2026-09-16 06:15:45','2026-09-16 06:15:47'),(23,'App\\Models\\User',1,'auth-token','e0b65ed5d472b282c0be573335981087bcbab3bb9cb51ad44d74a5a011111c3b','[\"*\"]','2026-09-16 06:15:59',NULL,'2026-09-16 06:15:58','2026-09-16 06:15:59'),(24,'App\\Models\\User',1,'auth-token','f89ddcc4cadf01bfc3e4f2d4c81a91a69eb4104b85fbe6e0ca7b9d3467c327b7','[\"*\"]','2026-09-16 06:16:13',NULL,'2026-09-16 06:16:13','2026-09-16 06:16:13'),(25,'App\\Models\\User',1,'auth-token','e34e0f2a64b92ffd212ab096154a19003333aee4581de582ed9eff8a825083f9','[\"*\"]','2026-09-16 06:19:37',NULL,'2026-09-16 06:19:33','2026-09-16 06:19:37'),(26,'App\\Models\\User',1,'auth-token','56d547da2a99173ebb13029219a803469c513bb903a0ede78664d68866b6b3ff','[\"*\"]','2026-09-16 06:20:25',NULL,'2026-09-16 06:20:18','2026-09-16 06:20:25'),(27,'App\\Models\\User',1,'auth-token','29d5a6b32cd722f6eea8970eaec4df2b150d79649adf06472fee1284fc9d65ef','[\"*\"]','2026-09-16 06:20:47',NULL,'2026-09-16 06:20:39','2026-09-16 06:20:47');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vehicle_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `precio_venta` decimal(12,2) NOT NULL DEFAULT 0.00,
  `observacion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,'2026-07-03 10:04:01','2026-07-03 10:04:01',1,1,1,'2026-07-03',125000.00,'ningula'),(2,'2026-09-10 00:27:02','2026-09-10 00:27:02',3,3,18,'2027-09-09',99000.00,NULL);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('bqi44RFGwsLTNYqsXRNxgIj6JDSXLp0PpRoDBmWK',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibXBFSUVIMVR2U3U2U2RGM3pacWt6czFzT0RUWVdQa1Z5a1dFVDI0SSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly9sb2NhbGhvc3QvbWFsZXRpbl9tb3ZpbGlkYWRlcy9wdWJsaWMvcmVnaXN0cm8iO3M6NToicm91dGUiO3M6ODoicmVnaXN0cm8iO319',1783053156);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
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
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','jefe','tecnico','invitado') NOT NULL DEFAULT 'invitado',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `sucursal` varchar(255) DEFAULT NULL,
  `cargo` varchar(255) DEFAULT NULL,
  `supervisor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Jefe de Sucursal','admin','admin@pegaso.com',NULL,'$2y$12$kxHvLrtTNPKfzHZyNQKAD.7b0Yekp./ffDLPqwfeOEJ5.3vloACdm','admin',NULL,'2026-07-03 08:30:10','2026-07-03 09:13:18','70000001','Bolivia Central','Jefe de Sucursal','Administrador General'),(2,'MARCO ANTONIO ARUQUIPA LOZA','tecnico1','tecnico1@pegaso.com',NULL,'$2y$12$5bBv/HyR9CzyjYMbi8zfK.dF.HNgrITvnTNWYmCGR8dt6SurPKDsS','tecnico',NULL,'2026-07-03 08:30:10','2026-07-03 09:52:41','67128324','Bolivia Central','Ejecutivo de Venta','Jefe de Sucursal'),(3,'Usuario Invitado','invitado','invitado@pegaso.com',NULL,'$2y$12$hnLyv8B3yxFwBcZiPWPOTeKi1VdaQ6ugpsWu37gre4E.iZMOZROmm','invitado',NULL,'2026-07-03 08:30:10','2026-07-03 09:13:20','70000002','General','Invitado','Sistema'),(4,'MARCO ANTONIO','67128324','marcoantonioaruquipaloza0@gmail.com',NULL,'$2y$12$S7SvGyAt0Ncor/ngaJUmlOAPss0ertF/oZBO/f/Pp6AwZbQ54jml2','tecnico',NULL,'2026-07-03 09:16:30','2026-07-03 09:16:30',NULL,NULL,NULL,NULL),(5,'Marco Antonio','marco1','marcoantonio@gmail.com',NULL,'$2y$12$V1kbrEiychqpQBaSk3C8Q.ShDoLoLUdEdkW0uYrrLB8j4nsdjRCqG','tecnico',NULL,'2026-07-03 09:36:28','2026-07-03 09:36:28',NULL,NULL,NULL,NULL),(6,'Marco Antonio','invitado1','est.marco.aruquipaloza@itboliviamar.edu.bo',NULL,'$2y$12$JMC9V4rkVRZLJ1S6mHIQkOp5uUBTuAx9m9dkMZUguaqX0xzzFCh5G','invitado',NULL,'2026-07-03 09:40:43','2026-07-03 19:59:29',NULL,'General',NULL,'Jefe de Sucursal'),(7,'marquiños','marcquiños','marcoantonioaruquipaloza@gmail.com',NULL,'$2y$12$qsknRDVAsnMkNeprT5.7Weu11NXakfPnuYZTrJy3p8fwT7EUIgR2G','invitado',NULL,'2026-07-03 16:16:10','2026-07-03 16:16:56','67128324',NULL,NULL,NULL),(8,'Marco Antonio','administrador','susanhilda62@gmail.com',NULL,'$2y$12$HKpYR43z53/j.a8jPBtI2Okh1skUSsRU4ybLGJGb7aHzgW1/YZ48.','admin',NULL,'2026-07-03 16:44:53','2026-07-03 16:44:53',NULL,NULL,NULL,NULL),(9,'Juan Lopez','juan1','aruquipa@gmail.com',NULL,'$2y$12$MBsd2pMyXYpRdPzPdjDKF.NY5Y184G.ttI/eFB2hLS5I1JwDBzM66','invitado',NULL,'2026-08-24 18:35:58','2026-08-24 18:35:58',NULL,NULL,NULL,NULL),(12,'alert(\"xss\")','David1','david2@gamil.com',NULL,'$2y$12$PvG4lGGRRTsK0xoTo.lTSuYiGvojGo03xlGHLPW0N00jjlkNCuxN2','invitado',NULL,'2026-08-24 19:08:59','2026-08-24 19:08:59',NULL,NULL,NULL,NULL),(13,'Erick','Erick','erick@gmail.com',NULL,'$2y$12$SGS7YJ2lKnaRABm2/cglS.DmUCQgaptWXShccLznXTTYnTgzWDqeK','invitado',NULL,'2026-08-24 19:40:07','2026-08-24 19:40:07',NULL,NULL,NULL,NULL),(14,'Ximena','Ximena2','ximena2@gmail.com',NULL,'$2y$12$TNRwiQYm/LaTCPFAUISLt.U1mc553jNFVqXIFMsXEnjUgchPL.nYa','invitado',NULL,'2026-08-31 18:35:49','2026-08-31 18:35:49',NULL,NULL,NULL,NULL),(15,'Marco Antonio','marco2','marco@gmail.com',NULL,'$2y$12$VVEDfjnnyckB66fsaY.UGOm/b9bCGpmvNGa0G91szmsjznuybaKSy','invitado',NULL,'2026-08-31 18:37:23','2026-08-31 18:37:23',NULL,NULL,NULL,NULL),(16,'mani','mani2','mani@gmail.com',NULL,'$2y$12$tOT6d8jU4pBpPstjhSKWg.quFuGJKqYSJoG5B7OjSTQ59WKYBxOmK','invitado',NULL,'2026-09-04 20:19:50','2026-09-04 20:19:50',NULL,NULL,NULL,NULL),(18,'marco Antonio Aruquipa Loza','marco3','marco3@gmail.com',NULL,'$2y$12$V7vspwl4v4zPDpsGIPT.a.asxs/GMkEryDlUO/w.nLImyl4RQ5N.C','invitado',NULL,'2026-09-10 00:20:04','2026-09-16 01:17:46','67128324','lopez','ejecutivo en venta',NULL),(19,'Marco Antonio','Ximena3','Ximena3@gmail.com',NULL,'$2y$12$2wirM18VEPj2I/89U0Q2L.XGBlIH4/6S9/eSwQbK6IXkZxkNcxSf2','invitado',NULL,'2026-09-16 01:01:27','2026-09-16 01:01:27','77778499','lopez','ejecutiva en venta',NULL),(20,'Laura Condori','Laura','laura@gmail.com',NULL,'$2y$12$XicN5A1xfW63rbPOc1NYCeCbPDCXQsohyn5n.CU.xBlyK0ntIY8Ci','invitado',NULL,'2026-09-16 01:04:00','2026-09-16 01:04:00','77778499','El Alto','ejecutivo de ventas',NULL),(21,'Jefe de Sucursal','jefe1','jefe1@pegaso.com',NULL,'$2y$12$Kb.iNN7mQlKGYzsRaOyxn.4zbSVjmxI7LGQkhrFBUS31AOqrn/Sl.','jefe',NULL,'2026-09-16 02:13:09','2026-09-16 02:13:09',NULL,'Bolivia Central','Jefe de Sucursal','Administrador General');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `modelo` varchar(255) DEFAULT NULL,
  `anio` int(11) DEFAULT NULL,
  `placa` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `precio` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado` varchar(255) NOT NULL DEFAULT 'Disponible',
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,2,'2026-07-03 09:26:39','2026-07-03 10:04:01','Toyota','Rush',2024,NULL,'rojo',124.00,'Vendido','buena para viajes largos','vehiculos/4o9fZ7hn4MoTEqWVhr2oDtZWmGwMqiNvn4Tb5xCF.jpg'),(2,5,'2026-07-03 09:38:47','2026-07-03 09:38:47','keyton','x80',2025,NULL,'cafe',125.00,'Disponible','para qye puedas andar donde tu quieras','vehiculos/CMcwwDzmqrPFPYZ37L5GqdQGUTJU42JYMNraZXWp.jpg'),(3,5,'2026-07-03 09:40:04','2026-09-10 00:27:02','susuki','alto',2024,NULL,'blanco',99000.00,'Vendido','economica como te gusta','vehiculos/9S6ntKmblv8jD5xp2sAxU6wBH8nMCcqxFKqtjYjC.jpg'),(4,18,'2026-09-10 00:22:27','2026-09-10 00:22:27','toyota ruh','2026',2026,'67','rojo',252000.00,'Disponible','es bueno','vehiculos/RtaT8pl6qJ5DPv9ixf8ziTLfvOcXpoQ4hyO6z1SY.jpg'),(5,18,'2026-09-10 00:23:01','2026-09-10 00:23:01','toyota ruh','Rush',2026,'67','rojo',252000.00,'Disponible','es bueno','vehiculos/GO9KSylRIDdoTLXmWdxoQyPX5MVVgsWm5lbiByE0.jpg'),(6,18,'2026-09-10 00:23:06','2026-09-10 00:23:06','toyota ruh','Rush',2026,'67','rojo',252000.00,'Disponible','es bueno','vehiculos/3X2a3VpK6KrVZ9UraPcam7plRomLYIEtFarzquec.jpg'),(7,18,'2026-09-10 00:23:16','2026-09-10 00:23:16','toyota ruh','Rush',2026,'67','rojo',25200.00,'Disponible','es bueno','vehiculos/ZHqEplqLD1W9vsAk1MRmxp3kpTXbxWtqYmrXKgZa.jpg'),(8,18,'2026-09-10 00:23:30','2026-09-10 00:23:30','toyota ruh','2026',2026,'67','rojo',2520.00,'Disponible','es bueno','vehiculos/in05Ssb7hBLEljN7bqFxmvt5qmT7pNOqEpFBhy2y.jpg'),(9,18,'2026-09-10 00:23:37','2026-09-10 00:23:37','toyota ruh','2026',2026,'67','rojo',250.00,'Disponible','es bueno','vehiculos/tArWzf32R6XJjIRp8LTBKiqjC6CvAu2z99bUcGhM.jpg'),(10,18,'2026-09-10 00:24:10','2026-09-10 00:24:10','toyota ruh','2026',2026,'67','rojo',250.00,'Disponible','es bueno','vehiculos/WTSYRKUySXQf1nnjFEED4z0Ax5kLxNTDouOPRnD4.jpg'),(11,18,'2026-09-10 00:28:54','2026-09-16 02:13:49','Toyota','Corolla',2024,NULL,NULL,85000.00,'Disponible',NULL,'vehiculos/625544312_1430659041905857_8838933409478208084_n.jpg'),(12,18,'2026-09-10 00:30:36','2026-09-10 00:30:36','toyota','2026',2026,'67','rojo',25000.00,'Disponible','disponible','vehiculos/FxOI5tJoTUe526zm2tFd8VHiHHfdO0ldIoucyBT4.jpg'),(13,18,'2026-09-10 00:59:38','2026-09-10 00:59:38','toyota','rush',2026,'67','rojo',250000.00,'Disponible','familiar amolio','vehiculos/kgnglhLYwPh6zJ86dSsLgpDuBeTeiBW4eWgezP3X.jpg');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-15 22:52:43
