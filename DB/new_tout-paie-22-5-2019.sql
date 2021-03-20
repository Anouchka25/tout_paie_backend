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

-- Dumping structure for table tout-paie.tblAdvertisements
CREATE TABLE IF NOT EXISTS `tblAdvertisements` (
  `AdvertisementID` int(11) NOT NULL AUTO_INCREMENT,
  `ArticleName` varchar(50) DEFAULT NULL,
  `ArticleDescription` text,
  `PhotoURL` varchar(50) DEFAULT NULL,
  `StreetName` varchar(100) DEFAULT NULL,
  `PostalCode` varchar(50) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`AdvertisementID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblAdvertisements: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblAdvertisements` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblAdvertisements` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblAdvertisementsImages
CREATE TABLE IF NOT EXISTS `tblAdvertisementsImages` (
  `AdvertisementsPhotoID` int(11) NOT NULL AUTO_INCREMENT,
  `AdvImageURL` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`AdvertisementsPhotoID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblAdvertisementsImages: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblAdvertisementsImages` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblAdvertisementsImages` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblDepartment
CREATE TABLE IF NOT EXISTS `tblDepartment` (
  `DepartmentID` int(11) NOT NULL AUTO_INCREMENT,
  `DepartmentCode` int(11) NOT NULL DEFAULT '0',
  `DepartmentName` varchar(50) NOT NULL,
  PRIMARY KEY (`DepartmentID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblDepartment: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblDepartment` DISABLE KEYS */;
INSERT INTO `tblDepartment` (`DepartmentID`, `DepartmentCode`, `DepartmentName`) VALUES
	(1, 1, 'Paris');
/*!40000 ALTER TABLE `tblDepartment` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblOrderItems
CREATE TABLE IF NOT EXISTS `tblOrderItems` (
  `OrderItemID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `ProductCatalogueID` int(11) NOT NULL DEFAULT '0',
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `Quantity` int(11) NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`OrderItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblOrderItems: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblOrderItems` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblOrderItems` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblOrders
CREATE TABLE IF NOT EXISTS `tblOrders` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `OrderDate` datetime DEFAULT NULL,
  `TotalAmount` double NOT NULL DEFAULT '0',
  `PaymentStatus` char(50) NOT NULL DEFAULT 'N',
  `Status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblOrders: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblOrders` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblOrders` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblPostComments
CREATE TABLE IF NOT EXISTS `tblPostComments` (
  `PostCommentID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `PostID` int(11) NOT NULL DEFAULT '0',
  `Comment` text,
  `CommentedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`PostCommentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblPostComments: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblPostComments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblPostComments` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblPostLikes
CREATE TABLE IF NOT EXISTS `tblPostLikes` (
  `PostLikeID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `PostID` int(11) NOT NULL DEFAULT '0',
  `LikeedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`PostLikeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblPostLikes: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblPostLikes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblPostLikes` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblPosts
CREATE TABLE IF NOT EXISTS `tblPosts` (
  `PostID` int(11) NOT NULL AUTO_INCREMENT,
  `PostDescription` text,
  `PostedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`PostID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblPosts: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblPosts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblPosts` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblProductBasket
CREATE TABLE IF NOT EXISTS `tblProductBasket` (
  `ProductBasketID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `ProductCatalogueID` int(11) NOT NULL DEFAULT '0',
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `Quantity` int(11) NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`ProductBasketID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblProductBasket: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblProductBasket` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblProductBasket` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblProductCalaloguePrice
CREATE TABLE IF NOT EXISTS `tblProductCalaloguePrice` (
  `ProductCataloguePriceID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductCatalogueID` int(11) NOT NULL DEFAULT '0',
  `PriceType` varchar(50) DEFAULT '0',
  `PromotionalPrice` double NOT NULL DEFAULT '0',
  `SellPrice` double NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0',
  `TotalQuantity` double DEFAULT '0',
  `AvailableQuantity` double DEFAULT '0',
  PRIMARY KEY (`ProductCataloguePriceID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblProductCalaloguePrice: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblProductCalaloguePrice` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblProductCalaloguePrice` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblProductCatalogue
CREATE TABLE IF NOT EXISTS `tblProductCatalogue` (
  `ProductCatalogueID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) DEFAULT NULL,
  `StoreProductGroupID` int(11) DEFAULT NULL,
  `ProductName` varchar(100) DEFAULT NULL,
  `ShortDescription` text,
  `LongDescription` text,
  `PhotoURL` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ProductCatalogueID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblProductCatalogue: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblProductCatalogue` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblProductCatalogue` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblProductCategory
CREATE TABLE IF NOT EXISTS `tblProductCategory` (
  `ProductCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductCategoryName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ProductCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblProductCategory: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblProductCategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblProductCategory` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStore
CREATE TABLE IF NOT EXISTS `tblStore` (
  `StoreID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreName` varchar(100) NOT NULL DEFAULT '',
  `StoreLogo` varchar(50) NOT NULL DEFAULT '0',
  `BackgroundPhoto` varchar(50) DEFAULT NULL,
  `Description` text,
  `TelephoneNumber` varchar(12) DEFAULT NULL,
  `StoreCategoryID` int(11) DEFAULT NULL,
  `DeliveryPrice` double DEFAULT NULL,
  `MinimumDelivery` double DEFAULT NULL,
  `ShowOnHomepage` char(2) NOT NULL DEFAULT 'N',
  `CategoryType` char(1) NOT NULL DEFAULT 'N',
  `ShippingCost` double NOT NULL DEFAULT '0',
  `Presentation` varchar(100) NOT NULL DEFAULT '0',
  `IsActive` char(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`StoreID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStore: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblStore` DISABLE KEYS */;
INSERT INTO `tblStore` (`StoreID`, `StoreName`, `StoreLogo`, `BackgroundPhoto`, `Description`, `TelephoneNumber`, `StoreCategoryID`, `DeliveryPrice`, `MinimumDelivery`, `ShowOnHomepage`, `CategoryType`, `ShippingCost`, `Presentation`, `IsActive`) VALUES
	(1, 'MRF', '', '', 'The MRF', '9090909090', 0, 123, 120, '', '', 0, '0', 'Y'),
	(2, 'TATA', '', '', 'TATA Birla', '9999999999', 0, 1, 11, '', '', 0, '0', 'Y');
/*!40000 ALTER TABLE `tblStore` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreAddress
CREATE TABLE IF NOT EXISTS `tblStoreAddress` (
  `StoreAddressID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `AddressType` char(1) NOT NULL DEFAULT 'P',
  `StreetName` varchar(100) DEFAULT NULL,
  `PostalCode` varchar(20) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`StoreAddressID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreAddress: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblStoreAddress` DISABLE KEYS */;
INSERT INTO `tblStoreAddress` (`StoreAddressID`, `StoreID`, `AddressType`, `StreetName`, `PostalCode`, `City`) VALUES
	(3, 2, 'P', 'Gali No 14', '989890', 'Nashik'),
	(4, 2, 'O', 'Gali no 15', '987654', 'Nagar');
/*!40000 ALTER TABLE `tblStoreAddress` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreCategory
CREATE TABLE IF NOT EXISTS `tblStoreCategory` (
  `StoreCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreCategoryName` varchar(100) NOT NULL,
  `StoreCategoryIconURL` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`StoreCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreCategory: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblStoreCategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblStoreCategory` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreComments
CREATE TABLE IF NOT EXISTS `tblStoreComments` (
  `StoreCommentID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `Comment` text,
  `CommentedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`StoreCommentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreComments: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblStoreComments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblStoreComments` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreFavourites
CREATE TABLE IF NOT EXISTS `tblStoreFavourites` (
  `StoreFavouritesID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `CreatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`StoreFavouritesID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreFavourites: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblStoreFavourites` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblStoreFavourites` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreProductGroup
CREATE TABLE IF NOT EXISTS `tblStoreProductGroup` (
  `StoreProductGroupID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `GroupName` varchar(100) NOT NULL DEFAULT '0',
  `GroupPhotoURL` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`StoreProductGroupID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreProductGroup: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblStoreProductGroup` DISABLE KEYS */;
INSERT INTO `tblStoreProductGroup` (`StoreProductGroupID`, `StoreID`, `GroupName`, `GroupPhotoURL`) VALUES
	(1, 2, 'Pepsi', '1558536524.jpg');
/*!40000 ALTER TABLE `tblStoreProductGroup` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreProducts
CREATE TABLE IF NOT EXISTS `tblStoreProducts` (
  `StoreProductID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreCategoryID` int(11) NOT NULL DEFAULT '0',
  `ProductCategoryID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`StoreProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreProducts: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblStoreProducts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblStoreProducts` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreRatings
CREATE TABLE IF NOT EXISTS `tblStoreRatings` (
  `StoreRatingID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `Rating` int(11) NOT NULL DEFAULT '0',
  `CommentedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`StoreRatingID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreRatings: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblStoreRatings` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblStoreRatings` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblUsers
CREATE TABLE IF NOT EXISTS `tblUsers` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `RememberToken` varchar(100) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `MobileNumber` varchar(12) DEFAULT NULL,
  `StreetName` varchar(100) DEFAULT NULL,
  `PostalCode` varchar(20) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `DepartmentID` int(11) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `ProfilePhotoURL` varchar(50) DEFAULT NULL,
  `isActive` char(1) NOT NULL DEFAULT 'Y',
  `Role` char(2) NOT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblUsers: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblUsers` DISABLE KEYS */;
INSERT INTO `tblUsers` (`UserID`, `FirstName`, `LastName`, `Email`, `password`, `RememberToken`, `DateOfBirth`, `MobileNumber`, `StreetName`, `PostalCode`, `City`, `DepartmentID`, `Status`, `ProfilePhotoURL`, `isActive`, `Role`, `CreatedOn`, `UpdatedOn`) VALUES
	(1, 'dsfgds', '', 'te@gmail.com', '$2y$10$M3Fguw8uNO8L6m5654khw.zh99LDyZeDSSv5dtCQmzeKji/tJ5seO', 'UqV6rGxUrxcSzQtCsHJh96YZe6z5yRUGb69BpWLC5AeVatXE3XKHXBiwZMr7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'U', '2019-05-22 13:04:27', '2019-05-22 13:34:15'),
	(2, 'Mahesh', 'Bhosale', 'maheshab555@gmail.com', '$2y$10$RtxgpVTuTJ4wAHUXWhDs2eQb.hb8um/GJc1sw6qqT8IBqf7W/4u8G', 'UqV6rGxUrxcSzQtCsHJh96YZe6z5yRUGb69BpWLC5AeVatXE3XKHXBiwZMr8', NULL, '', '', '', '', 0, NULL, '1558533328.png', 'Y', 'U', '2019-05-22 13:37:22', '2019-05-22 13:55:28');
/*!40000 ALTER TABLE `tblUsers` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
