-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.26-0ubuntu0.16.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             9.5.0.5293
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table tout-paie.add_product
CREATE TABLE IF NOT EXISTS `add_product` (
  `APId` int(11) NOT NULL AUTO_INCREMENT,
  `SId_fk` int(11) NOT NULL DEFAULT '0',
  `PGId_fk` int(11) NOT NULL DEFAULT '0',
  `product_name` varchar(100) DEFAULT NULL,
  `short_product_description` varchar(200) DEFAULT NULL,
  `long_product_description` text,
  `product_type_fk` int(11) DEFAULT NULL,
  PRIMARY KEY (`APId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.add_product: ~0 rows (approximately)
/*!40000 ALTER TABLE `add_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `add_product` ENABLE KEYS */;

-- Dumping structure for table tout-paie.department
CREATE TABLE IF NOT EXISTS `department` (
  `Did` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Did`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.department: ~0 rows (approximately)
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
/*!40000 ALTER TABLE `department` ENABLE KEYS */;

-- Dumping structure for table tout-paie.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `GId` int(11) NOT NULL AUTO_INCREMENT,
  `created_date` datetime DEFAULT NULL,
  `group_name` varchar(100) DEFAULT NULL,
  `group_icon` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT '0',
  `group_status` varchar(10) DEFAULT 'Active',
  PRIMARY KEY (`GId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.groups: ~0 rows (approximately)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Dumping structure for table tout-paie.group_messages
CREATE TABLE IF NOT EXISTS `group_messages` (
  `GMId` int(11) NOT NULL AUTO_INCREMENT,
  `GId_fk` int(11) NOT NULL DEFAULT '0',
  `message` text,
  `message_date` datetime DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`GMId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.group_messages: ~0 rows (approximately)
/*!40000 ALTER TABLE `group_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_messages` ENABLE KEYS */;

-- Dumping structure for table tout-paie.group_user
CREATE TABLE IF NOT EXISTS `group_user` (
  `GUId` int(11) NOT NULL AUTO_INCREMENT,
  `UId_fk` int(11) NOT NULL DEFAULT '0',
  `GId_fk` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`GUId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.group_user: ~0 rows (approximately)
/*!40000 ALTER TABLE `group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_user` ENABLE KEYS */;

-- Dumping structure for table tout-paie.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `PId` int(11) NOT NULL AUTO_INCREMENT,
  `post_date` datetime DEFAULT NULL,
  `post_description` text,
  PRIMARY KEY (`PId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.posts: ~0 rows (approximately)
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;

-- Dumping structure for table tout-paie.posts_comments
CREATE TABLE IF NOT EXISTS `posts_comments` (
  `PCId` int(11) NOT NULL AUTO_INCREMENT,
  `PId_fk` int(11) NOT NULL DEFAULT '0',
  `comments_datetime` datetime DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`PCId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.posts_comments: ~0 rows (approximately)
/*!40000 ALTER TABLE `posts_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts_comments` ENABLE KEYS */;

-- Dumping structure for table tout-paie.posts_likes
CREATE TABLE IF NOT EXISTS `posts_likes` (
  `PLId` int(11) NOT NULL AUTO_INCREMENT,
  `PId_fk` int(11) NOT NULL DEFAULT '0',
  `like_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`PLId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.posts_likes: ~0 rows (approximately)
/*!40000 ALTER TABLE `posts_likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts_likes` ENABLE KEYS */;

-- Dumping structure for table tout-paie.prodcut_sell
CREATE TABLE IF NOT EXISTS `prodcut_sell` (
  `PSId` int(11) NOT NULL AUTO_INCREMENT,
  `PId_fk` int(11) NOT NULL DEFAULT '0',
  `UId_fk` int(11) NOT NULL DEFAULT '0',
  `total_amount` int(11) NOT NULL DEFAULT '0',
  `sell_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`PSId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.prodcut_sell: ~0 rows (approximately)
/*!40000 ALTER TABLE `prodcut_sell` DISABLE KEYS */;
/*!40000 ALTER TABLE `prodcut_sell` ENABLE KEYS */;

-- Dumping structure for table tout-paie.product_group
CREATE TABLE IF NOT EXISTS `product_group` (
  `PGid` int(11) NOT NULL AUTO_INCREMENT,
  `SId_fk` int(11) NOT NULL DEFAULT '0',
  `group_name` varchar(100) DEFAULT NULL,
  `group_illustration_photo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`PGid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.product_group: ~0 rows (approximately)
/*!40000 ALTER TABLE `product_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_group` ENABLE KEYS */;

-- Dumping structure for table tout-paie.product_images
CREATE TABLE IF NOT EXISTS `product_images` (
  `PIId` int(11) NOT NULL AUTO_INCREMENT,
  `APId` int(11) NOT NULL DEFAULT '0',
  `image` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`PIId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.product_images: ~0 rows (approximately)
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;

-- Dumping structure for table tout-paie.product_ratings
CREATE TABLE IF NOT EXISTS `product_ratings` (
  `PRId` int(11) NOT NULL AUTO_INCREMENT,
  `rating` int(11) NOT NULL DEFAULT '0',
  `PId_fk` int(11) NOT NULL DEFAULT '0',
  `UId_fk` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`PRId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.product_ratings: ~0 rows (approximately)
/*!40000 ALTER TABLE `product_ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_ratings` ENABLE KEYS */;

-- Dumping structure for table tout-paie.product_sell_items
CREATE TABLE IF NOT EXISTS `product_sell_items` (
  `PSIId` int(11) NOT NULL AUTO_INCREMENT,
  `PSId_fk` int(11) NOT NULL DEFAULT '0',
  `PId_fk` int(11) NOT NULL DEFAULT '0',
  `SId_fk` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `product_amount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`PSIId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.product_sell_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `product_sell_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_sell_items` ENABLE KEYS */;

-- Dumping structure for table tout-paie.product_type
CREATE TABLE IF NOT EXISTS `product_type` (
  `PTId` int(11) NOT NULL AUTO_INCREMENT,
  `product_type` varchar(100) DEFAULT NULL,
  `promo_price` varchar(100) DEFAULT NULL,
  `sell_price` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`PTId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.product_type: ~0 rows (approximately)
/*!40000 ALTER TABLE `product_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_type` ENABLE KEYS */;

-- Dumping structure for table tout-paie.store
CREATE TABLE IF NOT EXISTS `store` (
  `Sid` int(11) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(200) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `file_type` varchar(20) DEFAULT NULL,
  `description` text,
  `phone_no` varchar(16) DEFAULT NULL,
  `main_address_name_of_the_street` varchar(200) DEFAULT NULL,
  `main_address_postal_code` varchar(50) DEFAULT NULL,
  `main_address_city` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Sid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.store: ~0 rows (approximately)
/*!40000 ALTER TABLE `store` DISABLE KEYS */;
/*!40000 ALTER TABLE `store` ENABLE KEYS */;

-- Dumping structure for table tout-paie.store_categories
CREATE TABLE IF NOT EXISTS `store_categories` (
  `SCid` int(11) NOT NULL AUTO_INCREMENT,
  `store_id_fk` int(11) NOT NULL DEFAULT '0',
  `delivery_price` double NOT NULL DEFAULT '0',
  `minimum_for_delivery` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`SCid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.store_categories: ~0 rows (approximately)
/*!40000 ALTER TABLE `store_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `store_categories` ENABLE KEYS */;

-- Dumping structure for table tout-paie.store_categories_items
CREATE TABLE IF NOT EXISTS `store_categories_items` (
  `SCIid` int(11) NOT NULL AUTO_INCREMENT,
  `SCId_fk` int(11) NOT NULL DEFAULT '0',
  `PId_fk` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`SCIid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.store_categories_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `store_categories_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `store_categories_items` ENABLE KEYS */;

-- Dumping structure for table tout-paie.store_other_address
CREATE TABLE IF NOT EXISTS `store_other_address` (
  `SOAid` int(11) NOT NULL AUTO_INCREMENT,
  `SId_fk` int(11) NOT NULL DEFAULT '0',
  `other_address_name_of_the_street` varchar(200) DEFAULT NULL,
  `other_address_postal_code` varchar(30) DEFAULT NULL,
  `other_address_city` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`SOAid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.store_other_address: ~0 rows (approximately)
/*!40000 ALTER TABLE `store_other_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `store_other_address` ENABLE KEYS */;

-- Dumping structure for table tout-paie.users
CREATE TABLE IF NOT EXISTS `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `department_id_fk` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `status` varchar(15) DEFAULT 'Active',
  `profile_photo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.users: ~2 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`Id`, `email`, `password`, `remember_token`, `name`, `first_name`, `birth_date`, `department_id_fk`, `role`, `status`, `profile_photo`) VALUES
	(1, 'mahesh123@gmail.com', '$2y$10$jRD51RpeXE9qg74SSsmxfONsWgk475w.VpM/aQ0DLXMdhgQiH.ySG', 'uCQqdJtzAy4a3nHgMR3O8KQV1WT20uAH2s644Aceps4PwLpejs1nWdF0uQPO', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 'test123@gmail.com', '$2y$10$L.bjLOx8xklfTb0AqU94P.NSrYOOy3ulkpDWV0M4NaoaxRSwlP6yS', 'MMryJK5cjowtsMN5kRitN712sL34ZDvrEG7eYQ6HGua7ObBerM6aWv1v7pUg', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table tout-paie.users_favorites_store
CREATE TABLE IF NOT EXISTS `users_favorites_store` (
  `FSId` int(11) NOT NULL AUTO_INCREMENT,
  `SId_fk` int(11) NOT NULL DEFAULT '0',
  `UId_fk` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`FSId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.users_favorites_store: ~0 rows (approximately)
/*!40000 ALTER TABLE `users_favorites_store` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_favorites_store` ENABLE KEYS */;

-- Dumping structure for table tout-paie.user_ads
CREATE TABLE IF NOT EXISTS `user_ads` (
  `UAid` int(11) NOT NULL AUTO_INCREMENT,
  `UId_fk` int(11) NOT NULL DEFAULT '0',
  `type_search_or_sell` varchar(10) DEFAULT NULL,
  `item_name` varchar(50) DEFAULT NULL,
  `article_description` text,
  `item_price` varchar(50) DEFAULT NULL,
  `street_name` varchar(200) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `item_status` varchar(10) DEFAULT 'In Stock',
  PRIMARY KEY (`UAid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.user_ads: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_ads` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_ads` ENABLE KEYS */;

-- Dumping structure for table tout-paie.user_ads_images
CREATE TABLE IF NOT EXISTS `user_ads_images` (
  `UAIId` int(11) NOT NULL AUTO_INCREMENT,
  `UAId_fk` int(11) NOT NULL DEFAULT '0',
  `image` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`UAIId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.user_ads_images: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_ads_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_ads_images` ENABLE KEYS */;

-- Dumping structure for table tout-paie.user_ads_message
CREATE TABLE IF NOT EXISTS `user_ads_message` (
  `UAMId` int(11) NOT NULL AUTO_INCREMENT,
  `UId_fk` int(11) NOT NULL DEFAULT '0',
  `message` text,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`UAMId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.user_ads_message: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_ads_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_ads_message` ENABLE KEYS */;

-- Dumping structure for table tout-paie.user_basket
CREATE TABLE IF NOT EXISTS `user_basket` (
  `UBId` int(11) NOT NULL AUTO_INCREMENT,
  `UId_fk` int(11) NOT NULL DEFAULT '0',
  `PId_fk` int(11) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'YES',
  PRIMARY KEY (`UBId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.user_basket: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_basket` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_basket` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
