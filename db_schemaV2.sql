-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: bookshop
-- ------------------------------------------------------
-- Server version	8.0.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `order_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `product_price` decimal(10,0) DEFAULT NULL,
  `qty` decimal(10,0) DEFAULT NULL,
  `row_total` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,7,20,4,80),(2,1,3,71,45,3195);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `order_total` decimal(10,0) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,3275,'2024-10-24 01:50:27','2024-10-24 01:50:27');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `quantity` int NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'sdf',42,'Books',1,'2024-10-23 23:24:51','2024-10-24 16:11:53',100,50.00),(3,'Eraser',71,'Stationery',3,'2024-10-23 23:24:51','2024-10-23 23:24:51',150,80.00),(6,'Ruler',28,'Stationery',6,'2024-10-23 23:24:51','2024-10-23 23:24:51',250,35.00),(7,'Glue Stick',20,'Stationery',7,'2024-10-23 23:24:51','2024-10-23 23:24:51',400,30.00),(8,'Scissors',97,'Stationery',8,'2024-10-23 23:24:51','2024-10-23 23:24:51',150,105.00),(14,'sdfsfgsfg',50,'Books',10,'2024-10-24 16:12:17','2024-10-24 16:12:17',4,8.02);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `supplier_id` int NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'ABC Books','John Doe','1234567890','john.doe@abcbooks.com','123 Main St, Cityville','2024-08-17 05:17:50','2024-08-17 05:17:50'),(2,'Global Publishing','Jane Smith','0987654321','jane.smith@globalpublishing.com','456 Elm St, Townsville','2024-08-17 05:17:50','2024-08-17 05:17:50'),(3,'Print Media','Alice Johnson','1122334455','alice.johnson@printmedia.com','789 Oak St, Villageville','2024-08-17 05:17:50','2024-08-17 05:17:50'),(4,'Reading World','Bob Brown','5566778899','bob.brown@readingworld.com','101 Pine St, Hamlet','2024-08-17 05:17:50','2024-08-17 05:17:50'),(5,'Book Distributors','Charlie Clark','6677889900','charlie.clark@bookdistributors.com','202 Maple St, Suburbia','2024-08-17 05:17:50','2024-08-17 05:17:50'),(6,'Scholarly Press','Diana Green','7788990011','diana.green@scholarlypress.com','303 Cedar St, Metropolis','2024-08-17 05:17:50','2024-08-17 05:17:50'),(7,'Knowledge House','Eve White','8899001122','eve.white@knowledgehouse.com','404 Birch St, Uptown','2024-08-17 05:17:50','2024-08-17 05:17:50'),(8,'Literature Inc.','Frank Black','9900112233','frank.black@literatureinc.com','505 Spruce St, Downtown','2024-08-17 05:17:50','2024-08-17 05:17:50'),(9,'Education First','Grace Blue','0011223344','grace.blue@educationfirst.com','606 Willow St, Midlands','2024-08-17 05:17:50','2024-08-17 05:17:50'),(10,'Book Haven','Henry Gold','1122335566','henry.gold@bookhaven.com','707 Fir St, Outskirts','2024-08-17 05:17:50','2024-08-17 05:17:50');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `user_role_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`user_role_id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `role` enum('admin','manager','staff') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','blocked') NOT NULL DEFAULT 'active',
  `login_attempts` int DEFAULT '0',
  `blocked_until` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'e99a18c428cb38d5f260853678922e03','kamal.indika.n@gmail.com','Test-001','Nanayakkara','admin','2024-08-16 12:19:53','2024-10-22 07:32:35','1510930d3e43866f8bf7744fc5156d3f8419dee99a98984689edee6c4c03c620','2024-10-15 01:41:46','active',0,NULL),(2,'443e5a43a3a3e8e9ef46bfa37bef7600','kamal1.indika.n@gmail.com','Kamal Indika','Nanayakkara','admin','2024-08-16 14:08:13','2024-08-16 14:08:13',NULL,NULL,'active',0,NULL),(5,'60ee0a829b08e615916bb0e5d012270d','indika@12.cvb','Piyadasa','Balagamage','staff','2024-08-17 01:01:01','2024-08-17 01:15:14',NULL,NULL,'active',0,NULL),(6,'443e5a43a3a3e8e9ef46bfa37bef7600','indika@gmail.com','Kamal Indika','Nanayakkara rerere','admin','2024-08-18 16:57:30','2024-08-18 16:57:46',NULL,NULL,'active',0,NULL),(8,'443e5a43a3a3e8e9ef46bfa37bef7600','indika1231231@gmail.com','Kamal Indika','Nanayakkara','manager','2024-08-18 17:02:52','2024-08-18 17:03:03',NULL,NULL,'active',0,NULL),(9,'443e5a43a3a3e8e9ef46bfa37bef7600','indika122@gmail.com','Kamal Indika','Nanayakkara','admin','2024-08-18 17:14:59','2024-08-18 17:14:59',NULL,NULL,'active',0,NULL),(10,'443e5a43a3a3e8e9ef46bfa37bef7600','kamal@234.com','Kamal Indika','Nanayakkara','manager','2024-08-18 17:28:39','2024-08-18 17:28:39',NULL,NULL,'active',0,NULL),(16,'$2y$10$8NrsIZdYsM.Y3I2RCqVJCuj7zLlYD1ETzi2GCp/vh3v6mjbBFh1ky','staff@gmail.com','Staff ','user','staff','2024-10-22 08:05:16','2024-10-22 08:42:33',NULL,NULL,'active',4,'2024-10-22 02:57:33'),(18,'3e0cd7dbf477f6da9831acd7c1d617bc','staff1@gmail.com','staff','test','staff','2024-10-22 08:34:13','2024-10-22 08:43:55',NULL,NULL,'active',0,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-25  0:35:02
