-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 29, 2019 at 04:28 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tout-paie`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblAdvertisements`
--

CREATE TABLE `tblAdvertisements` (
  `AdvertisementID` int(11) NOT NULL,
  `AdsType` varchar(20) NOT NULL,
  `ArticleName` varchar(50) DEFAULT NULL,
  `ArticleDescription` text,
  `ItemPrice` int(11) NOT NULL,
  `PhotoURL` varchar(50) DEFAULT NULL,
  `StreetName` varchar(100) DEFAULT NULL,
  `PostalCode` varchar(50) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `Status` varchar(10) NOT NULL DEFAULT 'Open',
  `PublicationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblAdvertisements`
--

INSERT INTO `tblAdvertisements` (`AdvertisementID`, `AdsType`, `ArticleName`, `ArticleDescription`, `ItemPrice`, `PhotoURL`, `StreetName`, `PostalCode`, `City`, `Status`, `PublicationDate`) VALUES
(3, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(4, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(5, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(6, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(7, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(8, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(9, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(11, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(13, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(14, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(15, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(16, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(17, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(18, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(19, 'Search', 'Mobile 21', 'Dell Moboile 21', 21, '', 'Gali no1', '232321', 'Pune', 'Close', NULL),
(20, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(21, 'Search', 'Mobile 21', 'Dell Moboile 21', 21, '', 'Gali no1', '232321', 'Pune', 'Open', NULL),
(22, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', '2019-05-29');

-- --------------------------------------------------------

--
-- Table structure for table `tblAdvertisementsImages`
--

CREATE TABLE `tblAdvertisementsImages` (
  `AdvertisementsPhotoID` int(11) NOT NULL,
  `AdvertisementID` int(11) NOT NULL,
  `AdvImageURL` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblAdvertisementsImages`
--

INSERT INTO `tblAdvertisementsImages` (`AdvertisementsPhotoID`, `AdvertisementID`, `AdvImageURL`) VALUES
(4, 13, '1559125605.jpg'),
(5, 14, '1559126015.jpg'),
(6, 15, '1559126049.jpg'),
(7, 18, '15591262910.jpg'),
(8, 18, '15591262911.png'),
(9, 18, '15591262912.png'),
(13, 20, '15591271050.png'),
(19, 19, '15591278760.png'),
(20, 19, '15591278761.png');

-- --------------------------------------------------------

--
-- Table structure for table `tblDepartment`
--

CREATE TABLE `tblDepartment` (
  `DepartmentID` int(11) NOT NULL,
  `DepartmentCode` int(11) NOT NULL DEFAULT '0',
  `DepartmentName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblDepartment`
--

INSERT INTO `tblDepartment` (`DepartmentID`, `DepartmentCode`, `DepartmentName`) VALUES
(1, 1, 'Paris');

-- --------------------------------------------------------

--
-- Table structure for table `tblOrderItems`
--

CREATE TABLE `tblOrderItems` (
  `OrderItemID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `ProductCatalogueID` int(11) NOT NULL DEFAULT '0',
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `Quantity` int(11) NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblOrders`
--

CREATE TABLE `tblOrders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `OrderDate` datetime DEFAULT NULL,
  `TotalAmount` double NOT NULL DEFAULT '0',
  `PaymentStatus` char(50) NOT NULL DEFAULT 'N',
  `Status` varchar(50) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblPostComments`
--

CREATE TABLE `tblPostComments` (
  `PostCommentID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `PostID` int(11) NOT NULL DEFAULT '0',
  `Comment` text,
  `CommentedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblPostComments`
--

INSERT INTO `tblPostComments` (`PostCommentID`, `UserID`, `PostID`, `Comment`, `CommentedOn`, `UpdatedOn`) VALUES
(1, 1, 2, 'Hi AA', '2019-05-28 10:39:29', '2019-05-28 10:39:29'),
(2, 2, 2, 'Hi Hello', '2019-05-28 10:40:15', '2019-05-28 10:40:15'),
(3, 2, 2, 'Valid', '2019-05-29 05:35:40', '2019-05-29 05:35:40');

-- --------------------------------------------------------

--
-- Table structure for table `tblPostLikes`
--

CREATE TABLE `tblPostLikes` (
  `PostLikeID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `PostID` int(11) NOT NULL DEFAULT '0',
  `LikeedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblPostLikes`
--

INSERT INTO `tblPostLikes` (`PostLikeID`, `UserID`, `PostID`, `LikeedOn`, `UpdatedOn`) VALUES
(1, 2, 2, '2019-05-28 11:14:52', '2019-05-28 11:14:52'),
(2, 2, 2, '2019-05-28 11:22:11', '2019-05-28 11:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `tblPosts`
--

CREATE TABLE `tblPosts` (
  `PostID` int(11) NOT NULL,
  `PostDescription` text,
  `PostedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblPosts`
--

INSERT INTO `tblPosts` (`PostID`, `PostDescription`, `PostedOn`, `UpdatedOn`) VALUES
(2, 'Hello', '2019-05-28 09:59:25', '2019-05-28 09:59:25'),
(3, 'Validations check', '2019-05-29 05:29:29', '2019-05-29 05:31:37');

-- --------------------------------------------------------

--
-- Table structure for table `tblProductBasket`
--

CREATE TABLE `tblProductBasket` (
  `ProductBasketID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `ProductCatalogueID` int(11) NOT NULL DEFAULT '0',
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `Quantity` int(11) NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblProductBasket`
--

INSERT INTO `tblProductBasket` (`ProductBasketID`, `UserID`, `ProductCatalogueID`, `StoreID`, `Quantity`, `Amount`) VALUES
(1, 2, 2, 2, 10, 2000),
(3, 2, 3, 3, 100, 2000),
(4, 2, 3, 1, 2, 500),
(5, 2, 3, 2, 2, 600);

-- --------------------------------------------------------

--
-- Table structure for table `tblProductCalaloguePrice`
--

CREATE TABLE `tblProductCalaloguePrice` (
  `ProductCataloguePriceID` int(11) NOT NULL,
  `ProductCatalogueID` int(11) NOT NULL DEFAULT '0',
  `PriceType` varchar(50) DEFAULT NULL,
  `PromotionalPrice` double NOT NULL DEFAULT '0',
  `SellPrice` double NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0',
  `TotalQuantity` double DEFAULT '0',
  `AvailableQuantity` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblProductCalaloguePrice`
--

INSERT INTO `tblProductCalaloguePrice` (`ProductCataloguePriceID`, `ProductCatalogueID`, `PriceType`, `PromotionalPrice`, `SellPrice`, `Amount`, `TotalQuantity`, `AvailableQuantity`) VALUES
(1, 2, 'A', 1, 12, 1, 1, 2),
(4, 4, 'A', 1, 12, 1, 1, 2),
(5, 5, 'A', 1, 12, 1, 1, 2),
(6, 6, 'A', 1, 12, 1, 1, 2),
(7, 7, 'A', 1, 12, 1, 1, 2),
(8, 8, 'A', 1, 8, 8, 8, 88);

-- --------------------------------------------------------

--
-- Table structure for table `tblProductCatalogue`
--

CREATE TABLE `tblProductCatalogue` (
  `ProductCatalogueID` int(11) NOT NULL,
  `StoreID` int(11) DEFAULT NULL,
  `StoreProductGroupID` int(11) DEFAULT NULL,
  `ProductName` varchar(100) DEFAULT NULL,
  `ShortDescription` text,
  `LongDescription` text,
  `PhotoURL` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblProductCatalogue`
--

INSERT INTO `tblProductCatalogue` (`ProductCatalogueID`, `StoreID`, `StoreProductGroupID`, `ProductName`, `ShortDescription`, `LongDescription`, `PhotoURL`) VALUES
(2, 2, 2, 'Moto', 'Test', NULL, '1558766727.jpeg'),
(4, 1, 1, 'MOTO', 'Test', NULL, ''),
(5, 1, 1, 'MOTO', 'Test', NULL, ''),
(6, 1, 1, 'MOTO', 'Test', NULL, ''),
(7, 1, 1, 'TVS', 'Test', NULL, ''),
(8, 1, 1, 'TVS', 'Test', NULL, '1558782996.pdf'),
(9, 1, 1, 'TVS', 'Test', NULL, '1558784016.pdf'),
(10, 1, 1, 'TVS', 'Test', NULL, '1558784063.pdf'),
(11, 1, 1, 'TVS', 'Test', NULL, '1558784179.pdf'),
(12, 1, 1, 'Lenovo', 'Test', NULL, '1558784237.pdf'),
(13, 2, 2, 'Nokia', 'Test', NULL, '1559022190.png'),
(14, 1, 1, 'Dell', 'Test', NULL, ''),
(15, 1, 1, 'Dell', 'Test', NULL, '1559109081.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tblProductCategory`
--

CREATE TABLE `tblProductCategory` (
  `ProductCategoryID` int(11) NOT NULL,
  `ProductCategoryName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblProductCategory`
--

INSERT INTO `tblProductCategory` (`ProductCategoryID`, `ProductCategoryName`) VALUES
(1, 'Cloth'),
(2, 'Creta');

-- --------------------------------------------------------

--
-- Table structure for table `tblStore`
--

CREATE TABLE `tblStore` (
  `StoreID` int(11) NOT NULL,
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
  `IsActive` char(1) NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblStore`
--

INSERT INTO `tblStore` (`StoreID`, `StoreName`, `StoreLogo`, `BackgroundPhoto`, `Description`, `TelephoneNumber`, `StoreCategoryID`, `DeliveryPrice`, `MinimumDelivery`, `ShowOnHomepage`, `CategoryType`, `ShippingCost`, `Presentation`, `IsActive`) VALUES
(1, 'MRF', '', '', 'The MRF', '9090909090', 0, 123, 120, '', '', 0, '0', 'Y'),
(2, 'TATA', '', '', 'TATA Birla', '9999999999', 0, 1, 11, '', '', 0, '0', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `tblStoreAddress`
--

CREATE TABLE `tblStoreAddress` (
  `StoreAddressID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `AddressType` char(1) NOT NULL DEFAULT 'P',
  `StreetName` varchar(100) DEFAULT NULL,
  `PostalCode` varchar(20) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblStoreAddress`
--

INSERT INTO `tblStoreAddress` (`StoreAddressID`, `StoreID`, `AddressType`, `StreetName`, `PostalCode`, `City`) VALUES
(3, 2, 'P', 'Gali No 14', '989890', 'Nashik'),
(4, 2, 'O', 'Gali no 15', '987654', 'Nagar');

-- --------------------------------------------------------

--
-- Table structure for table `tblStoreCategory`
--

CREATE TABLE `tblStoreCategory` (
  `StoreCategoryID` int(11) NOT NULL,
  `StoreCategoryName` varchar(100) NOT NULL,
  `StoreCategoryIconURL` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblStoreComments`
--

CREATE TABLE `tblStoreComments` (
  `StoreCommentID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `Comment` text,
  `CommentedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblStoreComments`
--

INSERT INTO `tblStoreComments` (`StoreCommentID`, `StoreID`, `UserID`, `Comment`, `CommentedOn`, `UpdatedOn`) VALUES
(1, 2, 1, 'Hi Amit', '2019-05-28 07:32:55', '2019-05-28 07:32:55'),
(2, 2, 1, 'jio', '2019-05-29 06:41:54', '2019-05-29 06:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `tblStoreFavourites`
--

CREATE TABLE `tblStoreFavourites` (
  `StoreFavouritesID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `Status` char(2) NOT NULL DEFAULT 'N',
  `CreatedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblStoreFavourites`
--

INSERT INTO `tblStoreFavourites` (`StoreFavouritesID`, `StoreID`, `UserID`, `Status`, `CreatedOn`, `UpdatedOn`) VALUES
(1, 2, 2, 'N', NULL, '2019-05-28 06:58:11'),
(2, 2, 2, 'Y', '2019-05-28 06:42:53', '2019-05-28 06:42:53'),
(3, 1, 2, 'N', '2019-05-28 06:43:08', '2019-05-28 06:43:08'),
(4, 1, 2, 'Y', '2019-05-28 06:55:48', '2019-05-28 06:55:48');

-- --------------------------------------------------------

--
-- Table structure for table `tblStoreProductGroup`
--

CREATE TABLE `tblStoreProductGroup` (
  `StoreProductGroupID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `GroupName` varchar(100) NOT NULL DEFAULT '0',
  `GroupPhotoURL` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblStoreProductGroup`
--

INSERT INTO `tblStoreProductGroup` (`StoreProductGroupID`, `StoreID`, `GroupName`, `GroupPhotoURL`) VALUES
(1, 2, 'Pepsi', '1558536524.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tblStoreProducts`
--

CREATE TABLE `tblStoreProducts` (
  `StoreProductID` int(11) NOT NULL,
  `StoreCategoryID` int(11) NOT NULL DEFAULT '0',
  `ProductCategoryID` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblStoreProducts`
--

INSERT INTO `tblStoreProducts` (`StoreProductID`, `StoreCategoryID`, `ProductCategoryID`) VALUES
(1, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tblStoreRatings`
--

CREATE TABLE `tblStoreRatings` (
  `StoreRatingID` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `Rating` int(11) NOT NULL DEFAULT '0',
  `CommentedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblStoreRatings`
--

INSERT INTO `tblStoreRatings` (`StoreRatingID`, `StoreID`, `UserID`, `Rating`, `CommentedOn`, `UpdatedOn`) VALUES
(1, 2, 1, 4, '2019-05-28 07:52:35', '2019-05-28 07:52:35'),
(2, 1, 1, 5, '2019-05-28 07:52:52', '2019-05-28 07:52:52'),
(3, 2, 1, 2, '2019-05-29 06:47:45', '2019-05-29 06:47:45');

-- --------------------------------------------------------

--
-- Table structure for table `tblUsers`
--

CREATE TABLE `tblUsers` (
  `UserID` int(11) NOT NULL,
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
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblUsers`
--

INSERT INTO `tblUsers` (`UserID`, `FirstName`, `LastName`, `Email`, `password`, `RememberToken`, `DateOfBirth`, `MobileNumber`, `StreetName`, `PostalCode`, `City`, `DepartmentID`, `Status`, `ProfilePhotoURL`, `isActive`, `Role`, `CreatedOn`, `UpdatedOn`) VALUES
(2, 'Mahesh', 'Bhosale', 'maheshab555@gmail.com', '$2y$10$RtxgpVTuTJ4wAHUXWhDs2eQb.hb8um/GJc1sw6qqT8IBqf7W/4u8G', 'UqV6rGxUrxcSzQtCsHJh96YZe6z5yRUGb69BpWLC5AeVatXE3XKHXBiwZMr8', NULL, '', '', '', '', 0, NULL, '1558533328.png', 'N', 'U', '2019-05-22 13:37:22', '2019-05-28 08:43:22'),
(3, 'mamamam', 'nnnnn', 'mahesh@gmail.com', '$2y$10$wWie9b6EATZNXfentdcP3O0B5pngM7Vczw5O4x6PptPU85A4udaeG', 'UqV6rGxUrxcSzQtCsHJh96YZe6z5yRUGb69BpWLC5AeVatXE3XKHXBiwZMr9', NULL, '', '', '', '', 0, NULL, NULL, 'Y', 'U', '2019-05-28 13:53:11', '2019-05-28 14:40:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblAdvertisements`
--
ALTER TABLE `tblAdvertisements`
  ADD PRIMARY KEY (`AdvertisementID`);

--
-- Indexes for table `tblAdvertisementsImages`
--
ALTER TABLE `tblAdvertisementsImages`
  ADD PRIMARY KEY (`AdvertisementsPhotoID`);

--
-- Indexes for table `tblDepartment`
--
ALTER TABLE `tblDepartment`
  ADD PRIMARY KEY (`DepartmentID`);

--
-- Indexes for table `tblOrderItems`
--
ALTER TABLE `tblOrderItems`
  ADD PRIMARY KEY (`OrderItemID`);

--
-- Indexes for table `tblOrders`
--
ALTER TABLE `tblOrders`
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `tblPostComments`
--
ALTER TABLE `tblPostComments`
  ADD PRIMARY KEY (`PostCommentID`);

--
-- Indexes for table `tblPostLikes`
--
ALTER TABLE `tblPostLikes`
  ADD PRIMARY KEY (`PostLikeID`);

--
-- Indexes for table `tblPosts`
--
ALTER TABLE `tblPosts`
  ADD PRIMARY KEY (`PostID`);

--
-- Indexes for table `tblProductBasket`
--
ALTER TABLE `tblProductBasket`
  ADD PRIMARY KEY (`ProductBasketID`);

--
-- Indexes for table `tblProductCalaloguePrice`
--
ALTER TABLE `tblProductCalaloguePrice`
  ADD PRIMARY KEY (`ProductCataloguePriceID`);

--
-- Indexes for table `tblProductCatalogue`
--
ALTER TABLE `tblProductCatalogue`
  ADD PRIMARY KEY (`ProductCatalogueID`);

--
-- Indexes for table `tblProductCategory`
--
ALTER TABLE `tblProductCategory`
  ADD PRIMARY KEY (`ProductCategoryID`);

--
-- Indexes for table `tblStore`
--
ALTER TABLE `tblStore`
  ADD PRIMARY KEY (`StoreID`);

--
-- Indexes for table `tblStoreAddress`
--
ALTER TABLE `tblStoreAddress`
  ADD PRIMARY KEY (`StoreAddressID`);

--
-- Indexes for table `tblStoreCategory`
--
ALTER TABLE `tblStoreCategory`
  ADD PRIMARY KEY (`StoreCategoryID`);

--
-- Indexes for table `tblStoreComments`
--
ALTER TABLE `tblStoreComments`
  ADD PRIMARY KEY (`StoreCommentID`);

--
-- Indexes for table `tblStoreFavourites`
--
ALTER TABLE `tblStoreFavourites`
  ADD PRIMARY KEY (`StoreFavouritesID`);

--
-- Indexes for table `tblStoreProductGroup`
--
ALTER TABLE `tblStoreProductGroup`
  ADD PRIMARY KEY (`StoreProductGroupID`);

--
-- Indexes for table `tblStoreProducts`
--
ALTER TABLE `tblStoreProducts`
  ADD PRIMARY KEY (`StoreProductID`);

--
-- Indexes for table `tblStoreRatings`
--
ALTER TABLE `tblStoreRatings`
  ADD PRIMARY KEY (`StoreRatingID`);

--
-- Indexes for table `tblUsers`
--
ALTER TABLE `tblUsers`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblAdvertisements`
--
ALTER TABLE `tblAdvertisements`
  MODIFY `AdvertisementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tblAdvertisementsImages`
--
ALTER TABLE `tblAdvertisementsImages`
  MODIFY `AdvertisementsPhotoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tblDepartment`
--
ALTER TABLE `tblDepartment`
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblOrderItems`
--
ALTER TABLE `tblOrderItems`
  MODIFY `OrderItemID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblOrders`
--
ALTER TABLE `tblOrders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblPostComments`
--
ALTER TABLE `tblPostComments`
  MODIFY `PostCommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblPostLikes`
--
ALTER TABLE `tblPostLikes`
  MODIFY `PostLikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblPosts`
--
ALTER TABLE `tblPosts`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblProductBasket`
--
ALTER TABLE `tblProductBasket`
  MODIFY `ProductBasketID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblProductCalaloguePrice`
--
ALTER TABLE `tblProductCalaloguePrice`
  MODIFY `ProductCataloguePriceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblProductCatalogue`
--
ALTER TABLE `tblProductCatalogue`
  MODIFY `ProductCatalogueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tblProductCategory`
--
ALTER TABLE `tblProductCategory`
  MODIFY `ProductCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblStore`
--
ALTER TABLE `tblStore`
  MODIFY `StoreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblStoreAddress`
--
ALTER TABLE `tblStoreAddress`
  MODIFY `StoreAddressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblStoreCategory`
--
ALTER TABLE `tblStoreCategory`
  MODIFY `StoreCategoryID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblStoreComments`
--
ALTER TABLE `tblStoreComments`
  MODIFY `StoreCommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblStoreFavourites`
--
ALTER TABLE `tblStoreFavourites`
  MODIFY `StoreFavouritesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblStoreProductGroup`
--
ALTER TABLE `tblStoreProductGroup`
  MODIFY `StoreProductGroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblStoreProducts`
--
ALTER TABLE `tblStoreProducts`
  MODIFY `StoreProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblStoreRatings`
--
ALTER TABLE `tblStoreRatings`
  MODIFY `StoreRatingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblUsers`
--
ALTER TABLE `tblUsers`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
