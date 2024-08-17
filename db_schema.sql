/*

Explanation:
Database: The database is named bookshop.

User Management Module:

users table: Stores user information.
roles table: Stores user roles.
user_roles table: Manages the many-to-many relationship between users and roles.
Inventory Management Module:

items table: Stores information about the items in the inventory.
inventory_log table: Tracks changes in inventory levels.
Supplier Management Module:

suppliers table: Stores supplier information.
supplier_orders table: Manages orders placed to suppliers.
Order Management (POS) Module:

customer_orders table: Stores customer order information.
order_items table: Manages the items included in each customer order.
Reporting and Analytics Module:

sales_report, inventory_report, supplier_performance views: Provide various reports based on existing data.


*/

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
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `role` enum('admin','manager','staff') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Indika','e99a18c428cb38d5f260853678922e03','kamal.indika.n@gmail.com','Indika','test','staff','2024-08-16 12:19:53','2024-08-16 14:10:14','1a7d3d2092c36ef167ad88775bd1226f1d866c861b3b69d78172eee389c8d517','2024-08-16 14:29:02'),(2,'admin','443e5a43a3a3e8e9ef46bfa37bef7600','kamal1.indika.n@gmail.com','Kamal Indika','Nanayakkara','admin','2024-08-16 14:08:13','2024-08-16 14:08:13',NULL,NULL);


-- Dump completed on 2024-08-16 23:29:29


ALTER TABLE users 
DROP COLUMN username,
MODIFY COLUMN email VARCHAR(255) NOT NULL,
MODIFY COLUMN password VARCHAR(255) NOT NULL,
ADD COLUMN status ENUM('active', 'inactive', 'blocked') NOT NULL DEFAULT 'active',
ADD COLUMN login_attempts INT DEFAULT 0,
ADD COLUMN blocked_until TIMESTAMP NULL;

