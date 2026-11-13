-- ==========================================================
-- ORDERPRINTEX — Full Production Database Dump for Hostinger
-- Compatible with: MySQL 5.7+ / 8.0+ / MariaDB 10.3+ / phpMyAdmin
-- Default Charset: utf8mb4 (utf8mb4_unicode_ci)
-- Generated on: 2026-11-13
-- NOTE: Do NOT create database manually in SQL; select your Hostinger DB in phpMyAdmin and click Import.
-- ==========================================================

SET NAMES utf8mb4;
SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT;
SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS;
SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION;
SET @OLD_TIME_ZONE=@@TIME_ZONE;
SET TIME_ZONE='+00:00';
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0;
SET AUTOCOMMIT = 0;
START TRANSACTION;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_log_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=284 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES
(1,3,'user_login','User logged in successfully.','127.0.0.1','2026-11-03 00:59:45'),
(2,3,'user_logout','User logged out.','127.0.0.1','2026-11-03 01:07:46'),
(3,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-03 01:10:00'),
(4,1,'user_logout','User logged out.','127.0.0.1','2026-11-03 01:11:30'),
(5,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-03 01:40:43'),
(6,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-05777 for arb@gmail.com','127.0.0.1','2026-11-03 09:53:22'),
(7,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-04 23:30:48'),
(8,1,'delete_enquiry','Deleted enquiry OP-ENQ-2026-05777','127.0.0.1','2026-11-04 23:32:51'),
(9,1,'create_blog_post','Published new article: Complete Guide to Business Card Printing','127.0.0.1','2026-11-04 23:37:12'),
(10,1,'create_blog_post','Published new article: sd','127.0.0.1','2026-11-04 23:39:33'),
(12,1,'user_login','User logged in successfully.','::1','2026-11-05 05:30:14'),
(13,1,'user_login','User logged in successfully.','::1','2026-11-05 05:30:37'),
(14,1,'update_product','Updated product: Sheet (SKU: DTF-001)','127.0.0.1','2026-11-05 05:33:09'),
(15,1,'update_product','Updated product: Sheet (SKU: DTF-001)','127.0.0.1','2026-11-05 05:33:14'),
(16,1,'delete_enquiry','Deleted enquiry OP-ENQ-2026-51989','127.0.0.1','2026-11-05 05:34:28'),
(17,1,'delete_product','Deleted product: Sheet','127.0.0.1','2026-11-05 05:35:19'),
(18,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 05:48:34'),
(19,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 05:48:52'),
(20,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 05:49:13'),
(21,1,'user_login','User logged in successfully.','::1','2026-11-05 05:53:24'),
(22,1,'user_login','User logged in successfully.','::1','2026-11-05 05:55:44'),
(23,1,'user_login','User logged in successfully.','::1','2026-11-05 06:10:10'),
(24,1,'user_login','User logged in successfully.','::1','2026-11-05 06:12:06'),
(25,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:12:06'),
(26,NULL,'seo_sync','Synchronized sitemap.xml (38 URLs) and llms.txt successfully.','::1','2026-11-05 06:12:06'),
(27,1,'create_product','Created new product: E2E Automated Test Polo Shirt (SKU: E2E-TEST-POLO-2585)','::1','2026-11-05 06:12:06'),
(28,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:12:06'),
(29,1,'delete_product','Deleted product: E2E Automated Test Polo Shirt','::1','2026-11-05 06:12:06'),
(30,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:12:06'),
(31,1,'user_login','User logged in successfully.','::1','2026-11-05 06:12:59'),
(32,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:12:59'),
(33,NULL,'seo_sync','Synchronized sitemap.xml (38 URLs) and llms.txt successfully.','::1','2026-11-05 06:12:59'),
(34,1,'create_product','Created new product: E2E Automated Test Polo Shirt (SKU: E2E-TEST-POLO-2086)','::1','2026-11-05 06:12:59'),
(35,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:12:59'),
(36,1,'delete_product','Deleted product: E2E Automated Test Polo Shirt','::1','2026-11-05 06:12:59'),
(37,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:12:59'),
(38,1,'user_login','User logged in successfully.','::1','2026-11-05 06:15:59'),
(39,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:15:59'),
(40,NULL,'seo_sync','Synchronized sitemap.xml (38 URLs) and llms.txt successfully.','::1','2026-11-05 06:15:59'),
(41,1,'create_product','Created new product: E2E Automated Test Polo Shirt (SKU: E2E-TEST-POLO-7642)','::1','2026-11-05 06:15:59'),
(42,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:16:00'),
(43,1,'delete_product','Deleted product: E2E Automated Test Polo Shirt','::1','2026-11-05 06:16:00'),
(44,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:16:00'),
(45,1,'user_login','User logged in successfully.','::1','2026-11-05 06:16:40'),
(46,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:16:41'),
(47,NULL,'seo_sync','Synchronized sitemap.xml (38 URLs) and llms.txt successfully.','::1','2026-11-05 06:16:41'),
(48,1,'create_product','Created new product: E2E Automated Test Polo Shirt (SKU: E2E-TEST-POLO-7377)','::1','2026-11-05 06:16:41'),
(49,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:16:41'),
(50,1,'delete_product','Deleted product: E2E Automated Test Polo Shirt','::1','2026-11-05 06:16:41'),
(51,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:16:41'),
(52,1,'user_login','User logged in successfully.','::1','2026-11-05 06:17:21'),
(53,1,'user_login','User logged in successfully.','::1','2026-11-05 06:17:42'),
(54,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:17:42'),
(55,NULL,'seo_sync','Synchronized sitemap.xml (38 URLs) and llms.txt successfully.','::1','2026-11-05 06:17:42'),
(56,1,'create_product','Created new product: E2E Automated Test Polo Shirt (SKU: E2E-TEST-POLO-5203)','::1','2026-11-05 06:17:42'),
(57,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:17:42'),
(58,1,'delete_product','Deleted product: E2E Automated Test Polo Shirt','::1','2026-11-05 06:17:42'),
(59,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:17:42'),
(60,1,'user_login','User logged in successfully.','::1','2026-11-05 06:18:04'),
(61,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:18:04'),
(62,NULL,'seo_sync','Synchronized sitemap.xml (38 URLs) and llms.txt successfully.','::1','2026-11-05 06:18:04'),
(63,1,'create_product','Created new product: E2E Automated Test Polo Shirt (SKU: E2E-TEST-POLO-5753)','::1','2026-11-05 06:18:04'),
(64,1,'update_order','Updated order ORD-2026-6F140 to quality_check','::1','2026-11-05 06:18:04'),
(65,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:18:04'),
(66,1,'delete_product','Deleted product: E2E Automated Test Polo Shirt','::1','2026-11-05 06:18:04'),
(67,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:18:04'),
(68,1,'user_login','User logged in successfully.','::1','2026-11-05 06:18:30'),
(69,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:18:30'),
(70,NULL,'seo_sync','Synchronized sitemap.xml (38 URLs) and llms.txt successfully.','::1','2026-11-05 06:18:30'),
(71,1,'create_product','Created new product: E2E Automated Test Polo Shirt (SKU: E2E-TEST-POLO-2514)','::1','2026-11-05 06:18:30'),
(72,1,'update_order','Updated order ORD-2026-6F140 to quality_check','::1','2026-11-05 06:18:31'),
(73,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:18:31'),
(74,1,'delete_product','Deleted product: E2E Automated Test Polo Shirt','::1','2026-11-05 06:18:31'),
(75,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:18:31'),
(76,1,'user_login','User logged in successfully.','::1','2026-11-05 06:19:09'),
(77,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 06:38:01'),
(78,1,'update_product','Updated product: Transfer Sheet (SKU: UV-001)','127.0.0.1','2026-11-05 06:38:01'),
(79,1,'user_login','User logged in successfully.','::1','2026-11-05 06:41:25'),
(80,1,'user_login','User logged in successfully.','::1','2026-11-05 06:41:37'),
(81,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:41:37'),
(82,NULL,'seo_sync','Synchronized sitemap.xml (38 URLs) and llms.txt successfully.','::1','2026-11-05 06:41:37'),
(83,1,'create_product','Created new product: E2E Automated Test Polo Shirt (SKU: E2E-TEST-POLO-7011)','::1','2026-11-05 06:41:37'),
(84,1,'update_order','Updated order ORD-2026-6F140 to quality_check','::1','2026-11-05 06:41:37'),
(85,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:41:37'),
(86,1,'delete_product','Deleted product: E2E Automated Test Polo Shirt','::1','2026-11-05 06:41:37'),
(87,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','::1','2026-11-05 06:41:37'),
(88,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 07:12:43'),
(89,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 07:14:57'),
(90,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 07:16:33'),
(91,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 07:30:57'),
(92,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 07:35:51'),
(93,1,'submit_enquiry','Created enquiry OP-ENQ-2026-14589 for admin@orderprintex.com','127.0.0.1','2026-11-05 07:42:14'),
(94,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 07:44:23'),
(95,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 07:45:05'),
(96,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 07:45:22'),
(97,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 07:50:53'),
(98,1,'update_settings','Updated studio contact, sticky top bar, social and SEO settings','127.0.0.1','2026-11-05 07:50:53'),
(99,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 07:54:43'),
(100,1,'update_product','Updated product: Transfer Sheet (SKU: UV-001)','127.0.0.1','2026-11-05 07:54:43'),
(101,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 07:58:39'),
(102,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 07:59:13'),
(103,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 07:59:17'),
(104,4,'user_register','Customer registered account.','127.0.0.1','2026-11-05 07:59:47'),
(105,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 08:00:34'),
(106,4,'submit_enquiry','Created enquiry OP-ENQ-2026-51E66 for abcd@gmail.com','127.0.0.1','2026-11-05 08:00:38'),
(107,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 08:04:39'),
(108,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 08:52:42'),
(109,1,'delete_enquiry','Deleted enquiry ENQ-2026-F74DF','127.0.0.1','2026-11-05 08:56:39'),
(110,1,'delete_enquiry','Deleted enquiry ENQ-2026-03CE4','127.0.0.1','2026-11-05 08:56:47'),
(111,2,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 08:58:25'),
(112,2,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:00:49'),
(113,2,'approve_quotation','Customer approved quotation QP-2026-225D1','127.0.0.1','2026-11-05 09:00:50'),
(114,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:01:21'),
(115,2,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:01:27'),
(116,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:01:27'),
(117,1,'delete_enquiry','Deleted enquiry OP-ENQ-5049B','127.0.0.1','2026-11-05 09:02:23'),
(118,2,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:02:49'),
(119,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:02:49'),
(120,2,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:03:04'),
(121,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:03:05'),
(122,2,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:05:30'),
(123,2,'approve_quotation','Customer approved quotation QP-2026-CAADE','127.0.0.1','2026-11-05 09:05:30'),
(124,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:05:31'),
(125,2,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:06:08'),
(126,2,'approve_quotation','Customer approved quotation QP-2026-CAADE','127.0.0.1','2026-11-05 09:06:09'),
(127,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:06:09'),
(128,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 09:20:47'),
(129,1,'update_category','Updated category: DTF Gang Sheets','127.0.0.1','2026-11-05 09:20:47'),
(130,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:22:29'),
(131,NULL,'seo_sync','Synchronized sitemap.xml (38 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 09:22:29'),
(132,1,'create_category','Created category: Automated Test Category','127.0.0.1','2026-11-05 09:22:29'),
(133,NULL,'seo_sync','Synchronized sitemap.xml (37 URLs) and llms.txt successfully.','127.0.0.1','2026-11-05 09:22:29'),
(134,1,'delete_category','Deleted category: DTF Gang Sheets','127.0.0.1','2026-11-05 09:22:29'),
(135,2,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:22:36'),
(136,2,'approve_quotation','Customer approved quotation QP-2026-CAADE','127.0.0.1','2026-11-05 09:22:36'),
(137,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:22:36'),
(138,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:25:01'),
(139,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:26:39'),
(140,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:26:52'),
(141,NULL,'seo_sync_all','Synchronized sitemap.xml (40 URLs), llms.txt, schema.json, and ORDERPRINTEX.md successfully.','127.0.0.1','2026-11-05 09:36:06'),
(142,NULL,'seo_sync_all','Synchronized sitemap.xml (40 URLs), llms.txt, schema.json, and ORDERPRINTEX.md successfully.','127.0.0.1','2026-11-05 09:37:14'),
(143,NULL,'seo_sync_all','Synchronized sitemap.xml (40 URLs), llms.txt, schema.json, and ORDERPRINTEX.md successfully.','127.0.0.1','2026-11-05 09:37:23'),
(144,NULL,'seo_sync_all','Synchronized sitemap.xml (40 URLs), llms.txt, schema.json, and ORDERPRINTEX.md successfully.','127.0.0.1','2026-11-05 09:37:41'),
(145,NULL,'seo_sync_all','Synchronized sitemap.xml (40 URLs), llms.txt, schema.json, and ORDERPRINTEX.md successfully.','127.0.0.1','2026-11-05 09:37:43'),
(146,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:37:57'),
(147,NULL,'seo_sync_all','Synchronized sitemap.xml (40 URLs), llms.txt, schema.json, and ORDERPRINTEX.md successfully.','127.0.0.1','2026-11-05 09:37:59'),
(148,NULL,'seo_sync_all','Synchronized sitemap.xml (40 URLs), llms.txt, schema.json, and ORDERPRINTEX.md successfully.','127.0.0.1','2026-11-05 09:38:23'),
(149,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:40:30'),
(150,1,'submit_enquiry','Created enquiry OP-ENQ-2026-A08FF for admin@orderprintex.com','127.0.0.1','2026-11-05 09:45:31'),
(151,1,'create_quotation','Created quotation QP-2026-5B845 for OrderPrintex Administrator','127.0.0.1','2026-11-05 09:45:58'),
(152,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:54:42'),
(153,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 09:55:41'),
(154,1,'create_admin_user','Created new staff account for teststaff_1793872544654@orderprintex.com','127.0.0.1','2026-11-05 09:55:45'),
(155,1,'create_admin_user','Created new staff account for aditya@gmail.com','127.0.0.1','2026-11-05 10:01:41'),
(156,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 10:02:05'),
(157,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 10:08:32'),
(158,1,'create_personnel_user','Created new staff account for staff_unit_1793873314673@orderprintex.com','127.0.0.1','2026-11-05 10:08:34'),
(159,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 10:08:35'),
(160,7,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 10:08:36'),
(161,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 10:13:01'),
(162,1,'toggle_admin_status','Changed status of aditya@gmail.com to suspended','127.0.0.1','2026-11-05 10:13:22'),
(163,1,'create_personnel_user','Created new staff account for nani@gmail.com','127.0.0.1','2026-11-05 10:14:02'),
(164,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 10:14:13'),
(165,8,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 10:14:27'),
(166,8,'user_logout','User logged out.','127.0.0.1','2026-11-05 10:18:13'),
(167,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 10:18:33'),
(168,1,'toggle_admin_status','Changed status of nani@gmail.com to suspended','127.0.0.1','2026-11-05 10:20:12'),
(169,1,'toggle_admin_status','Changed status of nani@gmail.com to active','127.0.0.1','2026-11-05 10:20:23'),
(170,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:03:56'),
(171,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:22:05'),
(172,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:23:14'),
(173,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:24:24'),
(174,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:26:36'),
(175,1,'create_personnel','Provisioned new staff account for blog_lead_1793888800809@orderprintex.com (Content & Editorial)','127.0.0.1','2026-11-05 14:26:43'),
(176,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:26:44'),
(177,9,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:26:45'),
(178,9,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:26:47'),
(179,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:26:49'),
(180,1,'create_personnel','Provisioned new staff account for sales_lead_1793888810685@orderprintex.com (Sales & Prepress)','127.0.0.1','2026-11-05 14:26:52'),
(181,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:26:53'),
(182,10,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:26:54'),
(183,10,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:26:56'),
(184,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:26:57'),
(185,1,'reset_password','Reset security credentials for blog_lead_1793888800809@orderprintex.com','127.0.0.1','2026-11-05 14:26:59'),
(186,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:27:00'),
(187,9,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:27:01'),
(188,9,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:27:02'),
(189,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:27:03'),
(190,1,'toggle_status','Changed status of blog_lead_1793888800809@orderprintex.com to suspended','127.0.0.1','2026-11-05 14:27:05'),
(191,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:27:05'),
(192,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:27:07'),
(193,1,'toggle_status','Changed status of blog_lead_1793888800809@orderprintex.com to active','127.0.0.1','2026-11-05 14:27:09'),
(194,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:27:10'),
(195,9,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:27:11'),
(196,9,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:27:12'),
(197,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:27:13'),
(198,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:26'),
(199,1,'create_personnel','Provisioned new staff account for blog_lead_1793889030531@orderprintex.com (Content & Editorial)','127.0.0.1','2026-11-05 14:30:31'),
(200,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:30:32'),
(201,11,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:33'),
(202,11,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:30:35'),
(203,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:35'),
(204,1,'create_personnel','Provisioned new staff account for sales_lead_1793889036749@orderprintex.com (Sales & Prepress)','127.0.0.1','2026-11-05 14:30:37'),
(205,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:30:37'),
(206,12,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:38'),
(207,12,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:30:39'),
(208,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:40'),
(209,1,'reset_password','Reset security credentials for blog_lead_1793889030531@orderprintex.com','127.0.0.1','2026-11-05 14:30:42'),
(210,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:30:42'),
(211,11,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:43'),
(212,11,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:30:44'),
(213,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:45'),
(214,1,'toggle_status','Changed status of blog_lead_1793889030531@orderprintex.com to suspended','127.0.0.1','2026-11-05 14:30:47'),
(215,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:30:47'),
(216,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:50'),
(217,1,'toggle_status','Changed status of blog_lead_1793889030531@orderprintex.com to active','127.0.0.1','2026-11-05 14:30:51'),
(218,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:30:51'),
(219,11,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:52'),
(220,11,'user_logout','User logged out.','127.0.0.1','2026-11-05 14:30:53'),
(221,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:30:54'),
(222,1,'reset_password','Reset security credentials for admin@orderprintex.com','127.0.0.1','2026-11-05 14:31:51'),
(223,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:57:37'),
(224,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:57:44'),
(225,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:58:06'),
(226,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:59:34'),
(227,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 14:59:56'),
(228,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 15:00:54'),
(229,1,'create_personnel','Provisioned new staff account for staff_verify_1793890856816@orderprintex.com (Production & QA)','127.0.0.1','2026-11-05 15:00:57'),
(230,13,'verify_email','Employee verified email address: staff_verify_1793890856816@orderprintex.com','127.0.0.1','2026-11-05 15:00:58'),
(231,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 15:00:58'),
(232,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 15:00:59'),
(233,1,'toggle_status','Changed status of staff_verify_1793890856816@orderprintex.com to suspended','127.0.0.1','2026-11-05 15:01:01'),
(234,1,'toggle_status','Changed status of staff_verify_1793890856816@orderprintex.com to active','127.0.0.1','2026-11-05 15:01:02'),
(235,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 15:01:35'),
(236,1,'create_personnel','Provisioned new staff account for staff_verify_1793890897923@orderprintex.com (Production & QA)','127.0.0.1','2026-11-05 15:01:38'),
(237,14,'verify_email','Employee verified email address: staff_verify_1793890897923@orderprintex.com','127.0.0.1','2026-11-05 15:01:39'),
(238,1,'user_logout','User logged out.','127.0.0.1','2026-11-05 15:01:40'),
(239,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 15:01:41'),
(240,1,'toggle_status','Changed status of staff_verify_1793890897923@orderprintex.com to suspended','127.0.0.1','2026-11-05 15:01:43'),
(241,1,'toggle_status','Changed status of staff_verify_1793890897923@orderprintex.com to active','127.0.0.1','2026-11-05 15:01:44'),
(242,1,'edit_personnel','Updated personnel profile and permissions for aditya@gmail.com','127.0.0.1','2026-11-05 15:24:52'),
(243,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-21B84 for testother@example.com','127.0.0.1','2026-11-05 15:25:18'),
(244,1,'toggle_status','Changed status of aditya@gmail.com to active','127.0.0.1','2026-11-05 15:25:22'),
(245,1,'toggle_status','Changed status of aditya@gmail.com to suspended','127.0.0.1','2026-11-05 15:25:25'),
(246,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-47AF3 for testother@example.com','127.0.0.1','2026-11-05 15:26:33'),
(247,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 15:26:36'),
(248,1,'create_personnel','Provisioned new staff account for staff_dept_1793892397792@orderprintex.com (International Logistics Desk 1793892397792)','127.0.0.1','2026-11-05 15:26:38'),
(249,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-D0057 for testother@example.com','127.0.0.1','2026-11-05 15:27:27'),
(250,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-05 15:27:30'),
(251,1,'create_personnel','Provisioned new staff account for staff_dept_1793892451530@orderprintex.com (International Logistics Desk 1793892451530)','127.0.0.1','2026-11-05 15:27:31'),
(252,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 04:38:19'),
(253,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-BCF13 for artwork_client@example.com','127.0.0.1','2026-11-12 05:01:22'),
(254,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-8AAAF for artwork_client@example.com','127.0.0.1','2026-11-12 05:02:13'),
(255,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:02:16'),
(256,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-0AC26 for artwork_client@example.com','127.0.0.1','2026-11-12 05:03:29'),
(257,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:03:32'),
(258,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-842E9 for artwork_client@example.com','127.0.0.1','2026-11-12 05:04:33'),
(259,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:04:35'),
(260,1,'update_enquiry','Updated enquiry ID #14 status to under_review','127.0.0.1','2026-11-12 05:04:46'),
(261,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-677FD for artwork_client@example.com','127.0.0.1','2026-11-12 05:06:42'),
(262,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:06:45'),
(263,1,'update_enquiry','Updated enquiry ID #15 status to under_review','127.0.0.1','2026-11-12 05:06:57'),
(264,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:07:21'),
(265,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:07:59'),
(266,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:08:16'),
(267,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-10C55 for artwork_client@example.com','127.0.0.1','2026-11-12 05:08:26'),
(268,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:08:28'),
(269,1,'update_enquiry','Updated enquiry ID #16 status to under_review','127.0.0.1','2026-11-12 05:08:39'),
(270,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:17:50'),
(271,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:25:47'),
(272,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:33:54'),
(273,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:35:36'),
(274,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-4765B for artwork_client@example.com','127.0.0.1','2026-11-12 05:38:15'),
(275,NULL,'submit_enquiry','Created enquiry OP-ENQ-2026-38F3F for artwork_client@example.com','127.0.0.1','2026-11-12 05:38:28'),
(276,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:38:30'),
(277,1,'update_enquiry','Updated enquiry ID #18 status to under_review','127.0.0.1','2026-11-12 05:38:41'),
(278,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 05:39:34'),
(279,1,'user_logout','User logged out.','127.0.0.1','2026-11-12 06:00:18'),
(280,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 06:00:44'),
(281,1,'user_logout','User logged out.','127.0.0.1','2026-11-12 06:00:50'),
(282,1,'user_login','User logged in successfully.','127.0.0.1','2026-11-12 07:33:25'),
(283,NULL,'seo_sync_all','Synchronized sitemap.xml (41 URLs), llms.txt, schema.json, and ORDERPRINTEX.md successfully.','127.0.0.1','2026-11-12 07:35:13');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_permissions`
--

DROP TABLE IF EXISTS `admin_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(80) NOT NULL,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES
(1,'blog.view','blog','view','View Blog Articles','View published and draft blog posts'),
(2,'blog.create','blog','create','Create Blog Articles','Write new blog posts and drafts'),
(3,'blog.edit','blog','edit','Edit Blog Articles','Edit existing blog posts and content'),
(4,'blog.publish','blog','publish','Publish / Unpublish Blog','Toggle blog post published status'),
(5,'blog.delete','blog','delete','Delete Blog Articles','Delete blog posts permanently'),
(6,'enquiries.view','enquiries','view','View Customer Enquiries','View customer enquiries and specifications'),
(7,'enquiries.status','enquiries','manage_status','Manage Enquiry Status','Update status of enquiries (quoted, review, etc.)'),
(8,'enquiries.notes','enquiries','edit','Add Internal Notes','Attach internal staff notes to enquiries'),
(9,'enquiries.export','enquiries','export','Export Enquiries','Export enquiry data and customer reports'),
(10,'enquiries.delete','enquiries','delete','Delete Enquiries','Delete spam or obsolete enquiries'),
(11,'quotations.view','quotations','view','View Quotations','View itemized quotation desk and history'),
(12,'quotations.create','quotations','create','Create Quotations','Draft new commercial printing proposals'),
(13,'quotations.edit','quotations','edit','Edit Quotations','Modify line items, quantities, and pricing'),
(14,'quotations.send','quotations','publish','Send Quotations to Client','Dispatch quotations to client portal and email'),
(15,'quotations.status','quotations','manage_status','Manage Quote Status','Update quote lifecycle status (approved/rejected)'),
(16,'quotations.delete','quotations','delete','Delete Quotations','Remove draft or obsolete quotations'),
(17,'customers.view','customers','view','View Customer Accounts','View customer profiles, contacts, and order logs'),
(18,'customers.edit','customers','edit','Edit Customer Accounts','Update customer profile information or status'),
(19,'products.view','products','view','View Products','Browse product catalog and specifications'),
(20,'products.create','products','create','Create Products','Add new printing products to catalog'),
(21,'products.edit','products','edit','Edit Products','Update descriptions, pricing, and images'),
(22,'products.delete','products','delete','Delete Products','Remove products from catalog'),
(23,'products.variations','products','manage_settings','Manage Variations & SKU','Configure variations, attributes, and SKU rules'),
(24,'categories.view','categories','view','View Categories','Browse product categories'),
(25,'categories.create','categories','create','Create Categories','Add new product categories'),
(26,'categories.edit','categories','edit','Edit Categories','Modify category names, slugs, and sequence'),
(27,'categories.delete','categories','delete','Delete Categories','Delete product categories'),
(28,'portfolio.view','portfolio','view','View Portfolio Projects','Browse studio portfolio projects'),
(29,'portfolio.create','portfolio','create','Create Portfolio Projects','Upload new print specimens and projects'),
(30,'portfolio.edit','portfolio','edit','Edit Portfolio Projects','Update project descriptions, tags, and photos'),
(31,'portfolio.publish','portfolio','publish','Publish / Unpublish Project','Toggle portfolio visibility'),
(32,'portfolio.delete','portfolio','delete','Delete Portfolio Projects','Remove portfolio specimens'),
(33,'services.view','services','view','View Printing Services','Browse commercial printing services'),
(34,'services.create','services','create','Create Printing Services','Add new printing services'),
(35,'services.edit','services','edit','Edit Printing Services','Update service capabilities and equipment'),
(36,'services.delete','services','delete','Delete Printing Services','Remove printing services'),
(37,'reviews.view','reviews','view','View Customer Reviews','Browse client testimonials and reviews'),
(38,'reviews.approve','reviews','approve','Approve / Reject Reviews','Moderate and publish customer reviews'),
(39,'reviews.delete','reviews','delete','Delete Reviews','Permanently remove reviews'),
(40,'content.view','content','view','View Website Content','View CMS homepage and landing pages'),
(41,'content.edit','content','edit','Edit Website Content','Update CMS text, announcements, and banners'),
(42,'seo.view','seo','view','View SEO & Discovery','View sitemap, llms.txt, and schema status'),
(43,'seo.manage','seo','manage_settings','Manage SEO Synchronization','Run SEO sync and modify metadata'),
(44,'settings.view','settings','view','View Website Settings','View contact, company, and studio settings'),
(45,'settings.edit','settings','manage_settings','Edit Website Settings','Update company details and studio configuration'),
(46,'personnel.view','personnel','view','View Admin Personnel','Browse staff directory and assigned roles'),
(47,'personnel.manage','personnel','manage_settings','Manage Personnel & Roles','Create, edit, reset passwords, and assign roles'),
(48,'activity.view','activity','view','View Security & Logs','Inspect system audit trails and login events');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role_permissions`
--

DROP TABLE IF EXISTS `admin_role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES
(1,1),
(1,2),
(1,3),
(1,4),
(1,5),
(1,6),
(1,7),
(1,8),
(1,9),
(1,10),
(1,11),
(1,12),
(1,13),
(1,14),
(1,15),
(1,16),
(1,17),
(1,18),
(1,19),
(1,20),
(1,21),
(1,22),
(1,23),
(1,24),
(1,25),
(1,26),
(1,27),
(1,28),
(1,29),
(1,30),
(1,31),
(1,32),
(1,33),
(1,34),
(1,35),
(1,36),
(1,37),
(1,38),
(1,39),
(1,40),
(1,41),
(1,42),
(1,43),
(1,44),
(1,45),
(1,46),
(1,47),
(1,48),
(2,1),
(2,2),
(2,3),
(2,4),
(2,5),
(3,6),
(3,7),
(3,8),
(3,9),
(4,6),
(4,11),
(4,12),
(4,13),
(4,14),
(4,15),
(5,6),
(5,11),
(5,17),
(6,19),
(6,20),
(6,21),
(6,22),
(6,23),
(7,24),
(7,25),
(7,26),
(7,27),
(8,28),
(8,29),
(8,30),
(8,31),
(8,32),
(9,33),
(9,34),
(9,35),
(9,36),
(10,37),
(10,38),
(10,39),
(11,40),
(11,41),
(12,42),
(12,43);
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_roles`
--

DROP TABLE IF EXISTS `admin_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(60) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES
(1,'super_admin','Super Admin','Executive Administration','Full administrative access across all modules, personnel management, website settings, and security audit logs.',1,'2026-11-05 14:11:26'),
(2,'blog_manager','Blog Manager','Content & Editorial','Manage editorial articles, drafting, editing, publishing, and blog media assets.',1,'2026-11-05 14:11:26'),
(3,'enquiry_executive','Enquiry Executive','Sales & Customer Inquiries','Review incoming client print specifications, update enquiry status, add internal notes, and follow up.',1,'2026-11-05 14:11:26'),
(4,'quotation_executive','Quotation Executive','Commercial Prepress & Pricing','Draft, calculate, itemize, and issue formal quotations and pricing proposals to customers.',1,'2026-11-05 14:11:26'),
(5,'customer_support','Customer Support','Client Services','Assist clients with account profiles, review enquiries, and check linked quotation statuses.',1,'2026-11-05 14:11:26'),
(6,'product_manager','Product Manager','Catalogue & Merchandise','Manage printing products, custom specifications, SKU codes, pricing, and variations.',1,'2026-11-05 14:11:26'),
(7,'category_manager','Category Manager','Catalogue Structure','Organize product collections, category slugs, descriptive content, and display sequences.',1,'2026-11-05 14:11:26'),
(8,'portfolio_manager','Portfolio Manager','Creative Studio Showcase','Curate studio printing portfolio, upload print specimen galleries, and manage project spotlights.',1,'2026-11-05 14:11:26'),
(9,'services_manager','Printing Services Manager','Production & Capabilities','Manage commercial printing services, machinery capabilities, and process overviews.',1,'2026-11-05 14:11:26'),
(10,'reviews_manager','Reviews Manager','Brand Reputation','Moderate, approve, and curate verified customer testimonials and print ratings.',1,'2026-11-05 14:11:26'),
(11,'content_manager','Website Content Manager','Marketing & CMS','Manage homepage sections, announcements, editorial text, and public landing pages.',1,'2026-11-05 14:11:26'),
(12,'seo_manager','Website / SEO Manager','Search & AI Discovery','Manage sitemap.xml, llms.txt, schema metadata, and SEO synchronization.',1,'2026-11-05 14:11:26');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_user_permissions`
--

DROP TABLE IF EXISTS `admin_user_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `is_granted` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`user_id`,`permission_id`),
  KEY `user_id` (`user_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user_permissions`
--

LOCK TABLES `admin_user_permissions` WRITE;
/*!40000 ALTER TABLE `admin_user_permissions` DISABLE KEYS */;
INSERT INTO `admin_user_permissions` VALUES
(10,19,1),
(12,19,1);
/*!40000 ALTER TABLE `admin_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_user_roles`
--

DROP TABLE IF EXISTS `admin_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user_roles`
--

LOCK TABLES `admin_user_roles` WRITE;
/*!40000 ALTER TABLE `admin_user_roles` DISABLE KEYS */;
INSERT INTO `admin_user_roles` VALUES
(1,1),
(6,1),
(9,2),
(10,3),
(10,4),
(11,2),
(12,3),
(12,4),
(13,3),
(14,3);
/*!40000 ALTER TABLE `admin_user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcement_bar`
--

DROP TABLE IF EXISTS `announcement_bar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcement_bar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `link_text` varchar(100) DEFAULT NULL,
  `badge_text` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `message_text` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement_bar`
--

LOCK TABLES `announcement_bar` WRITE;
/*!40000 ALTER TABLE `announcement_bar` DISABLE KEYS */;
INSERT INTO `announcement_bar` VALUES
(1,'','services.php',NULL,NULL,1,1,'2026-11-05 05:26:45','Free Express Delivery on Bulk Orders Across India'),
(2,'','get-quote.php',NULL,NULL,1,2,'2026-11-05 05:26:45','Upload Your Artwork — Free Prepress & Print Proof Analysis'),
(3,'','contact.php',NULL,NULL,1,3,'2026-11-05 05:26:45','Need Bulk DTF or UV DTF Transfers? Get Factory Direct Pricing');
/*!40000 ALTER TABLE `announcement_bar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artwork_proofs`
--

DROP TABLE IF EXISTS `artwork_proofs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `artwork_proofs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `proof_file_path` varchar(255) NOT NULL,
  `version` int(11) DEFAULT 1,
  `customer_approval` enum('pending','approved','revision_requested') DEFAULT 'pending',
  `customer_comments` text DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp(),
  `approved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `artwork_proofs_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artwork_proofs`
--

LOCK TABLES `artwork_proofs` WRITE;
/*!40000 ALTER TABLE `artwork_proofs` DISABLE KEYS */;
/*!40000 ALTER TABLE `artwork_proofs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_values`
--

DROP TABLE IF EXISTS `attribute_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(150) NOT NULL,
  `color_code` varchar(50) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  CONSTRAINT `attribute_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_values`
--

LOCK TABLES `attribute_values` WRITE;
/*!40000 ALTER TABLE `attribute_values` DISABLE KEYS */;
INSERT INTO `attribute_values` VALUES
(1,1,'Small (S)',NULL,1),
(2,1,'Medium (M)',NULL,2),
(3,1,'Large (L)',NULL,3),
(4,1,'X-Large (XL)',NULL,4),
(5,1,'XXL',NULL,5),
(6,1,'500ml',NULL,6),
(7,1,'750ml',NULL,7),
(8,1,'1000ml (1L)',NULL,8),
(9,1,'2\" x 6\"',NULL,9),
(10,1,'2\" x 7\"',NULL,10),
(11,1,'2\" x 2\" (Pack of 50)',NULL,11),
(12,1,'3\" x 3\" (Pack of 50)',NULL,12),
(13,1,'1 Meter Roll',NULL,13),
(14,1,'2 Meter Roll',NULL,14),
(15,1,'5 Meter Roll',NULL,15),
(16,1,'A3 Sheet (10 Sheets)',NULL,16),
(17,1,'A3 Sheet (25 Sheets)',NULL,17),
(18,2,'Phantom Black','#18181B',1),
(19,2,'Bright White','#FFFFFF',2),
(20,2,'Midnight Navy','#14233C',3),
(21,2,'Crimson Red','#A83E3C',4),
(22,2,'Steel Grey','#6B7280',5),
(23,3,'Rainbow Holographic Foil',NULL,1),
(24,3,'Waterproof Matte Vinyl',NULL,2),
(25,3,'Clear Transparent Gloss',NULL,3),
(26,3,'350 GSM Velvet Soft-Touch',NULL,4),
(27,3,'400 GSM Gold Foil Accented',NULL,5);
/*!40000 ALTER TABLE `attribute_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attributes`
--

LOCK TABLES `attributes` WRITE;
/*!40000 ALTER TABLE `attributes` DISABLE KEYS */;
INSERT INTO `attributes` VALUES
(1,'Size','size','2026-11-02 23:43:53'),
(2,'Colour','colour','2026-11-02 23:43:53'),
(3,'Material / Finish','material','2026-11-02 23:43:53');
/*!40000 ALTER TABLE `attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES
(1,'Printing Basics','printing-basics','In-depth commercial and technical printing tutorials','2026-11-02 03:25:04'),
(2,'T-Shirts & Apparel','t-shirts-apparel','DTF transfers, screen printing, and streetwear fashion tips','2026-11-02 03:25:04'),
(3,'Corporate & Branding','corporate-branding','Promotional merchandise, corporate gifting, and unboxing ideas','2026-11-02 03:25:04'),
(4,'Packaging & Labels','packaging-labels','Packaging boxes, adhesive stickers, and retail presentation','2026-11-02 03:25:04');
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'OrderPrintex Editorial Team',
  `read_time` varchar(50) DEFAULT '5 min read',
  `status` enum('published','draft') DEFAULT 'published',
  `is_sample` tinyint(1) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
INSERT INTO `blog_posts` VALUES
(1,1,'How DTF Printing Technology Is Transforming the Custom Apparel Industry','dtf-printing-technology-custom-apparel','Ask anyone who has tried to start a small clothing brand a few years back, and you will hear the same story. The designs were ready, the idea was solid, but the printing costs ate everything. Screen printing wanted big quantities, and most other options either looked dull or washed out after a few cycles.','<p>Ask anyone who has tried to start a small clothing brand a few years back, and you will hear the same story. The designs were ready, the idea was solid, but the printing costs ate everything. Screen printing wanted big quantities, and most other options either looked dull or washed out after a few cycles.</p>\n<p>DTF printing technology has quietly changed that story. Today, a college society, a cafe owner or a first-time label can order a small batch of full-colour T-shirts and get a result that looks professional. Let’s look at how this happened and why the whole custom apparel industry is paying attention.</p>\n<h2>What Is DTF Printing?</h2>\n<p>If you are wondering what DTF printing is, the name says most of it. DTF stands for Direct to Film. The design is first printed onto a thin transfer film using special water-based inks. While the ink is still wet, a fine adhesive powder is spread over it. The film is then heated so the powder melts and sets, and finally it is pressed onto the garment with a heat press. Once you peel the film away, the design stays on the fabric.</p>\n<p>There are no screens to make and no complicated pre-treatment on the garment. The print is created on the film, and the garment simply receives it. That one difference is behind almost every advantage we talk about below.</p>\n<h2>Why DTF Printing for Custom Apparel Is Growing So Fast</h2>\n<h3>It does not care much about fabric</h3>\n<p>Some methods work beautifully on 100% cotton and struggle with anything else. DTF printing on T-shirts, hoodies, polos, jackets and even polyester sportswear works well because the adhesive does the bonding. This matters a lot for a printer, since customers rarely ask what their garment is made of. They just want the design to look right.</p>\n<h3>Colour is no longer a cost problem</h3>\n<p>Anyone who has priced a screen print knows that each additional colour adds to the bill. With DTF, a simple one-colour logo and a detailed artwork with shading and gradients take about the same effort. Photographs, fine lines and small text come out clean. For designers, this feels like being let out of a box.</p>\n<h3>Small orders finally make sense</h3>\n<p>This is the real shift. Because there is no screen setup, printing 10 or 25 pieces is practical. A new brand can test two or three designs before committing to a large run. A school club can print team shirts without ordering 100 extra. The risk drops, and more people feel confident enough to try.</p>\n<h3>Turnaround is faster</h3>\n<p>Without screens to prepare, jobs move quickly from the file to the finished piece. Many printers also keep ready transfers that can be pressed whenever a repeat order arrives. When an event date is fixed and cannot be moved, that speed is a real relief.</p>\n<h2>Benefits of DTF Printing at a Glance</h2>\n<p>If you only want the short version, here are the main benefits of DTF printing:</p>\n<ul>\n<li>Prints on cotton, polyester, blends and more</li>\n<li>Handles full-colour and detailed artwork easily</li>\n<li>Works for single pieces as well as bulk orders</li>\n<li>Soft, flexible finish that moves with the fabric</li>\n<li>Good wash durability when done with quality materials</li>\n<li>Quick setup and faster delivery</li>\n</ul>\n<p>One honest note: quality is not the same everywhere. The film, the ink, the powder and the pressing temperature all affect how long a print lasts. A cheap setup can crack or peel early, so the printer you choose matters as much as the technology.</p>\n<h2>DTF vs Screen Printing: Which One Should You Pick?</h2>\n<p>People often ask whether DTF is going to replace screen printing completely. Not really. Both still have a place.</p>\n<p>Screen printing is a good fit when you need thousands of pieces with one simple design, because the cost per piece falls sharply at very high volumes. The setup effort is worth it only when the quantity is huge.</p>\n<p>DTF printing wins almost everywhere else. If your design has many colours, if you need different designs in the same order, or if your quantity is somewhere between one and a few hundred, DTF usually gives better value and a quicker result. It also gives you more freedom to change artwork between orders without paying for new screens.</p>\n<p>So the smarter question is not which one is better, but which one suits your order. For most custom apparel printing today, that answer leans toward DTF.</p>\n<h2>How DTF Is Changing the Custom Apparel Business</h2>\n<p>New brands can start lighter. Instead of stocking hundreds of pieces and hoping they sell, founders can print in small lots, watch what sells and reorder only the winners. It is a much gentler way to start.</p>\n<p>Corporate and event orders are easier. Uniforms with different names, departments or sizes used to be a headache. DTF handles these variations within a single order without much fuss.</p>\n<p>Personalisation has become normal. Birthday T-shirts, couple prints, custom hoodies, name and number jerseys. These used to feel like luxury items. Now they are everyday requests.</p>\n<p>Smaller workshops can offer more. Tailors, boutiques and gift shops can buy ready transfers and press them in-house, adding a custom printing service without a big machine investment.</p>\n<h2>What to Check Before Ordering DTF Prints</h2>\n<p>Before you place an order with any printer, it helps to ask a few simple things:</p>\n<p><strong>1.</strong> Can I see a printed sample? A screen preview never shows the real finish.</p>\n<p><strong>2.</strong> What kind of ink and film do you use? Good materials show up in how a print survives washing.</p>\n<p><strong>3.</strong> Will you check my artwork? A decent printer will warn you if the resolution is too low.</p>\n<p><strong>4.</strong> Can you handle bulk if I grow? Ideally you want one partner for both small and large runs.</p>\n<p><strong>5.</strong> How long will it take? Ask for a clear delivery timeline before paying.</p>\n<h2>Where DTF Printing Is Heading</h2>\n<p>Printers are getting faster, inks are improving and transfers are becoming softer and more breathable. As these improvements continue, DTF printing technology will likely become the default choice for custom clothing rather than an alternative. The industry is moving toward smaller batches, faster launches and more personal designs, and DTF fits that direction almost perfectly.</p>\n<h2>Looking for DTF Printing Services in Delhi?</h2>\n<p>At Printex, we have been printing custom apparel in Delhi since 2010. Whether you need a single sample T-shirt or a large run for your brand, team or company, our DTF printing services are built to give you sharp colours and prints that last. We also take on bulk DTF printing for businesses across India, so you can scale up without changing your supplier.</p>\n<p>Share your design with us, and we will guide you on fabric, size and quantity so you get the best result for your budget. Get in touch with Printex today and see what DTF can do for your brand.</p>\n<h2>Frequently Asked Questions About DTF Printing</h2>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">1. What is DTF printing?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">DTF stands for Direct to Film. Your design is printed on a special film, coated with adhesive powder, and then heat-pressed onto the garment. Once the film is peeled off, the design stays on the fabric. This method works on many types of material, which is why it has become so popular for custom apparel printing.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">2. Is DTF printing good for T-shirts?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Yes. DTF printing on T-shirts gives bright colours, sharp details and a soft, flexible finish. It works well on cotton, polyester and blended fabrics, and it handles detailed artwork and multi-colour designs without extra cost.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">3. How is DTF different from screen printing?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Screen printing needs a separate screen for every colour, so it makes sense mainly for very large quantities of a simple design. DTF needs no screens, so it suits small and medium orders, designs with many colours, and orders where different designs are needed together. When comparing DTF vs screen printing, the right choice depends on your quantity and artwork.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">4. Are DTF prints long-lasting?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">A good DTF print holds up well with regular washing. Durability depends on the quality of the film, ink and adhesive, and on correct heat pressing. To make the print last longer, wash the garment inside out in normal water, avoid harsh bleaching, and do not iron directly over the design.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">5. Can DTF printing be done on dark-coloured garments?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Yes. DTF prints include a white base layer, so colours stay vivid on dark fabrics like black, navy or maroon, without any special pre-treatment of the garment.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">6. What is the minimum order for DTF printing?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Since there is no screen setup, even a single piece can be printed, which makes DTF handy for samples and personalised gifts. For exact minimums and pricing, it is best to check with your printer.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">7. Can I order bulk DTF printing in India?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Yes. Many printers now offer bulk DTF printing for brands, corporate teams, schools and events, with deliveries across India. At Printex, we handle both small trial runs and large orders, so you can scale up without changing your supplier.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">8. Where can I get DTF printing services in Delhi?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Printex has been doing custom printing in Delhi since 2010. You can share your design with us and we will suggest the right fabric, size and quantity before printing. Check our DTF printing services page or contact us for a quote.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">9. What file format should I send for DTF printing?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">A high-resolution PNG with a transparent background is usually best. Vector files like AI, PDF or SVG also work well. Low-resolution images can look blurry when printed, so it helps to send the largest version of your artwork.</p>\n</div>','assets/images/dtf_fabric_print.jpg','OrderPrintex Editorial Team','6 min read','published',1,135,'DTF Printing Technology: How It’s Changing Custom Apparel','Learn how DTF printing technology is reshaping custom apparel printing, from low minimum orders to colourful, long-lasting prints. See how it compares with screen printing.','2026-11-02 03:25:04','2026-11-12 10:53:27'),
(2,1,'UV DTF Printing: How It Works and Its Applications in Product Branding','uv-dtf-printing-product-branding','Pick up a premium-looking water bottle or a café mug these days and run your thumb over the logo. Chances are it feels slightly raised, glossy, and almost moulded into the surface. Most people assume that kind of finish needs expensive manufacturing. In reality, it is often UV DTF printing, a method that has quietly changed how small and mid-sized brands put their name on products.','<p>Pick up a premium-looking water bottle or a café mug these days and run your thumb over the logo. Chances are it feels slightly raised, glossy, and almost moulded into the surface. Most people assume that kind of finish needs expensive manufacturing. In reality, it is often UV DTF printing, a method that has quietly changed how small and mid-sized brands put their name on products.</p>\n<p>If you have been wondering whether it is worth trying for your business, this guide will help. We will look at what the technique is, how it works, how it differs from regular DTF, and where it makes the most sense for branding.</p>\n<h2>What Is UV DTF Printing?</h2>\n<p>So, what is UV DTF printing? The name stands for ultraviolet direct-to-film. Instead of printing straight onto your product, the artwork is first printed on a special film using UV-curable ink. An adhesive layer and a clear protective film are then added. What you end up with is a ready-to-apply design that needs no heat press and no special equipment.</p>\n<p>The easiest way to picture it is as a sticker, but not the kind you are imagining. An ordinary sticker leaves a vinyl background visible around the design. UV DTF stickers work differently: once the top film is peeled away, only the artwork stays on the surface. No border, no backing, just your logo or graphic with a glossy, slightly raised finish.</p>\n<p>These UV DTF transfers are also tough. After application they resist water, scratches and everyday wear, which is why they have become popular on items that get handled and washed often.</p>\n<h2>How UV DTF Printing Works</h2>\n<p>Knowing how UV DTF printing works helps you brief your printer properly and spot quality differences between vendors. The process runs in a few clear steps.</p>\n<p><strong>1.</strong> Artwork check. The design is reviewed for resolution, colour and size. Thin lines and tiny text are adjusted so they stay readable after transfer.</p>\n<p><strong>2.</strong> Printing on the film. A UV printer lays down the colours on a transfer film. A white ink layer goes in as well, so the colours stay bright even on dark or metallic products.</p>\n<p><strong>3.</strong> Gloss layer. A clear varnish is printed over the design. This is what creates the raised, glassy effect that makes UV DTF stand out.</p>\n<p><strong>4.</strong> Instant curing. UV light cures the ink as it is printed. There is no drying time, and the finish is hard and scratch resistant.</p>\n<p><strong>5.</strong> Lamination. A second adhesive film is laminated on top. This is the layer you peel off later.</p>\n<p><strong>6.</strong> Cutting. The sheet is cut into individual pieces, by hand or with a cutting machine, depending on the shape.</p>\n<p><strong>7.</strong> Application. You place the transfer on the product, press and rub firmly, then peel off the top film. It takes under a minute.</p>\n<h2>UV DTF vs DTF Printing</h2>\n<p>The two get confused a lot because the names are nearly identical, but they are built for different jobs.</p>\n<p>Regular DTF prints a design on film and presses it onto fabric using heat. It is ideal for T-shirts, hoodies, caps and bags. The result is soft and flexible, so it moves with the cloth.</p>\n<p>UV DTF is made for hard surfaces. It needs no heat, so you can use it on things that would melt, warp or burn under a press. The finish is glossy and raised instead of soft.</p>\n<p>A quick way to decide:</p>\n<ul>\n<li>Selling apparel? Go with DTF.</li>\n<li>Selling bottles, mugs, gifts or packaged products? Go with UV DTF.</li>\n<li>Doing both? Many brands do, and the two work well side by side.</li>\n</ul>\n<h2>UV DTF Printing Applications</h2>\n<p>The real value shows up when you look at where it can be used. Here are the most common UV DTF printing applications in branding.</p>\n<h3>UV DTF Printing on Bottles, Mugs and Glass</h3>\n<p>This is the biggest use case. Steel bottles, tumblers, ceramic mugs and glass jars all take UV DTF stickers well, including curved shapes. The logo stays sharp, survives regular washing, and looks far more polished than a paper label. Cafés, gyms, schools and offices use it for branded drinkware all the time.</p>\n<h3>Corporate Gifting</h3>\n<p>Diaries, pen holders, power banks, wooden boxes, metal cards and desk accessories can all carry your logo. Even a batch of 25 or 50 pieces is practical, which matters when you are planning gifts for a small team or a handful of clients.</p>\n<h3>Product Packaging</h3>\n<p>Cosmetic jars, candle containers, perfume bottles and gift boxes look more premium with a glossy logo on the pack. For a new brand, this is a cheaper way to get a custom look without ordering printed packaging in thousands.</p>\n<h3>Personalised and Event Items</h3>\n<p>Wedding favours, return gifts, trophies and name-printed bottles are perfect for UV DTF, since every piece can carry a different name or design without extra setup cost.</p>\n<h3>Electronics and Accessories</h3>\n<p>Phone covers, laptop cases and headphone cases take UV DTF printing well too, and a custom graphic gives them a retail-ready look.</p>\n<h2>Why UV DTF Printing for Product Branding Makes Sense</h2>\n<p>There are a few practical reasons brands keep choosing it.</p>\n<p>Low minimum quantities. You do not have to commit to hundreds of pieces to test an idea.</p>\n<p>A premium feel. The raised, glossy finish makes an ordinary product look more expensive than it is.</p>\n<p>Full-colour freedom. Gradients, photographs and multi-colour logos print without extra cost per colour.</p>\n<p>Durability. A properly applied transfer handles water, friction and daily use.</p>\n<p>Speed. With no screens or moulds to prepare, orders move quickly.</p>\n<p>Flexibility. One technique covers glass, metal, plastic, wood, ceramic and leather.</p>\n<h2>Tips for Better Results</h2>\n<ul>\n<li>Share vector files (AI, CDR, PDF) or a high-resolution PNG.</li>\n<li>Clean the product with an alcohol wipe before applying. Dust and oil weaken the bond.</li>\n<li>Choose flat or gently curved areas. Sharp curves and rough textures can lift the edges.</li>\n<li>Press from the centre outwards, then peel the film slowly.</li>\n<li>Give it around 24 hours before washing or heavy use.</li>\n</ul>\n<h2>UV DTF Printing in Delhi</h2>\n<p>If you are looking for UV DTF printing in Delhi, Printex has been handling custom printing work since 2010. We print, cut and supply ready-to-apply UV DTF stickers and transfers for bottles, mugs, gift items and packaging, in small as well as bulk quantities. Send us your artwork and we will help you pick the right size, finish and quantity for your product.</p>\n<p>Ready to see your logo as a UV DTF sticker? Share your design with us and get a quote.</p>\n<h2>Frequently Asked Questions</h2>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">1. Is UV DTF printing waterproof?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Yes. Once applied, the design resists water, scratches and fading, so it holds up on bottles and mugs.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">2. Can UV DTF stickers go on curved surfaces?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Yes, they work well on cylindrical and gently curved items. Very sharp curves or heavily textured surfaces may not hold as well.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">3. Do UV DTF transfers need a heat press?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">No. They are applied by hand with pressure, which makes them safe for heat-sensitive products.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">4. Can I order a small quantity?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Yes. UV DTF suits small custom orders as well as bulk.</p>\n</div>','assets/images/uv_dtf_drinkware.jpg','OrderPrintex Editorial Team','5 min read','published',1,47,'UV DTF Printing: How It Works & Its Uses in Product Branding','What is UV DTF printing and how does it work? Learn about UV DTF stickers, transfers and their uses in product branding, plus UV DTF printing in Delhi.','2026-11-02 03:25:04','2026-11-12 08:33:09'),
(3,2,'Different T-Shirt Printing Techniques: A Complete Guide for Businesses','different-t-shirt-printing-techniques','If you run a business, sooner or later you will need printed T-shirts. Maybe it is a uniform for your staff, merchandise for a launch, gifts for a corporate event, or a small clothing line of your own. And the moment you start asking for quotes, you run into a confusing list: screen printing, DTF, DTG, heat transfer, sublimation, vinyl, embroidery. Every printer swears by a different one, which makes choosing between T-shirt printing techniques harder than it should be.','<p>If you run a business, sooner or later you will need printed T-shirts. Maybe it is a uniform for your staff, merchandise for a launch, gifts for a corporate event, or a small clothing line of your own. And the moment you start asking for quotes, you run into a confusing list: screen printing, DTF, DTG, heat transfer, sublimation, vinyl, embroidery. Every printer swears by a different one, which makes choosing between T-shirt printing techniques harder than it should be.</p>\n<p>The truth is that there is no single \"best\" T-shirt printing technique. There is only the best technique for your order: your quantity, your design, your fabric and your budget. This guide walks through the main T-shirt printing techniques used in India today, what each one does well, where it falls short, and how a business should choose for custom T-shirt printing and bulk T-shirt printing orders.</p>\n<h2>Why T-shirt printing techniques matter more than you think</h2>\n<p>Two T-shirts can carry the exact same logo and still feel completely different. One stays soft and bright after fifty washes. The other cracks, fades or feels like a plastic sticker on your chest. That difference comes from the T-shirt printing techniques behind it, not the design.</p>\n<p>For a business, this has real consequences. Staff uniforms need to survive daily wear. Event T-shirts need to look sharp in photos. Retail merchandise needs to feel premium enough that customers actually wear it. Choosing the wrong one of these T-shirt printing techniques can mean wasted money, unhappy customers and reprints.</p>\n<h2>1. Screen printing</h2>\n<p>Screen printing is the oldest and most familiar of all T-shirt printing techniques. A stencil (the screen) is made for each colour in your design, and ink is pushed through it onto the fabric. One colour, one screen.</p>\n<p>Where it works well</p>\n<ul>\n<li>Bulk T-shirt printing orders of 100 pieces or more, where the setup cost gets spread thin</li>\n<li>Simple designs with one to three colours, such as a logo or a slogan</li>\n<li>Long-lasting prints on cotton that handle repeated washing</li>\n</ul>\n<p>Where it struggles</p>\n<ul>\n<li>Small quantities, because every screen has a setup cost</li>\n<li>Detailed, photographic or gradient designs, which need many screens and become expensive</li>\n</ul>\n<p>If you are ordering a few hundred uniforms with a single-colour logo, screen printing is still very hard to beat on cost per piece.</p>\n<h2>2. DTF (Direct to Film) printing</h2>\n<p>DTF has become one of the most talked-about T-shirt printing techniques in the last few years, and for good reason. Your design is printed on a special film with water-based inks, coated with adhesive powder, and then heat-pressed onto the garment.</p>\n<p>Where it works well</p>\n<ul>\n<li>Full-colour designs, gradients and fine detail</li>\n<li>Small to medium custom T-shirt printing orders, since there is no screen setup</li>\n<li>Many fabrics: cotton, polyester, blends and even some dark or stretchy materials</li>\n<li>Businesses that want different designs across a batch without paying extra for each one</li>\n</ul>\n<p>Where it struggles</p>\n<ul>\n<li>Very large runs of a single simple design, where screen printing can be cheaper</li>\n<li>Cheap quality films and powders, which is why the printer you choose matters</li>\n</ul>\n<p>For most growing businesses, DTF printing offers the best balance of quality, flexibility and price. If you want to understand it in depth, we have explained it in our guide on how DTF printing technology is transforming the custom apparel industry.</p>\n<h2>3. DTG (Direct to Garment) printing</h2>\n<p>DTG works like a home inkjet printer, except the paper is a T-shirt. The ink goes straight into the fabric fibres, which gives a very soft feel.</p>\n<p>Where it works well</p>\n<ul>\n<li>Highly detailed artwork and photographs</li>\n<li>Very small orders, even a single piece</li>\n<li>100% cotton garments, where it gives its best results</li>\n</ul>\n<p>Where it struggles</p>\n<ul>\n<li>Polyester and blended fabrics, where colours can look dull</li>\n<li>Dark garments, which usually need a pre-treatment step and push up the cost</li>\n<li>Bulk orders, because it is slower than other methods</li>\n</ul>\n<p>Among all T-shirt printing techniques, DTG suits boutique brands and sample runs, but it is rarely the most practical choice for corporate bulk orders.</p>\n<h2>4. Heat transfer and vinyl printing</h2>\n<p>In heat transfer printing, the design is first printed or cut on a special paper or vinyl sheet, then pressed onto the T-shirt with heat. Vinyl is common for names, numbers and single-colour lettering, and it is one of the most budget-friendly T-shirt printing techniques for small jobs.</p>\n<p>Where it works well</p>\n<ul>\n<li>Names and numbers on sports jerseys and team wear</li>\n<li>Quick, small jobs with simple lettering</li>\n<li>Reflective or metallic finishes</li>\n</ul>\n<p>Where it struggles</p>\n<ul>\n<li>Large designs, which can feel heavy and less breathable</li>\n<li>Long-term durability, since cheap vinyl can peel after repeated washing</li>\n</ul>\n<h2>5. Sublimation printing</h2>\n<p>Sublimation uses heat to turn special dye into gas, which bonds permanently with the fabric. The print becomes part of the material itself, so it never cracks or peels.</p>\n<p>Where it works well</p>\n<ul>\n<li>Polyester and polyester-rich fabrics such as sports jerseys</li>\n<li>All-over prints that cover the entire T-shirt</li>\n<li>Vibrant colours that do not fade</li>\n</ul>\n<p>Where it struggles</p>\n<ul>\n<li>Cotton, because the dye needs polyester to bond</li>\n<li>Dark fabrics, since sublimation works only on light-coloured material</li>\n</ul>\n<p>If your business is in fitness, sports or events, sublimation on polyester is one of the T-shirt printing techniques worth considering.</p>\n<h2>6. Embroidery</h2>\n<p>Embroidery is not printing in the strict sense, but many businesses compare it with printing, so it deserves a mention. A machine stitches your logo directly into the fabric with thread.</p>\n<p>Where it works well</p>\n<ul>\n<li>Corporate polo shirts, caps and premium uniforms</li>\n<li>Small logos on the chest or sleeve</li>\n<li>A rich, professional look that lasts for years</li>\n</ul>\n<p>Where it struggles</p>\n<ul>\n<li>Large or highly detailed designs</li>\n<li>Cost, since it is usually more expensive than printing</li>\n</ul>\n<h2>Quick comparison of T-shirt printing techniques for different business needs</h2>\n<div style=\"overflow-x:auto; margin:1.5rem 0;\"><table class=\"compare-table\" style=\"width:100%;\">\n<thead><tr><th style=\"padding:0.75rem 1rem; color:var(--primary-gold);\">Technique</th><th style=\"padding:0.75rem 1rem; color:var(--primary-gold);\">Best for</th><th style=\"padding:0.75rem 1rem; color:var(--primary-gold);\">Ideal quantity</th><th style=\"padding:0.75rem 1rem; color:var(--primary-gold);\">Fabric</th></tr></thead>\n<tbody>\n<tr><td style=\"padding:0.75rem 1rem;\">Screen printing</td><td style=\"padding:0.75rem 1rem;\">Simple logos, uniforms</td><td style=\"padding:0.75rem 1rem;\">100+ pieces</td><td style=\"padding:0.75rem 1rem;\">Cotton, blends</td></tr>\n<tr><td style=\"padding:0.75rem 1rem;\">DTF printing</td><td style=\"padding:0.75rem 1rem;\">Full-colour, detailed designs</td><td style=\"padding:0.75rem 1rem;\">10 to 1,000+ pieces</td><td style=\"padding:0.75rem 1rem;\">Almost all fabrics</td></tr>\n<tr><td style=\"padding:0.75rem 1rem;\">DTG printing</td><td style=\"padding:0.75rem 1rem;\">Photographic designs, samples</td><td style=\"padding:0.75rem 1rem;\">1 to 50 pieces</td><td style=\"padding:0.75rem 1rem;\">100% cotton</td></tr>\n<tr><td style=\"padding:0.75rem 1rem;\">Heat transfer / vinyl</td><td style=\"padding:0.75rem 1rem;\">Names, numbers, lettering</td><td style=\"padding:0.75rem 1rem;\">1 to 100 pieces</td><td style=\"padding:0.75rem 1rem;\">Cotton, polyester</td></tr>\n<tr><td style=\"padding:0.75rem 1rem;\">Sublimation</td><td style=\"padding:0.75rem 1rem;\">All-over prints, sportswear</td><td style=\"padding:0.75rem 1rem;\">10 to 500 pieces</td><td style=\"padding:0.75rem 1rem;\">Polyester</td></tr>\n<tr><td style=\"padding:0.75rem 1rem;\">Embroidery</td><td style=\"padding:0.75rem 1rem;\">Polos, caps, premium wear</td><td style=\"padding:0.75rem 1rem;\">10 to 500 pieces</td><td style=\"padding:0.75rem 1rem;\">Cotton, polo fabrics</td></tr>\n</tbody></table></div>\n<h2>How to choose between T-shirt printing techniques for your business</h2>\n<p>Before you compare T-shirt printing techniques and ask for a quote, answer these five questions. They will narrow down the right method in minutes.</p>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">1. How many pieces do you need?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Under 50 pieces usually points towards DTF, DTG or heat transfer. Above 100 pieces with a simple design, screen printing starts to make financial sense.</p>\n</div>\n<p><strong>2.</strong> How complex is your design? A single-colour logo is easy for almost any method. A multi-colour logo, gradient or photograph is a job for DTF or DTG.</p>\n<p><strong>3.</strong> What is the fabric? Cotton is flexible. Polyester needs sublimation or DTF. Mixing fabrics in one order? DTF handles that best.</p>\n<p><strong>4.</strong> How will the T-shirt be used? Daily staff uniforms need durability. A one-day event T-shirt does not. Retail merchandise needs a soft feel and a premium finish.</p>\n<p><strong>5.</strong> What is your timeline? Screen printing needs setup time. DTF and heat transfer can usually be turned around much faster, which helps when an event date is fixed.</p>\n<h2>Common mistakes businesses make when ordering printed T-shirts</h2>\n<ul>\n<li>Choosing only on price. The cheapest quote often hides weak films, poor inks or low-GSM T-shirts. Reprints cost more than a good first print.</li>\n<li>Sending low-resolution artwork. A blurry logo prints as a blurry logo. Share vector files (AI, CDR, PDF) or high-resolution PNGs.</li>\n<li>Ignoring fabric quality. The best print will still look cheap on a thin, badly stitched T-shirt.</li>\n<li>Skipping the sample. For bulk orders, always approve one sample before the full run.</li>\n<li>Not asking about wash care. A good printer will tell you how the print should be washed and how long it should last.</li>\n</ul>\n<h2>Which T-shirt printing techniques do most businesses in Delhi choose?</h2>\n<p>In our experience of T-shirt printing in Delhi at Printex, most corporate and startup clients in Delhi end up choosing DTF printing for its flexibility, or screen printing for very large, simple uniform orders. Sports clubs and gyms often go for sublimation, and boutique brands with small drops lean towards DTG or DTF.</p>\n<p>There is no shortcut, though. The right answer depends on your design, quantity and budget, and a good printer will tell you honestly when a cheaper method will do the job.</p>\n<h2>Final thoughts</h2>\n<p>Understanding T-shirt printing techniques saves you from paying for features you do not need and from receiving prints that do not last. Screen printing is the old reliable for bulk. DTF is the all-rounder. DTG suits detailed small runs. Heat transfer handles names and numbers. Sublimation is made for polyester sportswear. Embroidery gives uniforms that premium touch.</p>\n<p>If you are planning a custom T-shirt printing order for your business, talk to a printer who offers more than one of these T-shirt printing techniques, because that is the only way you will get advice that is not tied to a single machine.</p>\n<p>At Printex, we handle custom T-shirt printing, DTF printing, UV printing and corporate bulk orders for businesses across Delhi and India. Share your design and quantity, and our team will suggest the technique that fits your budget and timeline. Get in touch today for a quote on your next batch.</p>\n<h2>Frequently Asked Questions</h2>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">1. Which of the T-shirt printing techniques is best for bulk orders?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">For large orders of a single, simple design (100 pieces or more), screen printing usually gives the lowest cost per piece. If your design has multiple colours or you need different designs in one batch, DTF printing is often the better choice for bulk T-shirt printing.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">2. What is the difference between DTF and screen printing?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Screen printing pushes ink through a separate stencil for each colour, so it has a setup cost and suits simple designs in large quantities. DTF prints the full design on a film and heat-presses it onto the garment, so it handles multi-colour designs and small or mixed orders without any screen setup.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">3. Which of the T-shirt printing techniques lasts the longest after washing?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Screen printing, sublimation and embroidery are known for lasting a long time. Good quality DTF prints also hold up well through regular washing when the film, powder and heat press settings are right. Wash care matters too: turning the T-shirt inside out and avoiding high heat helps any print last longer.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">4. Can DTF printing be done on both cotton and polyester T-shirts?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Yes. DTF works on cotton, polyester and blended fabrics, which is why it is a popular choice when a business wants the same design on different types of garments. Sublimation, by comparison, works properly only on polyester.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">5. What is the minimum order quantity for custom T-shirt printing?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">It depends on the technique. DTF, DTG and heat transfer can handle very small orders, even a single piece. Screen printing makes financial sense from around 100 pieces. For exact minimums and pricing, share your design with the Printex team and we will guide you.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">6. What file format should I send for T-shirt printing?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Vector files such as AI, CDR or PDF are best, because they stay sharp at any size. If you do not have one, send a high-resolution PNG with a transparent background. Low-quality or screenshot images usually produce blurry prints.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">7. How long does it take to get custom printed T-shirts?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Turnaround depends on the quantity and technique. DTF and heat transfer orders can usually be completed faster, while screen printing needs extra time for setup. If you have an event date, tell us early so we can plan the production schedule.</p>\n</div>\n<div style=\"margin-bottom:1.5rem;\">\n<h4 style=\"color:var(--text-primary); font-size:1.1rem; margin-bottom:0.4rem; font-weight:700;\">8. Does Printex offer T-shirt printing in Delhi for businesses?</h4>\n<p style=\"margin:0; color:var(--text-secondary); line-height:1.7;\">Yes. Printex handles custom T-shirt printing, DTF printing, UV printing and corporate bulk orders for businesses in Delhi and across India. You can send your design and quantity for a quote and a recommendation on the right technique.</p>\n</div>\n<p></USER_REQUEST></p>\n<p><ADDITIONAL_METADATA></p>\n<p>The current local time is: 2026-11-12T12:25:09+05:30.</p>\n<p>The user\'s current state is as follows:</p>\n<p>Active Document: c:\\Movies\\Internship\\OrderPrintex\\services\\dtf-printing.php (LANGUAGE_UNSPECIFIED)</p>\n<p>Cursor is on line: 29</p>\n<p>Other open documents:</p>\n<ul>\n<li>c:\\Movies\\Internship\\OrderPrintex\\scratch\\verify_services_mobile.js (LANGUAGE_UNSPECIFIED)</li>\n<li>c:\\Movies\\Internship\\OrderPrintex\\services.php (LANGUAGE_UNSPECIFIED)</li>\n<li>c:\\Movies\\Internship\\OrderPrintex\\schema.json (LANGUAGE_UNSPECIFIED)</li>\n<li>c:\\Movies\\Internship\\OrderPrintex\\product-details.php (LANGUAGE_UNSPECIFIED)</li>\n<li>c:\\Movies\\Internship\\OrderPrintex\\about.php (LANGUAGE_UNSPECIFIED)</li>\n</ul>\n<p></ADDITIONAL_METADATA></p>','assets/images/custom_tshirt_apparel.jpg','By Printex','8 min read','published',1,45,'Different T-Shirt Printing Techniques: A Complete Guide for Businesses | Printex','If you run a business, sooner or later you will need printed T-shirts. Compare screen printing, DTF, DTG, heat transfer, sublimation, vinyl, and embroidery.','2026-11-02 03:25:04','2026-11-12 08:33:09'),
(4,1,'DTF vs Screen Printing: Understanding the Differences, Benefits and Applications','dtf-vs-screen-printing-differences-benefits-applications','Sooner or later, everyone who orders custom T-shirts runs into the same question: DTF vs screen printing, which one should I go for? Both methods can give you a sharp, good-looking print, but they work very differently, cost differently and suit different kinds of orders.','<p>Sooner or later, everyone who orders custom T-shirts runs into the same question: DTF vs screen printing, which one should I go for? Both methods can give you a sharp, good-looking print, but they work very differently, cost differently and suit different kinds of orders. Pick the wrong one and you either overspend on a small batch or end up with a design that doesn’t hold up. In this guide, we explain the difference between DTF and screen printing in plain language, so you can decide with confidence.</p>\n\n<h2>What Is DTF Printing?</h2>\n<p>DTF stands for Direct to Film. The design is first printed onto a special transfer film using water-based pigment inks, with a layer of white ink where needed. While the ink is still wet, a fine adhesive powder is sprinkled on it and cured. The film is then placed on the fabric and pressed with heat. When you peel the film away, the design stays on the garment.</p>\n<p>Because the artwork is printed digitally, there are no screens or stencils involved. Full-colour designs, gradients, photographs and tiny text all print without any extra setup. DTF also works on cotton, polyester and blended fabrics, which makes it very flexible. If you want to understand the technology itself, our article on how DTF printing technology is transforming the custom apparel industry goes deeper.</p>\n\n<h2>What Is Screen Printing?</h2>\n<p>Screen printing is the older, traditional method. A separate mesh screen is prepared for every colour in the design. Ink is pushed through the open parts of the screen onto the fabric with a squeegee, one colour at a time. Each colour has to be dried or cured, and the screens must be aligned carefully so the layers match perfectly.</p>\n<p>Once the screens are ready, the process is fast and repeatable. That is why it has been the standard for large runs of uniforms, merchandise and promotional T-shirts for decades. The catch is the setup: screens take time and money to make, so the method makes the most sense when the quantity is high.</p>\n\n<h2>DTF vs Screen Printing: The Key Differences</h2>\n<p>The real difference between DTF and screen printing shows up in a few practical areas. Here is how they compare.</p>\n\n<h3>Cost</h3>\n<p>The DTF vs screen printing cost question depends almost entirely on quantity. DTF has very little setup cost, so a batch of 10 or 50 T-shirts with a multi-colour design is usually cheaper with DTF. Screen printing carries a fixed cost for creating screens, and that cost gets spread thinner as the quantity grows. Past a few hundred pieces with a simple design, the per-piece price of screen printing often drops below DTF.</p>\n\n<h3>Bulk Orders</h3>\n<p>When it comes to DTF vs screen printing for bulk orders, screen printing wins on very large runs of one or two colour designs. DTF is no slouch though. Several designs can be printed together on one film sheet, which makes it a great fit when a bulk order includes different artwork, names, numbers or sizes.</p>\n\n<h3>Durability</h3>\n<p>Screen printing has long had a reputation for toughness, and a well-cured screen print can last for years. DTF has closed much of that gap. A properly pressed DTF print handles repeated washing and stays flexible, especially if the garment is washed inside out at a moderate temperature. In the DTF vs screen printing durability debate, both are reliable when done right. The quality of the ink, the curing and the heat pressing matter more than the method itself.</p>\n\n<h3>Colour and Detail</h3>\n<p>DTF is the clear winner for complex artwork. Gradients, shadows, photographs and fine lines come out clean because the printer handles all the colours in a single pass. Screen printing is brilliant for bold, solid colours, but every extra colour means another screen, more time and a higher cost.</p>\n\n<h3>Feel on the Fabric</h3>\n<p>Screen prints usually sit slightly deeper in the fabric and can feel lighter. DTF prints sit on top of the fabric as a thin, flexible layer. With good film and proper pressing, a DTF print feels soft and stretches with the garment, but it is a little more noticeable to the touch on very large designs.</p>\n\n<h3>Turnaround Time</h3>\n<p>Since DTF needs no screens, work can begin the moment your artwork is approved. That makes it the better option when you need T-shirts quickly. Screen printing takes longer to start, though it can be very fast once production is running.</p>\n\n<h2>Benefits of DTF Printing</h2>\n<p>The benefits of DTF printing explain why so many brands and print shops have moved towards it:</p>\n<ul>\n  <li>No minimum order, so even one or two pieces are practical</li>\n  <li>Full-colour, photo-quality results with no limit on colours</li>\n  <li>Works on cotton, polyester, blends and many other fabrics</li>\n  <li>Quick setup and faster delivery</li>\n  <li>Soft, flexible prints that stretch without cracking</li>\n  <li>Easy to personalise with names, numbers or different designs in one order</li>\n</ul>\n\n<h2>Benefits of Screen Printing</h2>\n<p>Screen printing still has strong advantages, and it is not going anywhere:</p>\n<ul>\n  <li>Lowest cost per piece on large quantities</li>\n  <li>Rich, opaque and very vibrant solid colours</li>\n  <li>Excellent consistency across thousands of identical pieces</li>\n  <li>A long, proven track record with excellent wash performance</li>\n  <li>A great match for simple one or two colour logos</li>\n</ul>\n\n<h2>DTF Printing Applications</h2>\n<p>The applications of DTF printing are wide because the method is so flexible. It suits custom T-shirts in small and medium batches, hoodies and sweatshirts, sports jerseys with names and numbers, boutique and startup clothing collections, event and wedding merchandise, and sample runs where you want to test a design before investing in a big order.</p>\n\n<h2>Screen Printing Applications</h2>\n<p>Screen printing applications lean towards volume and simplicity. Think of company uniforms, school and college T-shirts, promotional giveaways, workwear with a single logo, tote bags and large merchandise runs where hundreds of identical pieces are needed.</p>\n\n<h2>DTF vs Screen Printing: Which One Should You Choose?</h2>\n<p>Here is a simple way to think about it. Choose DTF if your quantity is small to medium, your design has many colours or fine detail, you need names or variations, or you are short on time. Choose screen printing if you need hundreds or thousands of identical pieces with a simple design and you want the lowest possible cost per piece.</p>\n<p>Honestly, many businesses use both. A brand might print a new collection in DTF to test the market, then move the best seller to screen printing once demand is proven. The right answer in the DTF vs screen printing decision is the one that matches your quantity, design and budget, not the one that sounds more modern.</p>\n\n<h2>DTF Printing in Delhi with Printex</h2>\n<p>If you are looking for custom T-shirt printing in Delhi, Printex has been serving businesses, brands and individuals since 2010 from Chanakya Place. We take care of DTF printing for T-shirts, hoodies and more, with sharp colours, soft finish and dependable turnaround. Not sure which method suits your order? Share your design and quantity with us, and we will guide you honestly towards the option that makes the most sense. You can also explore our DTF printing service page to see what we offer.</p>\n\n<h2>Final Thoughts</h2>\n<p>There is no single winner in the DTF vs screen printing debate. DTF gives you freedom, detail and low setup, while screen printing gives you unbeatable economy on big runs. Once you know your quantity, your artwork and your timeline, the choice becomes much easier. And if you would still like a second opinion, the Printex team is always happy to help.</p>\n\n<h2>Frequently Asked Questions</h2>\n<div class=\"faq-accordion\">\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>1. What is the main difference between DTF and screen printing?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      DTF prints the design digitally onto a film and then heat-presses it onto the fabric, while screen printing pushes ink through a separate mesh screen for each colour. DTF needs no screens, so it is better for detailed, multi-colour designs and smaller orders.\n    </div>\n  </div>\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>2. Which is cheaper, DTF or screen printing?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      It depends on quantity. DTF is usually cheaper for small and medium batches because there is no screen setup cost. Screen printing becomes cheaper per piece on large orders with simple, one or two colour designs.\n    </div>\n  </div>\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>3. Is DTF printing as durable as screen printing?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      When it is printed and pressed properly, DTF lasts through many washes and stays flexible. Screen printing has a longer track record, but the durability gap between the two is small today. Washing the garment inside out at a moderate temperature helps either print last longer.\n    </div>\n  </div>\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>4. Which is better for bulk orders, DTF or screen printing?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      For very large runs of the same simple design, screen printing is usually more economical. For bulk orders with different designs, names, numbers or many colours, DTF is often the more practical choice.\n    </div>\n  </div>\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>5. Can DTF printing be done on different fabrics?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      Yes. DTF works well on cotton, polyester and blended fabrics, which is one of its biggest advantages over many other methods. It is a popular choice for T-shirts, hoodies and sweatshirts.\n    </div>\n  </div>\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>6. Is DTF printing good for small orders?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      Yes. Since there is no minimum order and no screen setup, DTF is ideal for samples, one-off pieces and small batches, including custom T-shirts for events, teams or new brands.\n    </div>\n  </div>\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>7. Where can I get DTF printing in Delhi?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      Printex offers DTF printing and custom T-shirt printing in Delhi from Chanakya Place. Share your design and quantity with us, and we will help you pick the right option for your order.\n    </div>\n  </div>\n</div>','assets/images/dtf_fabric_print.jpg','Printex Team','6 min read','published',0,46,'DTF vs Screen Printing: Differences, Benefits & Uses | Printex','DTF vs screen printing explained: compare cost, durability, bulk orders and applications, and find the right T-shirt printing method for your business in Delhi.','2026-11-12 08:12:25','2026-11-12 08:33:09'),
(5,2,'How Custom Printed T-Shirts Help Businesses Build Brand Identity','how-custom-printed-t-shirts-help-businesses-build-brand-identity','Walk into a busy café, an exhibition stall or a corporate office and notice what you remember afterwards. It is rarely the brochure on the counter. It is the person wearing the logo. Custom printed T-shirts for business turn everyday clothing into a small billboard that people see, remember and talk about.','<p>Walk into a busy café, an exhibition stall or a corporate office and notice what you remember afterwards. It is rarely the brochure on the counter. It is the person wearing the logo. Custom printed T-shirts for business turn everyday clothing into a small billboard that people see, remember and talk about, and they do it without a media budget attached to every wear.</p>\n<p>For a growing company, brand identity can feel like an abstract idea. You have a logo, a few colours and a tagline, but how does any of that reach real people? One of the simplest answers is a well-made T-shirt. This guide explains how custom printed T-shirts for business shape the way customers and staff see your brand, where branded T-shirts for business work best, and what to get right before you place an order.</p>\n\n<h2>What brand identity means, and where T-shirts fit in</h2>\n<p>Brand identity is everything people see and feel when they meet your business: your logo, your colours, your tone and the way your team presents itself. A T-shirt carries nearly all of that in one piece. The logo sits on the chest, the brand colours run through the fabric, and the quality of the print quietly tells people how much you care about details.</p>\n<p>That is why T-shirts for brand identity work better than most people expect. A flyer is read once and dropped. A T-shirt gets worn to the gym, to the market and into photos that end up online.</p>\n\n<h2>1. Custom printed T-shirts for business make your brand visible</h2>\n<p>Visibility is the most obvious benefit. Each time a team member or customer wears your shirt, your logo reaches people who may never have heard of you. Marketers call this a walking advertisement, and unlike a hoarding on the highway, it moves wherever the wearer goes.</p>\n<p>Company logo T-shirts also build recall through repetition. Someone spots your logo at a trade fair, again on a delivery executive, again in a friend\'s picture. By the third sighting the name feels familiar, and familiarity is often what pushes a person to pick up the phone and call.</p>\n\n<h2>2. Employee uniform T-shirts build a team and a standard</h2>\n<p>Inside the company, employee uniform T-shirts do a different job. They are the most everyday use of custom printed T-shirts for business, and they make people feel like one team. A service crew, a shop floor or a startup office all look more organised when everyone wears the same colours.</p>\n<p>Customers benefit too. At a mall kiosk or an expo stall, the person in the branded shirt is clearly the one with answers. That small bit of clarity makes your business look professional and approachable.</p>\n\n<h2>3. Promotional T-shirts keep advertising after the event</h2>\n<p>Events are where promotional T-shirts earn their cost. Hand them out at a launch, a seminar or a college fest, and your brand keeps showing up long after the stage is packed away. People throw away pamphlets and pens. They tend to keep a good T-shirt.</p>\n<p>The word that matters is good. A thin, badly printed shirt ends up as a kitchen duster. The best custom printed T-shirts for business are comfortable enough to be worn willingly, and every wear is free exposure.</p>\n\n<h2>4. Consistency turns a logo into a brand</h2>\n<p>A brand is built on consistency. If your logo is bright red in one batch and dull orange in the next, customers notice, even if they cannot explain why. This is where print quality counts.</p>\n<p>When you order custom T-shirts for branding, share exact colour references and a vector logo file such as AI, CDR or PDF. Always approve a sample before the full run. A reliable printer will match colours across batches, so your brand looks the same in January and in December.</p>\n\n<h2>5. Choose a printing technique that suits your brand</h2>\n<p>The print method changes how your brand feels in the hand. DTF printing handles full-colour logos and fine detail on most fabrics, which makes it a strong all-round choice. Screen printing suits very large orders of simple one or two colour designs. Embroidery gives polo shirts a premium finish. We have compared all of these in our guide to different T-shirt printing techniques for businesses.</p>\n<p>For bulk T-shirt printing for businesses, ask your printer to quote two methods so you can compare cost and finish side by side. Choosing the right custom printed T-shirts for business is much easier when you can hold a real sample in your hand.</p>\n\n<h2>Mistakes to avoid when ordering custom printed T-shirts for business</h2>\n<ul>\n  <li>Picking only on price. The cheapest quote often hides thin fabric and weak inks.</li>\n  <li>Overcrowding the design. A clean logo reads better than a logo, a tagline and three slogans.</li>\n  <li>Ignoring shirt colour. A dark logo on a navy T-shirt disappears. Check the contrast first.</li>\n  <li>Sending a blurry logo. Low-resolution files print blurry. Use vector artwork wherever possible.</li>\n  <li>Forgetting fit and fabric. Staff will not wear an itchy, ill-fitting shirt, however good the print looks.</li>\n</ul>\n\n<h2>Working with a local printer in Delhi</h2>\n<p>If your business is in Delhi NCR, a nearby printer makes life easier. You can approve samples in person, fix mistakes quickly and reorder when your team grows. At Printex, we handle custom T-shirt printing in Delhi for startups, offices, events and retail brands, from small trial batches to large orders. Our corporate T-shirt printing and custom T-shirt printing for companies cover uniforms, merchandise and event wear. Tell us your logo, quantity and budget, and we will suggest the best way to produce custom printed T-shirts for business like yours.</p>\n\n<h2>Frequently asked questions</h2>\n<div class=\"faq-accordion\">\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>1. Why are custom printed T-shirts for business good for branding?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      They put your logo and colours in front of people every day, at almost no extra cost after the order is placed. They also help your team look consistent, which builds trust with customers.\n    </div>\n  </div>\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>2. How many T-shirts should a small business order?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      Start with what you need for your team and your next event. With DTF printing, even small batches are practical, so you can test the design before placing a bigger bulk order.\n    </div>\n  </div>\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>3. Which T-shirt colour works best for a company logo?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      Choose a shirt colour that contrasts clearly with your logo. White or light shirts suit dark logos, and black or navy shirts suit light or bright logos.\n    </div>\n  </div>\n  <div class=\"faq-item\">\n    <div class=\"faq-question\">\n      <span>4. Can I order custom T-shirt printing in Delhi in small quantities?</span>\n      <span class=\"faq-icon\">+</span>\n    </div>\n    <div class=\"faq-answer\">\n      Yes. Share your design and quantity with the Printex team, and we will recommend a printing method that fits your budget and timeline.\n    </div>\n  </div>\n</div>\n\n<h2>Final thoughts</h2>\n<p>A logo on a screen is easy to ignore. A logo on a T-shirt walks into rooms, travels on trains and shows up in photographs. Custom printed T-shirts for business give your brand a face people actually see, and when the print quality and colours stay consistent, they turn a name into something people recognise and trust.</p>\n<p>If you are planning uniforms, event wear or merchandise, talk to Printex. Get in touch today for a quote on custom printed T-shirts for business, and we will help you choose the right fabric, print method and finish.</p>','assets/images/custom_tshirt_apparel.jpg','By Printex','5 min read','published',0,31,'How Custom Printed T-Shirts Help Businesses Build Brand Identity | Printex','Discover how custom printed T-shirts for business shape brand identity, build customer recall, and unite teams. Complete guide by Printex Delhi.','2026-11-12 08:12:25','2026-11-12 08:33:09');
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(2,'UV DTF Decals','uv-dtf-decals','Scratchproof 3D embossed decals for hard surfaces',2,'2026-11-02 03:25:04'),
(3,'Custom T-Shirts','custom-tshirts','Ready-to-wear printed apparel and streetwear',3,'2026-11-02 03:25:04'),
(4,'Corporate Merchandise','corporate-merchandise','Branded drinkware, stationery, and swag',4,'2026-11-02 03:25:04'),
(5,'Packaging & Boxes','packaging','Custom rigid and corrugated shipping mailer boxes',5,'2026-11-02 03:25:04'),
(6,'Custom Stickers','custom-stickers','Holographic, die-cut, and vinyl labels',6,'2026-11-02 03:25:04'),
(7,'Custom Stationery','custom-stationery','Bookmarks, flyers, brochures, and business cards',7,'2026-11-02 03:25:04'),
(8,'Automated Test Category','automated-test-category','Created by automated test runner to verify full-width layout.',99,'2026-11-05 09:22:29');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enquiries`
--

DROP TABLE IF EXISTS `enquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_number` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(191) NOT NULL,
  `customer_phone` varchar(30) NOT NULL,
  `company_name` varchar(150) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `service_name` varchar(150) DEFAULT NULL,
  `width_inches` decimal(8,2) DEFAULT NULL,
  `height_inches` decimal(8,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `material_options` varchar(255) DEFAULT NULL,
  `customer_notes` text DEFAULT NULL,
  `status` enum('received','under_review','quoted','converted','rejected','closed') DEFAULT 'received',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `ref_number` (`ref_number`),
  KEY `idx_ref_number` (`ref_number`),
  KEY `idx_enq_status` (`status`),
  KEY `user_id` (`user_id`),
  KEY `service_id` (`service_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `enquiries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `enquiries_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  CONSTRAINT `enquiries_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enquiries`
--

LOCK TABLES `enquiries` WRITE;
/*!40000 ALTER TABLE `enquiries` DISABLE KEYS */;
INSERT INTO `enquiries` VALUES
(4,'OP-ENQ-2026-14589',2,'OrderPrintex Administrator','customer@orderprintex.com','+91 93540 28199',NULL,2,NULL,'UV DTF Printing',21.00,23.00,1,'black','','converted','2026-11-05 07:42:14','2026-11-05 09:05:30'),
(5,'OP-ENQ-2026-51E66',4,'abc','abcd@gmail.com','566566566',NULL,3,NULL,'T-Shirt Printing',NULL,NULL,5,'SKU: STR-S-BLK','','received','2026-11-05 08:00:38','2026-11-05 08:00:38'),
(7,'OP-ENQ-2026-A08FF',1,'OrderPrintex Administrator','admin@orderprintex.com','+91 93540 28199',NULL,2,NULL,'UV DTF Printing',12.00,232.00,1,'fdfd','','quoted','2026-11-05 09:45:31','2026-11-05 09:45:58'),
(8,'OP-ENQ-2026-21B84',NULL,'Automated Test User','testother@example.com','9876543210',NULL,NULL,NULL,'Direct-to-Film Holographic Foil Roll 1793892317817',NULL,NULL,25,'','','received','2026-11-05 15:25:18','2026-11-05 15:25:18'),
(9,'OP-ENQ-2026-47AF3',NULL,'Automated Test User','testother@example.com','9876543210',NULL,NULL,NULL,'Direct-to-Film Holographic Foil Roll 1793892393178',NULL,NULL,25,'','','received','2026-11-05 15:26:33','2026-11-05 15:26:33'),
(10,'OP-ENQ-2026-D0057',NULL,'Automated Test User','testother@example.com','9876543210',NULL,NULL,NULL,'Direct-to-Film Holographic Foil Roll 1793892447035',NULL,NULL,25,'','','received','2026-11-05 15:27:27','2026-11-05 15:27:27'),
(11,'OP-ENQ-2026-BCF13',NULL,'Artwork Test Client','artwork_client@example.com','9812345678',NULL,1,NULL,'DTF Printing',NULL,NULL,30,'','Please check transparent gradient in the gold foil artwork.','received','2026-11-12 05:01:22','2026-11-12 05:01:22'),
(12,'OP-ENQ-2026-8AAAF',NULL,'Artwork Test Client','artwork_client@example.com','9812345678',NULL,1,NULL,'DTF Printing',NULL,NULL,30,'','Please check transparent gradient in the gold foil artwork.','received','2026-11-12 05:02:13','2026-11-12 05:02:13'),
(13,'OP-ENQ-2026-0AC26',NULL,'Artwork Test Client','artwork_client@example.com','9812345678',NULL,1,NULL,'DTF Printing',NULL,NULL,30,'','Please check transparent gradient in the gold foil artwork.','received','2026-11-12 05:03:29','2026-11-12 05:03:29'),
(14,'OP-ENQ-2026-842E9',NULL,'Artwork Test Client','artwork_client@example.com','9812345678',NULL,1,NULL,'DTF Printing',NULL,NULL,30,'','Please check transparent gradient in the gold foil artwork.','under_review','2026-11-12 05:04:33','2026-11-12 05:04:46'),
(15,'OP-ENQ-2026-677FD',NULL,'Artwork Test Client','artwork_client@example.com','9812345678',NULL,1,NULL,'DTF Printing',NULL,NULL,30,'','Please check transparent gradient in the gold foil artwork.','under_review','2026-11-12 05:06:42','2026-11-12 05:06:57'),
(16,'OP-ENQ-2026-10C55',NULL,'Artwork Test Client','artwork_client@example.com','9812345678',NULL,1,NULL,'DTF Printing',NULL,NULL,30,'','Please check transparent gradient in the gold foil artwork.','under_review','2026-11-12 05:08:26','2026-11-12 05:08:39'),
(17,'OP-ENQ-2026-4765B',NULL,'Artwork Test Client','artwork_client@example.com','9812345678',NULL,1,NULL,'DTF Printing',NULL,NULL,30,'','Please check transparent gradient in the gold foil artwork.','received','2026-11-12 05:38:15','2026-11-12 05:38:15'),
(18,'OP-ENQ-2026-38F3F',NULL,'Artwork Test Client','artwork_client@example.com','9812345678',NULL,1,NULL,'DTF Printing',NULL,NULL,30,'','Please check transparent gradient in the gold foil artwork.','under_review','2026-11-12 05:38:28','2026-11-12 05:38:41');
/*!40000 ALTER TABLE `enquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enquiry_files`
--

DROP TABLE IF EXISTS `enquiry_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `enquiry_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enquiry_id` int(11) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `file_extension` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `enquiry_id` (`enquiry_id`),
  CONSTRAINT `enquiry_files_ibfk_1` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enquiry_files`
--

LOCK TABLES `enquiry_files` WRITE;
/*!40000 ALTER TABLE `enquiry_files` DISABLE KEYS */;
INSERT INTO `enquiry_files` VALUES
(1,11,'test_artwork_1.png','20261112_103122_607f86291ae6da8fd718.png','uploads/artwork/20261112_103122_607f86291ae6da8fd718.png',1478,'image/png','png','2026-11-12 05:01:22'),
(2,12,'test_artwork_1.png','20261112_103213_1019b57bee07c9cf3a2e.png','uploads/artwork/20261112_103213_1019b57bee07c9cf3a2e.png',1478,'image/png','png','2026-11-12 05:02:13'),
(3,13,'test_artwork_1.png','20261112_103329_c9bc421853c0400f705c.png','uploads/artwork/20261112_103329_c9bc421853c0400f705c.png',1478,'image/png','png','2026-11-12 05:03:29'),
(4,13,'test_artwork_2.jpg','test_multi_1794459817253.jpg','uploads/artwork/test_multi_1794459817253.jpg',12450,'image/jpeg','jpg','2026-11-12 05:03:37'),
(5,13,'client_specification.pdf','test_doc_1794459820738.pdf','uploads/artwork/test_doc_1794459820738.pdf',45000,'application/pdf','pdf','2026-11-12 05:03:40'),
(6,13,'deleted_artwork.png','non_existent_file_9999.png','uploads/artwork/non_existent_file_9999.png',50000,'image/png','png','2026-11-12 05:03:41'),
(7,14,'test_artwork_1.png','20261112_103433_11646db1451e0bfa1478.png','uploads/artwork/20261112_103433_11646db1451e0bfa1478.png',1478,'image/png','png','2026-11-12 05:04:33'),
(8,14,'test_artwork_2.jpg','test_multi_1794459880291.jpg','uploads/artwork/test_multi_1794459880291.jpg',12450,'image/jpeg','jpg','2026-11-12 05:04:40'),
(9,14,'client_specification.pdf','test_doc_1794459883889.pdf','uploads/artwork/test_doc_1794459883889.pdf',45000,'application/pdf','pdf','2026-11-12 05:04:44'),
(10,14,'deleted_artwork.png','non_existent_file_9999.png','uploads/artwork/non_existent_file_9999.png',50000,'image/png','png','2026-11-12 05:04:44'),
(11,15,'test_artwork_1.png','20261112_103642_7e10df5b5d9a9df22744.png','uploads/artwork/20261112_103642_7e10df5b5d9a9df22744.png',1478,'image/png','png','2026-11-12 05:06:42'),
(12,15,'test_artwork_2.jpg','test_multi_1794460011573.jpg','uploads/artwork/test_multi_1794460011573.jpg',12450,'image/jpeg','jpg','2026-11-12 05:06:52'),
(13,15,'client_specification.pdf','test_doc_1794460015109.pdf','uploads/artwork/test_doc_1794460015109.pdf',45000,'application/pdf','pdf','2026-11-12 05:06:55'),
(14,15,'deleted_artwork.png','non_existent_file_9999.png','uploads/artwork/non_existent_file_9999.png',50000,'image/png','png','2026-11-12 05:06:56'),
(15,16,'test_artwork_1.png','20261112_103826_107fd72cee8b4ab093e1.png','uploads/artwork/20261112_103826_107fd72cee8b4ab093e1.png',1478,'image/png','png','2026-11-12 05:08:26'),
(16,16,'test_artwork_2.jpg','test_multi_1794460113643.jpg','uploads/artwork/test_multi_1794460113643.jpg',12450,'image/jpeg','jpg','2026-11-12 05:08:33'),
(17,16,'client_specification.pdf','test_doc_1794460117011.pdf','uploads/artwork/test_doc_1794460117011.pdf',45000,'application/pdf','pdf','2026-11-12 05:08:37'),
(18,16,'deleted_artwork.png','non_existent_file_9999.png','uploads/artwork/non_existent_file_9999.png',50000,'image/png','png','2026-11-12 05:08:37'),
(19,17,'test_artwork_1.png','20261112_110815_23b68fe2dbddae34054b.png','uploads/artwork/20261112_110815_23b68fe2dbddae34054b.png',1478,'image/png','png','2026-11-12 05:38:15'),
(20,18,'test_artwork_1.png','20261112_110828_fe41328b59472df9aa44.png','uploads/artwork/20261112_110828_fe41328b59472df9aa44.png',1478,'image/png','png','2026-11-12 05:38:28'),
(21,18,'test_artwork_2.jpg','test_multi_1794461915202.jpg','uploads/artwork/test_multi_1794461915202.jpg',12450,'image/jpeg','jpg','2026-11-12 05:38:35'),
(22,18,'client_specification.pdf','test_doc_1794461918640.pdf','uploads/artwork/test_doc_1794461918640.pdf',45000,'application/pdf','pdf','2026-11-12 05:38:38'),
(23,18,'deleted_artwork.png','non_existent_file_9999.png','uploads/artwork/non_existent_file_9999.png',50000,'image/png','png','2026-11-12 05:38:39');
/*!40000 ALTER TABLE `enquiry_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment_tools`
--

DROP TABLE IF EXISTS `equipment_tools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment_tools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `purpose` text NOT NULL,
  `impact` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `image_url` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment_tools`
--

LOCK TABLES `equipment_tools` WRITE;
/*!40000 ALTER TABLE `equipment_tools` DISABLE KEYS */;
INSERT INTO `equipment_tools` VALUES
(1,'Industrial Micro-Piezo Dual-Head DTF Printers','Textile Printing','Dual Epson i3200 micro-piezo heads laying down high-density white underbase and CMYK textile pigments simultaneously.','Photorealistic 1440x2880 DPI resolution with ultra-smooth gradient transitions and flawless solid color fill on dark garments.',1,1,'2026-11-05 05:26:45','assets/images/dtf_fabric_print.jpg'),
(2,'Automatic Powder Shaker & Infrared Conveyor Oven','Transfer Curing','Automated roll-to-roll powdering unit with electrostatic vibration and calibrated infrared heat tunnels.','Guarantees uniform TPU adhesive distribution and complete polyurethane polymerization for 50+ wash longevity.',2,1,'2026-11-05 05:26:45','assets/images/dtf_fabric_print.jpg'),
(3,'Multi-Axis UV DTF Hybrid Flatbed & Roll System','UV Hard Surfaces','UV LED curing system printing opaque white, CMYK, and high-build clear spot varnish onto release film.','Creates waterproof, scratchproof 3D tactile embossed decals for glass, tumblers, wood, and electronics.',3,1,'2026-11-05 05:26:45','assets/images/uv_dtf_drinkware.jpg'),
(4,'Digital Precision Optical Contour Cutting Plotters','Finishing & Decals','High-speed cutting plotters with optical registration cameras for precision blade contouring.','Flawless kiss-cut and die-cut borders with sub-millimeter registration accuracy on vinyl and holographic stocks.',4,1,'2026-11-05 05:26:45','assets/images/custom_stickers.jpg'),
(5,'Industrial Pneumatic Auto-Open Heat Presses','Garment Pressing','Digital dual-platen pneumatic presses with microprocessor temperature control and consistent pneumatic pressure.','Ensures 100% mechanical fiber bonding of transfers without hot spots, scorching, or uneven adhesion.',5,1,'2026-11-05 05:26:45','assets/images/custom_tshirt_apparel.jpg'),
(6,'X-Rite Prepress Color Spectrophotometers','Prepress & Quality','Spectrophotometric color management instruments calibrating ICC profiles for CMYK and Pantone reproduction.','Guarantees consistent color fidelity between digital client mockups and physical press-ready output.',6,1,'2026-11-05 05:26:45','assets/images/branding_stationery.jpg');
/*!40000 ALTER TABLE `equipment_tools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT 'General',
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'dtf',
  `image_url` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 1,
  `is_published` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES
(1,'Detailed DTF Print on a Dark T-Shirt','dtf','assets/images/dtf_fabric_print.jpg','A DTF transfer with smooth color blends, a bright white base and a soft, stretchy feel that doesn’t turn stiff.',0,1,1,'2026-11-05 09:31:44'),
(2,'UV DTF Decals on Glass & Tumbler Curved Surfaces','uv_dtf','assets/images/uv_dtf_drinkware.jpg','Industrial-adhesion 3D tactile UV DTF stickers applied seamlessly onto curved stainless steel bottles and glassware with spot gloss varnish.',1,1,2,'2026-11-05 09:31:44'),
(3,'Heavyweight 240 GSM Streetwear Graphic Tee','tshirt','assets/images/streetwear_tee.jpg','Vintage washed boxy drop-shoulder t-shirt with photographic DTF chest graphic, double-needle stitched seams, and tight retail ribbing.',1,1,3,'2026-11-05 09:31:44'),
(4,'Luxury Apparel Unboxing Mailer Packaging Box','packaging','assets/images/packaging_box.jpg','Custom matte black corrugated mailer box with hot-stamped gold foil logo, spot UV accents, and inner branded tissue wrap.',1,1,4,'2026-11-05 09:31:44'),
(5,'Custom Die-Cut & Holographic Decal Collection','stickers','assets/images/custom_stickers.jpg','Multi-surface peel-and-stick vinyl decals with embossed gloss spot varnish, iridescent light reflection, and permanent acrylic bond.',0,1,5,'2026-11-05 09:31:44'),
(6,'Executive Corporate Swag & Client Gifting Kit','corporate','assets/images/corporate_swag_kit.jpg','Turnkey corporate onboarding kit runs featuring customized insulated drinkware, embossed diary, and stylus pen in a bespoke gift hamper.',0,1,6,'2026-11-05 09:31:44'),
(7,'Retail Brand Collection & Crimson Studio Apparel','tshirt','assets/images/custom_tshirt_apparel.jpg','Studio fashion apparel featuring rich crimson red garment dyes, true-match ICC color profiling, and breathable photographic chest prints.',1,0,8,'2026-11-05 09:31:44'),
(8,'Luxury Embossed Branding Collateral & Stationery','branding','assets/images/branding_stationery.jpg','Thick crimson red and warm beige business cards with edge gilding, debossed linen corporate journal, and executive presentation folders.',1,1,7,'2026-11-05 09:31:44');
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `previous_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `remarks` text DEFAULT NULL,
  `changed_by_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_history`
--

LOCK TABLES `order_status_history` WRITE;
/*!40000 ALTER TABLE `order_status_history` DISABLE KEYS */;
INSERT INTO `order_status_history` VALUES
(1,1,'proof_approval','production','Proof confirmed via WhatsApp. Job sent to UV roll printer.',1,'2026-11-05 06:15:54'),
(2,1,'production','quality_check','Moved to quality check via automated test',1,'2026-11-05 06:18:04'),
(3,1,'quality_check','quality_check','Moved to quality check via automated test',1,'2026-11-05 06:18:31'),
(4,1,'quality_check','quality_check','Moved to quality check via automated test',1,'2026-11-05 06:41:37');
/*!40000 ALTER TABLE `order_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `enquiry_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(191) NOT NULL,
  `customer_phone` varchar(30) NOT NULL,
  `shipping_address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` varchar(20) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'Bank Transfer / UPI',
  `order_status` enum('enquiry_received','under_review','quotation_sent','quotation_approved','payment_pending','artwork_review','proof_approval','production','quality_check','dispatched','delivered','cancelled') DEFAULT 'quotation_approved',
  `courier_name` varchar(100) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `dispatch_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_order_num` (`order_number`),
  KEY `idx_order_status` (`order_status`),
  KEY `quotation_id` (`quotation_id`),
  KEY `enquiry_id` (`enquiry_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES
(1,'ORD-2026-6F140',NULL,NULL,NULL,'Priya Patel','priya@bohocraftstudio.com','+91 98220 12345','Plot 42, Craft Villa, Koregaon Park','Pune','Maharashtra','411001',20900.00,'paid','NEFT / UPI','quality_check','BlueDart Air Express','BD-TEST-1793860897869',NULL,NULL,'Verified via automated integration test','2026-11-04 06:15:54','2026-11-05 06:41:37');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `hero_heading` varchar(255) DEFAULT NULL,
  `hero_subheading` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `content_json` longtext DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content` longtext DEFAULT NULL,
  `created_at` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES
(1,'about-us','About Us',NULL,NULL,NULL,'About OrderPrintex | Industrial DTF & UV DTF Printing Delhi','Discover OrderPrintex: Delhi premier DTF and UV DTF printing manufacturing facility equipped with dual-head printers, optical plotters, and X-Rite spectrophotometers.',NULL,1,'2026-11-02 23:54:08','OrderPrintex is India\'s premier industrial DTF and UV DTF contract printing facility based in Delhi NCR. We specialize in commercial-grade heat transfers, custom garment branding, holographic stickers, and hard-surface industrial decals.','2026-11-03 05:24:08'),
(2,'contact','Contact Us',NULL,NULL,NULL,'Contact OrderPrintex Prepress & Production Desk Delhi','Reach our prepress and production desk via phone +91 93540 28199, WhatsApp, or email sales@orderprintex.com. Factory located in Patparganj Industrial Area, Delhi.',NULL,1,'2026-11-02 23:54:08','Get in touch with our prepress technicians for custom volume quotes, sample kits, and artwork verification. Our factory operates Monday to Saturday from 9:30 AM to 7:00 PM IST.','2026-11-03 05:24:08'),
(3,'privacy-policy','Privacy Policy',NULL,NULL,NULL,'Privacy Policy | OrderPrintex Data Protection','Learn how OrderPrintex protects your personal information, design files, and order details.',NULL,1,'2026-11-02 23:54:08','At OrderPrintex, we respect your privacy and protect your intellectual property. All customer design files, vector logos, and proprietary artwork are held in strict commercial confidence and never shared with third parties.','2026-11-03 05:24:08'),
(4,'terms-and-conditions','Terms & Conditions',NULL,NULL,NULL,'Terms & Conditions | OrderPrintex Commercial Policies','Review the official terms of service, payment milestones, prepress proofs, and delivery guidelines for OrderPrintex.',NULL,1,'2026-11-02 23:54:08','By placing a custom printing order with OrderPrintex, clients agree to digital prepress proof confirmation before production. All invoices include 18% GST and standard factory terms apply.','2026-11-03 05:24:08'),
(5,'shipping-policy','Shipping & Delivery Policy',NULL,NULL,NULL,'PAN-India Shipping Policy & Dispatch | OrderPrintex','Information on our 24-48 hour dispatch speed, express air cargo, protective tube packaging, and real-time courier tracking.',NULL,1,'2026-11-02 23:54:08','We dispatch gang sheets within 24–48 hours across PAN-India via BlueDart, DTDC, and Delhivery Air. Standard moisture-barrier packaging ensures zero damage during monsoon and transit.','2026-11-03 05:24:08');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_attributes`
--

DROP TABLE IF EXISTS `product_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `attribute_id` (`attribute_id`),
  CONSTRAINT `product_attributes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_attributes_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_attributes`
--

LOCK TABLES `product_attributes` WRITE;
/*!40000 ALTER TABLE `product_attributes` DISABLE KEYS */;
INSERT INTO `product_attributes` VALUES
(1,3,1,0),
(2,3,2,0),
(3,4,1,0),
(4,4,2,0),
(5,6,1,0),
(6,6,3,0),
(7,9,1,0),
(8,9,3,0),
(10,2,1,0);
/*!40000 ALTER TABLE `product_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` varchar(200) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_options`
--

DROP TABLE IF EXISTS `product_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `option_name` varchar(150) NOT NULL,
  `option_value` varchar(150) NOT NULL,
  `price_adjustment` decimal(10,2) DEFAULT 0.00,
  `display_order` int(11) DEFAULT 0,
  `option_type` longtext DEFAULT NULL,
  `additional_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_options_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_options`
--

LOCK TABLES `product_options` WRITE;
/*!40000 ALTER TABLE `product_options` DISABLE KEYS */;
INSERT INTO `product_options` VALUES
(1,9,'350 GSM Art Card (Standard)','',0.00,1,'paper',0.00),
(2,9,'400 GSM Extra-Rigid Card','',0.00,2,'paper',0.40),
(3,9,'300 GSM Eco Kraft Recycled','',0.00,3,'paper',0.25),
(4,9,'Standard Matte Lamination','',0.00,1,'finishing',0.20),
(5,9,'Gloss High-Shine Lamination','',0.00,2,'finishing',0.20),
(6,9,'Velvet Soft-Touch Luxury','',0.00,3,'finishing',0.60),
(7,9,'Metallic Gold Foil Accents','',0.00,4,'finishing',1.20),
(10,3,'Front Chest Print Only','',0.00,1,'finishing',0.00),
(11,3,'Front Chest + Full Back Print','',0.00,2,'finishing',120.00),
(12,3,'Front + Back + Sleeve Print','',0.00,3,'finishing',180.00);
/*!40000 ALTER TABLE `product_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_pricing_tiers`
--

DROP TABLE IF EXISTS `product_pricing_tiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_pricing_tiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `min_quantity` int(11) NOT NULL,
  `max_quantity` int(11) DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `fixed_unit_price` decimal(10,2) DEFAULT NULL,
  `badge_label` varchar(100) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `discount_percent` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_pricing_tiers_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_pricing_tiers`
--

LOCK TABLES `product_pricing_tiers` WRITE;
/*!40000 ALTER TABLE `product_pricing_tiers` DISABLE KEYS */;
INSERT INTO `product_pricing_tiers` VALUES
(1,9,100,249,0.00,NULL,NULL,0,0.00,NULL),
(2,9,250,499,0.00,NULL,NULL,0,10.00,NULL),
(3,9,500,999,0.00,NULL,NULL,0,20.00,NULL),
(4,9,1000,99999,0.00,NULL,NULL,0,35.00,NULL),
(9,3,5,19,0.00,NULL,NULL,0,0.00,NULL),
(10,3,20,49,0.00,NULL,NULL,0,10.00,NULL),
(11,3,50,99,0.00,NULL,NULL,0,18.00,NULL),
(12,3,100,9999,0.00,NULL,NULL,0,28.00,NULL);
/*!40000 ALTER TABLE `product_pricing_tiers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reviewer_name` varchar(150) NOT NULL,
  `reviewer_email` varchar(191) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `review_title` varchar(255) DEFAULT NULL,
  `review_text` text NOT NULL,
  `is_verified_buyer` tinyint(1) DEFAULT 1,
  `status` enum('approved','pending','rejected') DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `customer_name` longtext DEFAULT NULL,
  `customer_email` longtext DEFAULT NULL,
  `title` longtext DEFAULT NULL,
  `is_verified_purchase` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_reviews`
--

LOCK TABLES `product_reviews` WRITE;
/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;
INSERT INTO `product_reviews` VALUES
(1,9,NULL,'',NULL,5,NULL,'The 350 GSM cardstock with matte lamination feels ultra premium. Fast delivery to Mumbai.',1,'approved','2026-11-02 03:49:57','Aarav Mehta','aarav@example.com','Exceptional Quality Bookmarks',1);
/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_sizes`
--

DROP TABLE IF EXISTS `product_sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `size_name` varchar(100) NOT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `price_adjustment` decimal(10,2) DEFAULT 0.00,
  `display_order` int(11) DEFAULT 0,
  `size_label` longtext DEFAULT NULL,
  `base_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_sizes`
--

LOCK TABLES `product_sizes` WRITE;
/*!40000 ALTER TABLE `product_sizes` DISABLE KEYS */;
INSERT INTO `product_sizes` VALUES
(1,9,'','2\" x 6\"',0.00,1,'Standard (2 x 6 inches)',1.50),
(2,9,'','2\" x 7\"',0.00,2,'Medium (2 x 7 inches)',2.00),
(3,9,'','2.5\" x 8\"',0.00,3,'Large (2.5 x 8 inches)',2.80),
(8,2,'','8.25\" x 11.75\"',0.00,1,'A4 Sheet (8.25\" x 11.75\")',349.00),
(9,2,'','11.75\" x 16.5\"',0.00,2,'A3 Sheet (11.75\" x 16.5\")',599.00),
(10,2,'','24\" x 36\"',0.00,3,'Full Roll (24\" x 36\")',999.00),
(11,3,'','Chest 40\"',0.00,1,'Small (S)',449.00),
(12,3,'','Chest 42\"',0.00,2,'Medium (M)',449.00),
(13,3,'','Chest 44\"',0.00,3,'Large (L)',449.00),
(14,3,'','Chest 46\"',0.00,4,'X-Large (XL)',479.00),
(15,3,'','Chest 48\"',0.00,5,'2X-Large (2XL)',499.00),
(16,4,'','500ml',0.00,1,'500ml Flask',349.00),
(17,4,'','750ml',0.00,2,'750ml Vacuum Bottle',399.00),
(18,4,'','1000ml',0.00,3,'1000ml Heavy Duty',489.00),
(19,5,'','9x6x2\"',0.00,1,'Small (9\" x 6\" x 2\")',45.00),
(20,5,'','12x9x3\"',0.00,2,'Medium (12\" x 9\" x 3\")',65.00),
(21,5,'','14x10x4\"',0.00,3,'Large (14\" x 10\" x 4\")',85.00),
(22,6,'','2\" x 2\"',0.00,1,'Pack of 50 Pcs',299.00),
(23,6,'','2\" x 2\"',0.00,2,'Pack of 100 Pcs',499.00),
(24,6,'','2\" x 2\"',0.00,3,'Pack of 250 Pcs',999.00),
(25,7,'','Chest 38\"',0.00,1,'Small (S)',299.00),
(26,7,'','Chest 40\"',0.00,2,'Medium (M)',299.00),
(27,7,'','Chest 42\"',0.00,3,'Large (L)',299.00),
(28,7,'','Chest 44\"',0.00,4,'X-Large (XL)',329.00),
(29,8,'','Box 10x8x4\"',0.00,1,'Executive Standard (3 Items)',899.00),
(30,8,'','Box 14x11x5\"',0.00,2,'VIP Deluxe Suite (5 Items)',1399.00);
/*!40000 ALTER TABLE `product_sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variation_values`
--

DROP TABLE IF EXISTS `product_variation_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variation_values` (
  `variation_id` int(11) NOT NULL,
  `attribute_value_id` int(11) NOT NULL,
  PRIMARY KEY (`variation_id`,`attribute_value_id`),
  KEY `attribute_value_id` (`attribute_value_id`),
  CONSTRAINT `product_variation_values_ibfk_1` FOREIGN KEY (`variation_id`) REFERENCES `product_variations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_variation_values_ibfk_2` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variation_values`
--

LOCK TABLES `product_variation_values` WRITE;
/*!40000 ALTER TABLE `product_variation_values` DISABLE KEYS */;
INSERT INTO `product_variation_values` VALUES
(1,1),
(1,18),
(2,1),
(2,19),
(3,1),
(3,20),
(4,2),
(4,18),
(5,2),
(5,19),
(6,2),
(6,20),
(7,3),
(7,18),
(8,3),
(8,19),
(9,3),
(9,20),
(10,4),
(10,18),
(11,4),
(11,19),
(12,4),
(12,20),
(13,5),
(13,18),
(14,5),
(14,19),
(15,5),
(15,20),
(16,6),
(16,18),
(17,6),
(17,19),
(18,6),
(18,20),
(19,7),
(19,18),
(20,7),
(20,19),
(21,7),
(21,20),
(22,8),
(22,18),
(23,8),
(23,19),
(24,8),
(24,20),
(25,11),
(25,23),
(26,11),
(26,24),
(27,12),
(27,23),
(28,12),
(28,24),
(29,9),
(29,26),
(30,9),
(30,27),
(31,10),
(31,26),
(32,10),
(32,27),
(36,16),
(37,17);
/*!40000 ALTER TABLE `product_variation_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variations`
--

DROP TABLE IF EXISTS `product_variations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `combination_key` varchar(255) NOT NULL,
  `regular_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 50,
  `is_in_stock` tinyint(1) NOT NULL DEFAULT 1,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_variations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variations`
--

LOCK TABLES `product_variations` WRITE;
/*!40000 ALTER TABLE `product_variations` DISABLE KEYS */;
INSERT INTO `product_variations` VALUES
(1,3,'STR-S-BLK','size:Small (S)|colour:Phantom Black',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(2,3,'STR-S-WHT','size:Small (S)|colour:Bright White',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(3,3,'STR-S-NVY','size:Small (S)|colour:Midnight Navy',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(4,3,'STR-M-BLK','size:Medium (M)|colour:Phantom Black',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(5,3,'STR-M-WHT','size:Medium (M)|colour:Bright White',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(6,3,'STR-M-NVY','size:Medium (M)|colour:Midnight Navy',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(7,3,'STR-L-BLK','size:Large (L)|colour:Phantom Black',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(8,3,'STR-L-WHT','size:Large (L)|colour:Bright White',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(9,3,'STR-L-NVY','size:Large (L)|colour:Midnight Navy',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(10,3,'STR-XL-BLK','size:X-Large (XL)|colour:Phantom Black',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(11,3,'STR-XL-WHT','size:X-Large (XL)|colour:Bright White',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(12,3,'STR-XL-NVY','size:X-Large (XL)|colour:Midnight Navy',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(13,3,'STR-XXL-BLK','size:XXL|colour:Phantom Black',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(14,3,'STR-XXL-WHT','size:XXL|colour:Bright White',699.00,549.00,8,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(15,3,'STR-XXL-NVY','size:XXL|colour:Midnight Navy',699.00,549.00,45,1,'assets/images/streetwear_tee.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(16,4,'BTL-500-BLK','size:500ml|colour:Phantom Black',549.00,449.00,30,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(17,4,'BTL-500-WHT','size:500ml|colour:Bright White',549.00,449.00,30,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(18,4,'BTL-500-NVY','size:500ml|colour:Midnight Navy',549.00,449.00,30,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(19,4,'BTL-750-BLK','size:750ml|colour:Phantom Black',699.00,599.00,30,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(20,4,'BTL-750-WHT','size:750ml|colour:Bright White',699.00,599.00,30,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(21,4,'BTL-750-NVY','size:750ml|colour:Midnight Navy',699.00,599.00,30,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(22,4,'BTL-1L-BLK','size:1000ml (1L)|colour:Phantom Black',849.00,749.00,30,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(23,4,'BTL-1L-WHT','size:1000ml (1L)|colour:Bright White',849.00,749.00,30,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(24,4,'BTL-1L-NVY','size:1000ml (1L)|colour:Midnight Navy',849.00,749.00,30,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(25,6,'STK-2IN-HOLO','size:2\" x 2\" (Pack of 50)|material:Rainbow Holographic Foil',349.00,309.00,100,1,'assets/images/custom_stickers.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(26,6,'STK-2IN-VINYL','size:2\" x 2\" (Pack of 50)|material:Waterproof Matte Vinyl',299.00,259.00,100,1,'assets/images/custom_stickers.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(27,6,'STK-3IN-HOLO','size:3\" x 3\" (Pack of 50)|material:Rainbow Holographic Foil',549.00,509.00,100,1,'assets/images/custom_stickers.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(28,6,'STK-3IN-VINYL','size:3\" x 3\" (Pack of 50)|material:Waterproof Matte Vinyl',499.00,459.00,100,1,'assets/images/custom_stickers.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(29,9,'BKM-2X6-VLV','size:2\" x 6\"|material:350 GSM Velvet Soft-Touch',549.00,449.00,88,1,'assets/images/branding_stationery.jpg',1,'2026-11-02 23:43:53','2026-11-05 06:41:37'),
(30,9,'BKM-2X6-GOLD','size:2\" x 6\"|material:400 GSM Gold Foil Accented',299.00,240.00,80,1,'assets/images/branding_stationery.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(31,9,'BKM-2X7-VLV','size:2\" x 7\"|material:350 GSM Velvet Soft-Touch',199.00,150.00,80,1,'assets/images/branding_stationery.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(32,9,'BKM-2X7-GOLD','size:2\" x 7\"|material:400 GSM Gold Foil Accented',299.00,240.00,80,1,'assets/images/branding_stationery.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(36,2,'UV-10S','size:A3 Sheet (10 Sheets)',1899.00,1599.00,150,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53'),
(37,2,'UV-25S','size:A3 Sheet (25 Sheets)',4499.00,3799.00,150,1,'assets/images/uv_dtf_drinkware.jpg',1,'2026-11-02 23:43:53','2026-11-02 23:43:53');
/*!40000 ALTER TABLE `product_variations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `title` varchar(180) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `short_desc` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `short_description` text DEFAULT NULL,
  `badge_label` varchar(50) DEFAULT NULL,
  `features_json` text DEFAULT NULL,
  `faq_json` text DEFAULT NULL,
  `is_popular` tinyint(1) DEFAULT 0,
  `starting_price` decimal(10,2) DEFAULT 0.00,
  `lead_time` varchar(100) DEFAULT '24-48 Hours',
  `min_order_qty` int(11) DEFAULT 1,
  `overview_description` text DEFAULT NULL,
  `overview_highlights` text DEFAULT NULL,
  `artwork_guidelines` text DEFAULT NULL,
  `application_instructions` text DEFAULT NULL,
  `shipping_delivery_info` text DEFAULT NULL,
  `sku` longtext DEFAULT NULL,
  `badge` longtext DEFAULT NULL,
  `price_unit` longtext DEFAULT NULL,
  `min_order` longtext DEFAULT NULL,
  `artwork_notes` longtext DEFAULT NULL,
  `application_guide` longtext DEFAULT NULL,
  `shipping_info` longtext DEFAULT NULL,
  `meta_title` longtext DEFAULT NULL,
  `meta_description` longtext DEFAULT NULL,
  `regular_price` decimal(10,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `stock_status` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_product_slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES
(1,NULL,NULL,'DTF Gang Sheet (22\" Wide)','custom-dtf-gang-sheet','Ready-to-press transfers with bright color and a soft feel. Works on cotton, polyester and blends.','Ready-to-press transfers with bright color and a soft feel. Works on cotton, polyester and blends.',NULL,'assets/images/dtf_fabric_print.jpg',0,1,1,'2026-11-12 07:05:51','2026-11-12 07:05:51',NULL,NULL,NULL,NULL,0,249.00,'24-48 Hours',1,NULL,NULL,NULL,NULL,NULL,NULL,'DTF & UV DTF','/ sheet','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(2,2,2,'3D Gloss UV DTF Decal Sheet','custom-uv-dtf-decal-sheet','Raised, glossy decals that peel and stick on glass, steel, acrylic and plastic. Waterproof once applied.','Raised, glossy decals that peel and stick on glass, steel, acrylic and plastic. Waterproof once applied.','Sheet Sizes: A4, A3, or 24-inch continuous gang sheet | Finish: High-Build Spot Gloss Varnish | Adhesion: High-Tack Industrial Acrylic','assets/images/uv_dtf_drinkware.jpg',1,1,2,'2026-11-02 03:25:04','2026-11-12 07:05:51',NULL,NULL,NULL,NULL,0,349.00,'24-48 Hours',1,NULL,NULL,NULL,NULL,NULL,'UV-001','DTF & UV DTF','/ sheet','','Vector artwork preferred. Ensure 100% vector outlines for spot gloss channels.','Step 1: Thoroughly clean the target hard surface with isopropyl alcohol and dry.\r\nStep 2: Slowly peel the white backing carrier film from the clear application tape.\r\nStep 3: Align and press the decal firmly onto the target surface.\r\nStep 4: Burnish down with a squeegee or thumb firmly across all edges for 10–15 seconds.\r\nStep 5: Slowly peel the top transparent transfer film at a 45-degree angle. No heat press required!','Turnaround: 24 to 48 hours production turnaround.\r\nPackaging: Rigid flatboard mailers with moisture protection.\r\nDispatch: Express courier delivery across PAN-India.','','',0.00,NULL,100,'in_stock'),
(3,3,3,'Oversized Streetwear T-Shirt (240 GSM)','streetwear-oversized-tee','A heavy drop-shoulder tee with your design printed in DTF. Roomy fit and a soft feel.','A heavy drop-shoulder tee with your design printed in DTF. Roomy fit and a soft feel.','Fabric: 100% Combed Cotton | Weight: 240 GSM Heavyweight | Fit: Relaxed Drop-Shoulder Oversized | Collar: 1.25\" Lycra Ribbed | Sizes: S to 3XL','assets/images/custom_tshirt_apparel.jpg',1,1,3,'2026-11-02 03:25:04','2026-11-12 07:05:51',NULL,NULL,NULL,NULL,0,449.00,'24-48 Hours',1,NULL,NULL,NULL,NULL,NULL,'STR-001','T-Shirts','/ piece','','Front Chest, Back, or Sleeve placements. Provide mockup and 300 DPI PNG.','Washing Instructions: Machine wash inside out with cold water and mild detergent.\nDrying: Tumble dry low or line dry in shade for best longevity.\nIroning: Do not iron directly on the printed graphic; iron on reverse side.','Turnaround: 2–3 business days for custom printed orders.\nPackaging: Individual poly-bagged garments inside tamper-proof OrderPrintex courier mailer.\nDelivery: PAN-India express delivery within 3–5 working days.',NULL,NULL,0.00,NULL,100,'in_stock'),
(4,4,4,'Custom Steel Thermal Bottle (750 ml)','thermal-sipper-bottle','A double-wall steel bottle with your logo printed on it. Good for gifts and team kits.','A double-wall steel bottle with your logo printed on it. Good for gifts and team kits.','Capacity: 750ml / 25oz | Material: Food-Grade SS 304 | Insulation: Double-Wall Vacuum Insulated | Finish: Matte Powder-Coat with Laser/UV DTF branding','assets/images/corporate_swag_kit.jpg',1,1,4,'2026-11-02 03:25:04','2026-11-12 07:05:51',NULL,NULL,NULL,NULL,0,599.00,'24-48 Hours',1,NULL,NULL,NULL,NULL,NULL,'BTL-001','Corporate Gifts','/ bottle','','Vector logo in AI/EPS or high-resolution PNG with transparent background.','Daily Care: Wash with warm soapy water and non-abrasive sponge.\nDishwasher: Hand wash recommended for maximum vacuum insulation life.','Turnaround: 2 business days production.\nPackaging: Individual retail presentation box inside outer shipping carton.',NULL,NULL,0.00,NULL,100,'in_stock'),
(5,5,4,'Branded Apparel Mailer Box','apparel-mailer-box','A sturdy matte black box with your logo in gold foil and gloss detail. Made for shipping apparel.','A sturdy matte black box with your logo in gold foil and gloss detail. Made for shipping apparel.','Board: 3-Ply E-Flute Corrugated | Finish: Matte Soft-Touch Lamination + Gold Foil Stamping | Dimensions: 12x9x3 inches | Closure: Self-locking tab','assets/images/packaging_box.jpg',1,1,5,'2026-11-02 03:25:04','2026-11-12 07:05:51',NULL,NULL,NULL,NULL,0,65.00,'24-48 Hours',1,NULL,NULL,NULL,NULL,NULL,'BOX-001','Packaging','/ box','','Custom dieline provided upon order confirmation. Vector artwork mandatory.',NULL,NULL,NULL,NULL,0.00,NULL,100,'in_stock'),
(6,6,2,'Custom Die-Cut Sticker Pack','holographic-stickers','Stickers cut to your shape, in holographic and UV DTF finishes. Great for brands and giveaways.','Stickers cut to your shape, in holographic and UV DTF finishes. Great for brands and giveaways.','Material: Rainbow Holographic Vinyl + 3D UV Varnish | Cut: Precision Kiss-Cut or Die-Cut | Adhesion: Extra-strength permanent adhesive | Outdoor Durability: 2+ years','assets/images/custom_stickers.jpg',1,1,6,'2026-11-02 03:25:04','2026-11-12 07:05:51',NULL,NULL,NULL,NULL,0,299.00,'24-48 Hours',1,NULL,NULL,NULL,NULL,NULL,'STK-001','Stickers','/ pack','','Include cut contour line or outline vector art in 100% magenta.','Apply onto any clean, dry, flat surface. Firmly press from center outwards to remove any air pockets.','Turnaround: 24 to 48 hours dispatch. Rigid non-bend cardboard packaging.',NULL,NULL,0.00,NULL,100,'in_stock'),
(7,3,3,'Everyday Cotton T-Shirt (180 GSM)','bio-washed-cotton-tee','A breathable combed cotton crewneck with a soft printed design. Easy to wear all day.','A breathable combed cotton crewneck with a soft printed design. Easy to wear all day.','Material: 100% Bio-Washed Combed Cotton | Weight: 180 GSM Pre-Shrunk | Fit: Regular Unisex | Seams: Double-needle reinforced | Colors: Black, Navy, White, Sage','assets/images/streetwear_tee.jpg',1,1,7,'2026-11-02 03:25:04','2026-11-12 07:05:51',NULL,NULL,NULL,NULL,0,299.00,'24-48 Hours',1,NULL,NULL,NULL,NULL,NULL,'TEE-001','T-Shirts','/ piece','','Max print area: 12x16 inches front/back. Transparent 300 DPI PNG.',NULL,NULL,NULL,NULL,0.00,NULL,100,'in_stock'),
(8,7,4,'Corporate Welcome Gift Kit','executive-welcome-hamper','A ready gift set with branded items for new joiners and clients, packed neatly.','A ready gift set with branded items for new joiners and clients, packed neatly.','Contents: 1 Ceramic Mug (11oz), 1 Hardbound Diary, 1 Metal Stylus Pen, 1 Branded Polo | Packaging: Rigid luxury presentation box with magnetic lid','assets/images/branding_stationery.jpg',1,1,8,'2026-11-02 03:25:04','2026-11-12 07:05:51',NULL,NULL,NULL,NULL,0,599.00,'24-48 Hours',1,NULL,NULL,NULL,NULL,NULL,'HMP-001','Corporate Gifts','/ kit','','Vector brand guidelines and Pantone color codes required.',NULL,NULL,NULL,NULL,0.00,NULL,100,'in_stock'),
(9,7,4,'Custom Printed Bookmarks','bookmark-03','Double-sided bookmarks on thick card, with a soft-touch finish. Good for brands, bookstores and event giveaways.','Double-sided bookmarks on thick card, with a soft-touch finish. Good for brands, bookstores and event giveaways.','350 GSM card • Double-sided print • Custom sizes','assets/images/branding_stationery.jpg',1,1,9,'2026-11-02 03:25:04','2026-11-12 07:05:51',NULL,NULL,NULL,NULL,0,1.50,'24-48 Hours',1,NULL,NULL,NULL,NULL,NULL,'BKM-001','CUSTOM STATIONERY','/ piece','Min. 100 pcs','Submit PDF or AI with 3mm bleed on all sides. CMYK color mode.','Ready-to-use premium printed bookmarks for publishing, events, and gift retail.','Turnaround: 24–48 hours dispatch. Packaged in moisture-resistant bundles.',NULL,NULL,0.00,NULL,100,'in_stock');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotation_items`
--

DROP TABLE IF EXISTS `quotation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotation_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quotation_id` int(11) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `quotation_id` (`quotation_id`),
  CONSTRAINT `quotation_items_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation_items`
--

LOCK TABLES `quotation_items` WRITE;
/*!40000 ALTER TABLE `quotation_items` DISABLE KEYS */;
INSERT INTO `quotation_items` VALUES
(5,6,'Custom Commercial DTF Transfer Roll (22\" x 40\")','22\" x 40\"',20,1250.00,25000.00,'2026-11-05 09:03:55'),
(6,7,'UV DTF Printing','12.00x232.00 in',1,0.00,0.00,'2026-11-05 09:45:58');
/*!40000 ALTER TABLE `quotation_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotations`
--

DROP TABLE IF EXISTS `quotations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_number` varchar(50) NOT NULL,
  `enquiry_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(191) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 18.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `delivery_charge` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `validity_days` int(11) DEFAULT 15,
  `valid_until` date DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `customer_feedback` text DEFAULT NULL,
  `status` enum('draft','sent','accepted','rejected','change_requested') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `quote_number` (`quote_number`),
  KEY `idx_quote_number` (`quote_number`),
  KEY `idx_quote_status` (`status`),
  KEY `enquiry_id` (`enquiry_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotations`
--

LOCK TABLES `quotations` WRITE;
/*!40000 ALTER TABLE `quotations` DISABLE KEYS */;
INSERT INTO `quotations` VALUES
(6,'QP-2026-CAADE',4,2,'Demo Customer','customer@orderprintex.com',25000.00,18.00,4500.00,400.00,29900.00,15,'2026-11-20','Prepress check completed. 300 DPI verified.','Accepted via automated test suite. Ready for prepress.','accepted','2026-11-05 09:03:55','2026-11-05 09:22:36'),
(7,'QP-2026-5B845',7,1,'OrderPrintex Administrator','admin@orderprintex.com',0.00,18.00,0.00,0.00,0.00,15,'2026-11-20','1. High-speed Japanese dual-head industrial print quality with hot-melt adhesive backing.\r\n2. Standard turn-around: 24-48 business hours after proof confirmation.\r\n3. Safe pan-India insured courier dispatch.',NULL,'sent','2026-11-05 09:45:58','2026-11-05 09:45:58');
/*!40000 ALTER TABLE `quotations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) NOT NULL,
  `title` varchar(150) NOT NULL,
  `short_description` text NOT NULL,
  `full_description` longtext NOT NULL,
  `hero_tagline` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `badge` varchar(50) DEFAULT 'Popular',
  `specifications` text DEFAULT NULL,
  `artwork_guidelines` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
(1,'dtf-printing','1.DTF Printing in Delhi','Direct-to-Film transfer printing with ultra soft-hand feel and 50+ wash cycles.','Our DTF (Direct-to-Film) printing is perfect for cotton, polyester and blended fabrics. It prints a white base under the colours so your designs stay bright even on black. Want to get started ? You can order ready-to-press transfers and gang sheets for your own pressing or we can do it for you. This service is a hit with apparel brands, resellers and print shops that need reliable bulk DTF printing.','Prints on Fabric with Bright, Soft, and Long Lasting Results','assets/images/dtf_fabric_print.jpg','1.DTF Printing in Delhi','Works on: Cotton, Polyester, Blends | Sheet width: 24 inches - Let us know if you need anything else | Order: Gang sheets and bulk rolls',NULL,1,1,'2026-11-02 03:25:04','2026-11-12 06:57:36'),
(2,'uv-dtf-printing','2. UV DTF Printing - A Popular Choice','Industrial-grade UV-cured transfer decals for glass, metal, wood, and acrylic.','Peel and stick stickers that just need to be applied - no heat press needed. The glossy layer gives them a raised 3D look, making them waterproof and scratch resistant. They work on glass, steel, acrylic, plastic, and wood, so they are super popular for mugs, tumblers, bottles, gift items and packaging. We make UV DTF stickers in custom sizes and bulk quantities.','UV DTF Printing & Stickers - Add a 3D Glossy Finish to Your Products','assets/images/uv_dtf_drinkware.jpg','2. UV DTF Printing - A Popular Choice','Layers: Colour + White + Gloss | Surfaces: Glass, Steel, Acrylic, Plastic, Wood | No heat press needed',NULL,1,2,'2026-11-02 03:25:04','2026-11-12 06:57:36'),
(3,'t-shirt-printing','Delhi\'s Custom T-Shirt Printing Studio - Where Art Meets Fabric','We take your brand from concept to production, printing vibrant high-density graphics onto those comfy 180-240 GSM bio-washed combed cotton tees with the perfect oversized fit, or the sleek athletic wear polyester. Whether you\'re just starting out or producing in bulk for your company, we\'ve got you covered.','At OrderPrintex, we bridge that gap between making a few special items for your event and churning out a thousand corporate tees in a week. Using the latest digital technology we pair with super-soft combed cotton, we bring you shirts that look and feel like the real deal.','Delhi\'s Custom T-Shirt Printing Studio - Where Art Meets Fabric','assets/images/custom_tshirt_apparel.jpg','Apparel Production & Merchandise - Made to Order','Fabrics: Cotton, Poly-Rich, Dry-Fit | Sizes: XS to 3XL | Orders: Single sample to bulk',NULL,1,3,'2026-11-02 03:25:04','2026-11-12 08:10:22'),
(4,'corporate-printing','4. Corporate Printing Services for Businesses','End-to-end branded merchandise, drinkware, and executive employee welcome kits.','Need branded merchandise for your company or event ? Printex is here to help. We print uniform t-shirts, branded mugs and bottles, and giveaways with your logo, in the same colours across the whole batch. GST invoice available on request.','Corporate T-Shirt & Merchandise Printing - For Offices, Events and Brands','assets/images/corporate_swag_kit.jpg','4. Corporate Printing Services for Businesses','Products: T-Shirts, Mugs, Bottles, Giveaways | Invoice: GST on request | Delivery: Delhi and pan-India',NULL,1,4,'2026-11-02 03:25:04','2026-11-12 06:57:36');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `group_name` varchar(50) DEFAULT 'general',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES
(1,'site_name','OrderPrintex','general','2026-11-02 03:25:04'),
(2,'tagline','Industrial Printing Studio — Delhi, India','general','2026-11-02 03:25:04'),
(3,'phone','+91 93540 28199','contact','2026-11-02 03:25:04'),
(4,'phone_raw','919354028199','contact','2026-11-02 03:25:04'),
(5,'email','sales@orderprintex.com','contact','2026-11-02 03:25:04'),
(6,'address','Shop E-6, Chanakya Palace, Street No 21, Pankha Rd, near 40 Feet Road, Block C, Sitapuri Part 1, Rani Bagh, Delhi, 110059','contact','2026-11-02 03:25:04'),
(7,'announcement_enabled','1','announcement','2026-11-02 03:25:04'),
(8,'announcement_speed','4500','announcement','2026-11-02 03:25:04'),
(9,'whatsapp','+91 93540 28199','contact','2026-11-02 23:43:53'),
(10,'whatsapp_raw','919354028199','contact','2026-11-02 23:43:53'),
(11,'business_hours','Mon - Sat: 9:30 AM - 8:00 PM','contact','2026-11-02 23:43:53'),
(12,'social_instagram','https://www.instagram.com/order_printex','social','2026-11-05 07:50:53'),
(13,'social_facebook','https://www.facebook.com/orderprintex','social','2026-11-02 23:43:53'),
(14,'social_youtube','https://www.youtube.com/@orderprintex','social','2026-11-02 23:43:53'),
(15,'social_linkedin','https://www.linkedin.com/company/orderprintex','social','2026-11-02 23:43:53'),
(16,'top_bar_enabled','1','general','2026-11-02 23:43:53'),
(17,'meta_title','OrderPrintex — Commercial Printing Studio & Direct Manufacturer Delhi','seo','2026-11-02 23:43:53'),
(18,'meta_description','OrderPrintex is India\'s premier bulk manufacturer of DTF apparel transfers, UV DTF decals, custom streetwear t-shirts, and corporate merchandise with pan-India express dispatch.','seo','2026-11-02 23:43:53'),
(19,'seo_last_generated','2026-11-12 13:05:13','seo','2026-11-12 07:35:13'),
(20,'seo_generation_status','success','seo','2026-11-05 05:48:34'),
(21,'seo_url_count','41','seo','2026-11-12 07:35:13'),
(22,'site_tagline','Manufacturer & Bulk Supplier of DTF and UV DTF in India','general','2026-11-05 07:50:53'),
(23,'whatsapp_prefill','Hi OrderPrintex, I would like to inquire about printing services.','contact','2026-11-05 07:50:53'),
(24,'google_maps_url','https://share.google/MWOVYne70asGLsYXn','contact','2026-11-05 07:50:53'),
(25,'operating_hours','Mon - Sat: 9:30 AM - 8:00 PM','contact','2026-11-05 07:50:53'),
(26,'meta_og_image','assets/images/gallery_1.jpg','seo','2026-11-05 07:50:53'),
(27,'default_gst_rate','18.0','commerce','2026-11-05 07:50:53'),
(28,'default_delivery','0.00','commerce','2026-11-05 07:50:53'),
(29,'quote_validity','15','commerce','2026-11-05 07:50:53'),
(30,'whatsapp_url','https://wa.me/919354028199?text=Hi+OrderPrintex%2C+I+would+like+to+inquire+about+printing+services.','contact','2026-11-05 07:50:53'),
(31,'instagram_url','https://www.instagram.com/order_printex','social','2026-11-05 07:50:53'),
(32,'facebook_url','https://www.facebook.com/orderprintex','social','2026-11-05 07:50:53'),
(33,'youtube_url','https://www.youtube.com/@orderprintex','social','2026-11-05 07:50:53'),
(34,'linkedin_url','https://www.linkedin.com/company/orderprintex','social','2026-11-05 07:50:53'),
(35,'seo_products_count','9','seo','2026-11-12 07:35:13'),
(36,'seo_services_count','4','seo','2026-11-05 09:36:06'),
(37,'seo_categories_count','7','seo','2026-11-05 09:36:06'),
(38,'seo_articles_count','8','seo','2026-11-05 09:36:06'),
(39,'seo_portfolio_count','7','seo','2026-11-05 09:37:14');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_wishlists`
--

DROP TABLE IF EXISTS `user_wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_wishlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product_wishlist` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `user_wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_wishlists`
--

LOCK TABLES `user_wishlists` WRITE;
/*!40000 ALTER TABLE `user_wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `company_name` varchar(150) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `role` enum('customer','admin','staff') DEFAULT 'customer',
  `status` enum('active','suspended') DEFAULT 'active',
  `email_verified_at` datetime DEFAULT NULL,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `email_verification_expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'OrderPrintex Administrator','admin@orderprintex.com','$2y$10$HfojVQb1AqkPgI1C6aTxhetBIFlODC/K4IzAATm6e5ZRgLtmtrXI.','+91 93540 28199',NULL,NULL,NULL,NULL,NULL,NULL,'admin','active','2026-11-05 20:07:20',NULL,NULL,'2026-11-02 03:25:04','2026-11-05 14:57:26'),
(2,'Demo Customer','customer@orderprintex.com','$2y$10$bdYiQq/R/ikTF4b8kyLypeIEw.88av95ujy/k3b2d.HxWEAvmqw1K','+91 98765 43210',NULL,NULL,NULL,NULL,NULL,NULL,'customer','active',NULL,NULL,NULL,'2026-11-02 03:25:04','2026-11-05 08:56:04'),
(3,'abc','abc@gmail.com','$2y$10$iw7qKgWnnT4DlgzrH4WE..UmnaklmVrQ6EoqzqDldylWlQzNP0YBe','12345','',NULL,'R 11 GURPREET NAGAR','NEW DELHI','','110059','customer','active',NULL,NULL,NULL,'2026-11-02 23:54:14','2026-11-02 23:54:14'),
(4,'abc','abcd@gmail.com','$2y$10$HVDJr8YNxltlnBDodAx7VeeId1Me.qNXL6UxxOVMaPvrZvUbNetrO','656565656','',NULL,'R 11 GURPREET NAGAR','NEW DELHI','','110059','customer','active',NULL,NULL,NULL,'2026-11-05 07:59:47','2026-11-05 07:59:47'),
(6,'Aditya Kumar','aditya@gmail.com','$2y$10$EjAUXlDDRzR8RFzJVm0Qt.IbccMOEVlCvnnI2gbWqiy0TMgYP.Gnm','+91 6121216543',NULL,'LOGISTIC',NULL,NULL,NULL,NULL,'admin','suspended',NULL,NULL,NULL,'2026-11-05 10:01:41','2026-11-05 15:25:25'),
(8,'adi','nani@gmail.com','$2y$10$i036Om2I5RqgnEs05mOEJuSEOMeSDVkddKaTo2tecdqdzHSJ8b/0u','123456789',NULL,NULL,NULL,NULL,NULL,NULL,'staff','active',NULL,NULL,NULL,'2026-11-05 10:14:02','2026-11-05 10:20:23'),
(15,'Staff Custom Dept','staff_dept_1793892397792@orderprintex.com','$2y$10$VABQoObN4AwcixnyfaptSeO/bjH4sLrUxYkWMCwy8jf9UAxBPyfz2','+91 9811122233',NULL,'International Logistics Desk 1793892397792',NULL,NULL,NULL,NULL,'staff','active',NULL,'c95927d3cb4d0d3c9862d4fdbdd63394ebdadd83694c967fa02b6ecbd976cf10','2026-11-07 20:56:38','2026-11-05 15:26:38','2026-11-05 15:26:38'),
(16,'Staff Custom Dept','staff_dept_1793892451530@orderprintex.com','$2y$10$uHtEz55qSXLiFF9v7j9wfeTy7XGSDuXz2G1Jlt2gM36vpg4JG.Piu','+91 9811122233',NULL,'International Logistics Desk 1793892451530',NULL,NULL,NULL,NULL,'staff','active',NULL,'aa4f255acbe88048bf95e067eb92547539a0141ba2b79555792736888525b339','2026-11-07 20:57:31','2026-11-05 15:27:31','2026-11-05 15:27:31');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

COMMIT;
SET AUTOCOMMIT = 1;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- ==========================================================
-- ORDERPRINTEX Database Dump Successfully Completed
-- ==========================================================