CREATE TABLE `products` (
                            `id` bigint NOT NULL AUTO_INCREMENT,
                            `product_name` varchar(255) NOT NULL,
                            `price` decimal(10,0) DEFAULT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `orders` (
                          `id` bigint NOT NULL AUTO_INCREMENT,
                          `order_total` decimal(10,0) DEFAULT NULL,
                          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `order_items` (
                               `id` bigint NOT NULL AUTO_INCREMENT,
                               `order_id` bigint DEFAULT NULL,
                               `product_id` bigint DEFAULT NULL,
                               `product_price` decimal(10,0) DEFAULT NULL,
                               `qty` decimal(10,0) DEFAULT NULL,
                               `row_total` decimal(10,0) DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO products (id, product_name, price) VALUES (1, 'Pen', 42);
INSERT INTO products (id, product_name, price) VALUES (2, 'Pencil', 41);
INSERT INTO products (id, product_name, price) VALUES (3, 'Eraser', 71);
INSERT INTO products (id, product_name, price) VALUES (4, 'Sharpener', 40);
INSERT INTO products (id, product_name, price) VALUES (5, 'Notebook', 67);
INSERT INTO products (id, product_name, price) VALUES (6, 'Ruler', 28);
INSERT INTO products (id, product_name, price) VALUES (7, 'Glue Stick', 20);
INSERT INTO products (id, product_name, price) VALUES (8, 'Scissors', 97);
INSERT INTO products (id, product_name, price) VALUES (9, 'Stapler', 53);
INSERT INTO products (id, product_name, price) VALUES (10, 'Staples', 53);
INSERT INTO products (id, product_name, price) VALUES (11, 'Paper Clips', 10);
INSERT INTO products (id, product_name, price) VALUES (12, 'Binder Clips', 59);
INSERT INTO products (id, product_name, price) VALUES (13, 'Highlighter', 76);
INSERT INTO products (id, product_name, price) VALUES (14, 'Marker', 16);
INSERT INTO products (id, product_name, price) VALUES (15, 'Whiteboard Marker', 20);
INSERT INTO products (id, product_name, price) VALUES (16, 'Permanent Marker', 45);
INSERT INTO products (id, product_name, price) VALUES (17, 'Correction Tape', 64);
INSERT INTO products (id, product_name, price) VALUES (18, 'Correction Fluid', 86);
INSERT INTO products (id, product_name, price) VALUES (19, 'Sticky Notes', 47);
INSERT INTO products (id, product_name, price) VALUES (20, 'Index Cards', 56);
INSERT INTO products (id, product_name, price) VALUES (21, 'File Folder', 43);
INSERT INTO products (id, product_name, price) VALUES (22, 'Document Wallet', 35);
INSERT INTO products (id, product_name, price) VALUES (23, 'Ring Binder', 39);
INSERT INTO products (id, product_name, price) VALUES (24, 'Lever Arch File', 77);
INSERT INTO products (id, product_name, price) VALUES (25, 'Clipboard', 81);
INSERT INTO products (id, product_name, price) VALUES (26, 'Calculator', 76);
INSERT INTO products (id, product_name, price) VALUES (27, 'Desk Organizer', 34);
INSERT INTO products (id, product_name, price) VALUES (28, 'Desk Pad', 26);
INSERT INTO products (id, product_name, price) VALUES (29, 'Mouse Pad', 16);
INSERT INTO products (id, product_name, price) VALUES (30, 'Keyboard Cleaner', 82);
INSERT INTO products (id, product_name, price) VALUES (31, 'Desk Lamp', 83);
INSERT INTO products (id, product_name, price) VALUES (32, 'Bookends', 69);
INSERT INTO products (id, product_name, price) VALUES (33, 'Magazine Holder', 86);
INSERT INTO products (id, product_name, price) VALUES (34, 'Letter Tray', 35);
INSERT INTO products (id, product_name, price) VALUES (35, 'Pen Holder', 89);
INSERT INTO products (id, product_name, price) VALUES (36, 'Tape Dispenser', 58);
INSERT INTO products (id, product_name, price) VALUES (37, 'Packing Tape', 16);
INSERT INTO products (id, product_name, price) VALUES (38, 'Double-sided Tape', 74);
INSERT INTO products (id, product_name, price) VALUES (39, 'Masking Tape', 45);
INSERT INTO products (id, product_name, price) VALUES (40, 'Duct Tape', 81);
INSERT INTO products (id, product_name, price) VALUES (41, 'Rubber Bands', 80);
INSERT INTO products (id, product_name, price) VALUES (42, 'Thumb Tacks', 56);
INSERT INTO products (id, product_name, price) VALUES (43, 'Push Pins', 34);
INSERT INTO products (id, product_name, price) VALUES (44, 'Paper Punch', 81);
INSERT INTO products (id, product_name, price) VALUES (45, 'Staple Remover', 24);
INSERT INTO products (id, product_name, price) VALUES (46, 'Label Maker', 45);
INSERT INTO products (id, product_name, price) VALUES (47, 'Labels', 56);
INSERT INTO products (id, product_name, price) VALUES (48, 'Envelope', 45);
INSERT INTO products (id, product_name, price) VALUES (49, 'Bubble Wrap', 49);
INSERT INTO products (id, product_name, price) VALUES (50, 'Packing Peanuts', 99);
INSERT INTO products (id, product_name, price) VALUES (51, 'Shipping Box', 70);
INSERT INTO products (id, product_name, price) VALUES (52, 'Mailing Tube', 41);
INSERT INTO products (id, product_name, price) VALUES (53, 'Clipboard', 75);
INSERT INTO products (id, product_name, price) VALUES (54, 'Whiteboard', 62);
INSERT INTO products (id, product_name, price) VALUES (55, 'Corkboard', 78);
INSERT INTO products (id, product_name, price) VALUES (56, 'Bulletin Board', 16);
INSERT INTO products (id, product_name, price) VALUES (57, 'Dry Erase Board', 13);
INSERT INTO products (id, product_name, price) VALUES (58, 'Chalkboard', 99);
INSERT INTO products (id, product_name, price) VALUES (59, 'Chalk', 87);
INSERT INTO products (id, product_name, price) VALUES (60, 'Whiteboard Eraser', 39);
INSERT INTO products (id, product_name, price) VALUES (61, 'Chalkboard Eraser', 16);
INSERT INTO products (id, product_name, price) VALUES (62, 'Push Pin Magnets', 41);
INSERT INTO products (id, product_name, price) VALUES (63, 'Magnetic Clips', 59);
INSERT INTO products (id, product_name, price) VALUES (64, 'Binder', 72);
INSERT INTO products (id, product_name, price) VALUES (65, 'Dividers', 84);
INSERT INTO products (id, product_name, price) VALUES (66, 'Sheet Protectors', 15);
INSERT INTO products (id, product_name, price) VALUES (67, 'Presentation Folder', 85);
INSERT INTO products (id, product_name, price) VALUES (68, 'Report Cover', 97);
INSERT INTO products (id, product_name, price) VALUES (69, 'Business Card Holder', 43);
INSERT INTO products (id, product_name, price) VALUES (70, 'Name Badge', 92);
INSERT INTO products (id, product_name, price) VALUES (71, 'ID Card Holder', 55);
INSERT INTO products (id, product_name, price) VALUES (72, 'Lanyard', 76);
INSERT INTO products (id, product_name, price) VALUES (73, 'Badge Reel', 26);
INSERT INTO products (id, product_name, price) VALUES (74, 'Visitor Badge', 72);
INSERT INTO products (id, product_name, price) VALUES (75, 'Conference Badge', 94);
INSERT INTO products (id, product_name, price) VALUES (76, 'Desk Calendar', 66);
INSERT INTO products (id, product_name, price) VALUES (77, 'Wall Calendar', 37);
INSERT INTO products (id, product_name, price) VALUES (78, 'Planner', 66);
INSERT INTO products (id, product_name, price) VALUES (79, 'Journal', 30);
INSERT INTO products (id, product_name, price) VALUES (80, 'Diary', 33);
INSERT INTO products (id, product_name, price) VALUES (81, 'Address Book', 66);
INSERT INTO products (id, product_name, price) VALUES (82, 'Phone Book', 40);
INSERT INTO products (id, product_name, price) VALUES (83, 'Sticky Flags', 84);
INSERT INTO products (id, product_name, price) VALUES (84, 'Page Markers', 20);
INSERT INTO products (id, product_name, price) VALUES (85, 'Binder Rings', 20);
INSERT INTO products (id, product_name, price) VALUES (86, 'Hole Reinforcements', 29);
INSERT INTO products (id, product_name, price) VALUES (87, 'Book Cover', 74);
INSERT INTO products (id, product_name, price) VALUES (88, 'Book Stand', 95);
INSERT INTO products (id, product_name, price) VALUES (89, 'Book Light', 64);
INSERT INTO products (id, product_name, price) VALUES (90, 'Reading Glasses', 26);
INSERT INTO products (id, product_name, price) VALUES (91, 'Magnifying Glass', 19);
INSERT INTO products (id, product_name, price) VALUES (92, 'Desk Fan', 98);
INSERT INTO products (id, product_name, price) VALUES (93, 'Desk Heater', 60);
INSERT INTO products (id, product_name, price) VALUES (94, 'Desk Organizer Tray', 90);
INSERT INTO products (id, product_name, price) VALUES (95, 'Desk Drawer Organizer', 80);
INSERT INTO products (id, product_name, price) VALUES (96, 'Desk Shelf', 31);
INSERT INTO products (id, product_name, price) VALUES (97, 'Desk Mat', 85);
INSERT INTO products (id, product_name, price) VALUES (98, 'Desk Chair Mat', 53);
INSERT INTO products (id, product_name, price) VALUES (99, 'Foot Rest', 89);
INSERT INTO products (id, product_name, price) VALUES (100, 'Monitor Stand', 98);
