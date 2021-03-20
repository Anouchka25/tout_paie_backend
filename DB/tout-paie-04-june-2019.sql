-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 04, 2019 at 03:24 PM
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
  `PublicationDate` date DEFAULT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblAdvertisements`
--

INSERT INTO `tblAdvertisements` (`AdvertisementID`, `AdsType`, `ArticleName`, `ArticleDescription`, `ItemPrice`, `PhotoURL`, `StreetName`, `PostalCode`, `City`, `Status`, `PublicationDate`, `UserID`) VALUES
(3, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(4, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(5, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(6, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(7, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(8, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(9, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(11, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(13, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(14, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(15, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(16, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(17, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(18, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 0),
(19, 'Search', 'Mobile 21', 'Dell Moboile 21', 21, '', 'Gali no1', '232321', 'Pune', 'Close', NULL, 2),
(20, 'Search', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 2),
(21, 'Search', 'Mobile 21', 'Dell Moboile 21', 21, '', 'Gali no1', '232321', 'Pune', 'Open', NULL, 2),
(22, 'Sell', 'Mobile', 'Dell Moboile', 111, '', 'Gali no1', '232321', 'Pune', 'Open', '2019-05-29', 2);

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
  `DepartmentName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DepartmentCode` int(11) NOT NULL,
  `Lng` double NOT NULL,
  `Lat` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblDepartment`
--

INSERT INTO `tblDepartment` (`DepartmentID`, `DepartmentName`, `DepartmentCode`, `Lng`, `Lat`) VALUES
(1, 'Ain(01)', 0, 5.130768, 46.247571),
(2, 'Aisne(02)', 0, 3.441737, 49.47692),
(3, 'Allier(03)', 0, 3.416765, 46.311555),
(5, 'Hautes-Alpes(05)', 0, 6.322607, 44.600872),
(4, 'Alpes-de-Haute-Provence(04)', 0, 6.237595, 44.077872),
(6, 'Alpes-Maritimes(06)', 0, 7.179026, 43.946679),
(7, 'Ardèche(07)', 0, 4.562443, 44.759629),
(8, 'Ardennes(08)', 0, 4.628505, 49.762464),
(9, 'Ariège(09)', 0, 1.443469, 42.932629),
(10, 'Aube(10)', 0, 4.373246, 48.156342),
(11, 'Aude(11)', 0, 2.381362, 43.072467),
(12, 'Aveyron(12)', 0, 2.618927, 44.217975),
(13, 'Bouches-du-Rhône(13)', 0, 5.31025, 43.591168),
(14, 'Calvados(14)', 0, -117.837927, 33.610659),
(15, 'Cantal(15)', 0, 2.632606, 45.1192),
(16, 'Charente(16)', 0, 0.153476, 45.751996),
(17, 'Charente-Maritime(17)', 0, -0.773319, 45.74949),
(18, 'Cher(18)', 0, 2.467191, 46.954005),
(19, 'Corrèze(19)', 0, 2.019591, 45.432008),
(20, 'Corse-du-sud(2a)', 0, 8.924534, 41.810263),
(21, 'Haute-corse(2b)', 0, 9.278558, 42.409788),
(22, 'Côte-d\'or(21)', 0, 4.635412, 47.51268),
(23, 'Côtes-d\'armor(22)', 0, -3.326368, 48.51081),
(24, 'Creuse(23)', 0, 2.062783, 46.037763),
(25, 'Dordogne(24)', 0, 0.757221, 45.146949),
(26, 'Doubs(25)', 0, 6.3126, 47.196982),
(27, 'Drôme(26)', 0, 5.226667, 44.73119),
(28, 'Eure(27)', 0, 0.958211, 49.118176),
(29, 'Eure-et-Loir(28)', 0, 1.198981, 48.552524),
(30, 'Finistère(29)', 0, -3.930052, 48.252025),
(31, 'Gard(30)', 0, 4.151376, 43.9447),
(32, 'Haute-Garonne(31)', 0, 1.135302, 43.401046),
(33, 'Gers(32)', 0, 0.450237, 43.636648),
(34, 'Gironde(33)', 0, -0.450237, 44.849665),
(35, 'Hérault(34)', 0, 3.258363, 43.591236),
(36, 'Ile-et-Vilaine(35)', 0, -1.530069, 48.229202),
(37, 'Indre(36)', 0, 1.448266, 46.661397),
(38, 'Indre-et-Loire(37)', 0, 0.816097, 47.289492),
(39, 'Isère(38)', 0, 5.929348, 44.995775),
(40, 'Jura(39)', 0, 5.672916, 46.762475),
(41, 'Landes(40)', 0, -0.753281, 43.941205),
(42, 'Loir-et-Cher(41)', 0, 1.415907, 47.676191),
(43, 'Loire(42)', 0, 4.052545, 45.984648),
(44, 'Haute-Loire(43)', 0, 3.926637, 45.082123),
(45, 'Loire-Atlantique(44)', 0, -1.815765, 47.278047),
(46, 'Loiret(45)', 0, 2.201817, 47.900771),
(47, 'Lot(46)', 0, 1.676069, 44.537936),
(48, 'Lot-et-Garonne(47)', 0, 0.450237, 44.247017),
(49, 'Lozère(48)', 0, 3.581269, 44.494203),
(50, 'Maine-et-Loire(49)', 0, -0.487785, 47.291355),
(51, 'Manche(50)', 0, -1.311595, 49.114712),
(52, 'Marne(51)', 0, 4.147544, 49.128754),
(53, 'Haute-Marne(52)', 0, 5.107132, 48.126097),
(54, 'Mayenne(53)', 0, -0.504256, 48.23825),
(55, 'Meurthe-et-Moselle(54)', 0, 6.094701, 48.799701),
(56, 'Meuse(55)', 0, 5.2824, 49.082432),
(57, 'Morbihan(56)', 0, -2.900187, 47.885293),
(58, 'Moselle(57)', 0, 6.552764, 49.098384),
(59, 'Nièvre(58)', 0, 3.529452, 47.238171),
(60, 'Nord(59)', 0, 3.264244, 50.385125),
(61, 'Oise(60)', 0, 2.41464, 49.421457),
(62, 'Orne(61)', 0, 0.08482, 48.638857),
(63, 'Pas-de-Calais(62)', 0, 2.324468, 50.573277),
(64, 'Puy-de-Dôme(63)', 0, 3.015582, 45.712414),
(65, 'Pyrénées-Atlantiques(64)', 0, -0.753281, 43.326994),
(66, 'Hautes-Pyrénées(65)', 0, 0.149499, 43.019392),
(67, 'Pyrénées-Orientales(66)', 0, 2.539603, 42.601291),
(68, 'Bas-Rhin(67)', 0, 7.525294, 48.634317),
(69, 'Haut-Rhin(68)', 0, 7.24411, 47.931504),
(70, 'Rhône(69)', 0, 4.610804, 45.735146),
(71, 'Haute-Saône(70)', 0, 6.155628, 47.756981),
(72, 'Saône-et-Loire(71)', 0, 4.486671, 46.582751),
(73, 'Sarthe(72)', 0, 0.16558, 47.921701),
(74, 'Savoie(73)', 0, 6.4724, 45.493205),
(75, 'Haute-Savoie(74)', 0, 6.538962, 46.175679),
(76, 'Paris(75)', 0, 2.352215, 48.856582),
(77, 'Seine-Maritime(76)', 0, 0.974844, 49.605419),
(78, 'Seine-et-Marne(77)', 0, 2.999366, 48.841082),
(79, 'Yvelines(78)', 0, 1.825657, 48.785094),
(80, 'Deux-Sèvres(79)', 0, -0.396284, 46.592654),
(81, 'Somme(80)', 0, 2.27071, 49.914518),
(82, 'Tarn(81)', 0, 1.988153, 43.92644),
(83, 'Tarn-et-Garonne(82)', 0, 1.289104, 44.012668),
(84, 'Var(83)', 0, 6.237595, 43.467646),
(85, 'Vaucluse(84)', 0, 5.143207, 44.056505),
(86, 'Vendée(85)', 0, -1.448266, 46.661397),
(87, 'Vienne(86)', 0, 0.477286, 46.669542),
(88, 'Haute-Vienne(87)', 0, 1.402548, 45.743517),
(89, 'Vosges(88)', 0, 6.335593, 48.144643),
(90, 'Yonne(89)', 0, 3.607982, 47.865273),
(91, 'Territoire de Belfort(90)', 0, 6.920772, 47.594657),
(92, 'Essonne(91)', 0, 2.156942, 48.45857),
(93, 'Hauts-de-Seine(92)', 0, 2.218807, 48.828508),
(94, 'Seine-Saint-Denis(93)', 0, 2.357443, 48.936181),
(95, 'Val-de-Marne(94)', 0, 2.474034, 48.793143),
(96, 'Val-d\'oise(95)', 0, 2.158135, 49.06159),
(97, 'Mayotte(976)', 0, 45.232242, -12.755121),
(98, 'Guadeloupe(971)', 0, -82.426337, 27.104463),
(99, 'Guyane(973)', 0, -52.3001, 4.926615),
(100, 'Martinique(972)', 0, -96.745316, 32.798854),
(101, 'Réunion(974)', 0, 55.45834, -20.88837);

-- --------------------------------------------------------

--
-- Table structure for table `tblOrderItems`
--

CREATE TABLE `tblOrderItems` (
  `OrderItemID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL DEFAULT '0',
  `ProductCatalogueID` int(11) NOT NULL DEFAULT '0',
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `Quantity` int(11) NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblOrderItems`
--

INSERT INTO `tblOrderItems` (`OrderItemID`, `OrderID`, `ProductCatalogueID`, `StoreID`, `Quantity`, `Amount`) VALUES
(1, 1, 2, 2, 2, 1000),
(4, 2, 3, 3, 1, 1500),
(5, 2, 4, 3, 1, 1500),
(6, 3, 3, 3, 1, 1000),
(7, 3, 4, 3, 1, 1000),
(8, 4, 3, 3, 1, 1000),
(9, 4, 4, 3, 1, 1000),
(10, 5, 3, 4, 1, 1000),
(11, 5, 4, 4, 1, 1000),
(12, 6, 3, 4, 1, 1000),
(13, 6, 4, 4, 1, 1000),
(14, 7, 3, 4, 1, 1000),
(15, 7, 4, 4, 1, 1000),
(16, 8, 3, 4, 1, 1000),
(17, 8, 4, 4, 1, 1000),
(18, 9, 3, 4, 1, 1000),
(19, 9, 4, 4, 1, 1000),
(20, 10, 3, 4, 1, 1000),
(21, 10, 4, 4, 1, 1000),
(22, 11, 3, 4, 1, 1000),
(23, 11, 4, 4, 1, 1000),
(24, 12, 3, 4, 1, 1000),
(25, 12, 4, 4, 1, 1000),
(26, 13, 3, 4, 1, 1000),
(27, 13, 4, 4, 1, 1000),
(28, 14, 3, 4, 1, 1000),
(29, 14, 4, 4, 1, 1000);

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

--
-- Dumping data for table `tblOrders`
--

INSERT INTO `tblOrders` (`OrderID`, `UserID`, `OrderDate`, `TotalAmount`, `PaymentStatus`, `Status`, `UpdatedOn`) VALUES
(1, 2, '2019-05-30 06:50:04', 2000, 'N', 'Cancel', '2019-05-30 07:16:38'),
(2, 2, '2019-05-30 06:52:21', 3000, 'N', 'Order Placed', '2019-05-30 07:03:48'),
(3, 3, '2019-06-04 05:49:04', 2000, 'N', 'Order Placed', '2019-06-04 05:49:04'),
(4, 2, '2019-06-04 05:52:07', 2000, 'N', 'Order Placed', '2019-06-04 05:52:07'),
(5, 2, '2019-06-04 05:53:09', 2000, 'N', 'Order Placed', '2019-06-04 05:53:09'),
(6, 2, '2019-06-04 05:54:45', 2000, 'N', 'Order Placed', '2019-06-04 05:54:45'),
(7, 2, '2019-06-04 05:56:55', 2000, 'N', 'Order Placed', '2019-06-04 05:56:55'),
(8, 2, '2019-06-04 05:59:44', 2000, 'N', 'Order Placed', '2019-06-04 05:59:44'),
(9, 2, '2019-06-04 06:02:06', 2000, 'N', 'Order Placed', '2019-06-04 06:02:06'),
(10, 2, '2019-06-04 06:03:20', 2000, 'N', 'Order Placed', '2019-06-04 06:03:20'),
(11, 2, '2019-06-04 06:04:56', 2000, 'N', 'Order Placed', '2019-06-04 06:04:56'),
(12, 2, '2019-06-04 06:13:29', 2000, 'N', 'Order Placed', '2019-06-04 06:13:29'),
(13, 2, '2019-06-04 06:18:00', 2000, 'N', 'Order Placed', '2019-06-04 06:18:00'),
(14, 2, '2019-06-04 06:26:11', 2000, 'N', 'Cancel', '2019-06-04 06:54:17');

-- --------------------------------------------------------

--
-- Table structure for table `tblOrderStatus`
--

CREATE TABLE `tblOrderStatus` (
  `OrderStatusId` int(11) NOT NULL,
  `StatusId` int(11) NOT NULL,
  `StatusValue` varchar(100) NOT NULL
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
(2, 2, 2, '2019-05-28 11:22:11', '2019-05-28 11:22:11'),
(3, 2, 1, '2019-06-04 09:41:04', '2019-06-04 09:41:04');

-- --------------------------------------------------------

--
-- Table structure for table `tblPosts`
--

CREATE TABLE `tblPosts` (
  `PostID` int(11) NOT NULL,
  `PostDescription` text,
  `PostStatus` char(1) NOT NULL DEFAULT 'Y',
  `UserID` int(11) NOT NULL,
  `PostedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblPosts`
--

INSERT INTO `tblPosts` (`PostID`, `PostDescription`, `PostStatus`, `UserID`, `PostedOn`, `UpdatedOn`) VALUES
(2, 'Hello', 'Y', 2, '2019-05-28 09:59:25', '2019-05-28 09:59:25'),
(3, 'Validations check', 'N', 2, '2019-05-29 05:29:29', '2019-06-03 06:08:37');

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
(1, 2, 'A', 1, 12, 1, 1, 2);

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
(2, 2, 1, 'Moto', 'Test', NULL, '1558766727.jpeg'),
(3, 3, 1, 'Dell', 'Test', NULL, '1558766727.jpeg'),
(4, 2, 1, 'Mo', 'Test', NULL, '1558766727.jpeg');

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
(2, 'MRF', '', '', 'TATA Birla', '9999999999', 0, 1, 11, '', '', 0, '0', 'Y'),
(3, 'Bio', '1559222144.png', '1559222144.png', 'Test', '9898989898', 0, 100, 80, 'Y', '', 0, '0', 'Y'),
(4, 'Bio', '1559223309.png', '1559223309.png', 'Apple Bio', '9898989898', 0, 100, 80, 'Y', '', 0, '0', 'Y'),
(5, 'Bio', '1559223527.png', '', 'Apple Bio', '9898989898', 0, 100, 80, '', '', 0, '0', 'Y'),
(6, 'Men', '1559223544.png', '1559223544.png', 'Apple Bio', '9898989898', 0, 100, 80, 'Y', '', 0, '0', 'Y'),
(7, 'Bio', '1559645628.jpg', '', 'Apple Bio', '9898989898', 0, 100, 80, '', '', 0, '0', 'Y');

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
(4, 2, 'O', 'Gali no 15', '987654', 'Nagar'),
(6, 4, 'P', 'Apple', '89876', 'Pune'),
(7, 5, 'P', 'Apple', '89876', 'Pune'),
(8, 6, 'P', 'Apple', '89876', 'Pune'),
(9, 7, 'P', 'Apple', '89876', 'Pune');

-- --------------------------------------------------------

--
-- Table structure for table `tblStoreCategory`
--

CREATE TABLE `tblStoreCategory` (
  `StoreCategoryID` int(11) NOT NULL,
  `StoreCategoryName` varchar(100) NOT NULL,
  `StoreCategoryIconURL` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblStoreCategory`
--

INSERT INTO `tblStoreCategory` (`StoreCategoryID`, `StoreCategoryName`, `StoreCategoryIconURL`) VALUES
(1, 'Shops', '1559207217.jpg'),
(2, 'Bio', '1559207699.jpg'),
(4, 'Bio', '1559210595.jpg');

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
-- Table structure for table `tblStoreDepartment`
--

CREATE TABLE `tblStoreDepartment` (
  `StoreDepartment` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `DepartmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblStoreDepartment`
--

INSERT INTO `tblStoreDepartment` (`StoreDepartment`, `StoreID`, `DepartmentID`) VALUES
(4, 1, 1),
(5, 4, 3),
(6, 4, 4),
(7, 2, 1),
(8, 3, 3),
(9, 5, 4),
(10, 6, 2),
(11, 5, 2),
(12, 7, 2);

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
(3, 2, 1, 3, '2019-05-29 06:47:45', '2019-05-29 06:47:45');

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
(2, 'Mahesh', 'Bhosale', 'maheshab555@gmail.com', '$2y$10$RtxgpVTuTJ4wAHUXWhDs2eQb.hb8um/GJc1sw6qqT8IBqf7W/4u8G', 'vWxiHH9BhsdtaAjXtPFLMtAjJ4ix8B9O0nNFFDoohpyubGzgGC0t3R4a9vSs', NULL, '', '', '', '', NULL, NULL, '1558533328.png', 'Y', 'U', '2019-05-22 13:37:22', '2019-06-04 12:47:43'),
(3, 'mamamam', 'nnnnn', 'mahesh@gmail.com', '$2y$10$wWie9b6EATZNXfentdcP3O0B5pngM7Vczw5O4x6PptPU85A4udaeG', 'UqV6rGxUrxcSzQtCsHJh96YZe6z5yRUGb69BpWLC5AeVatXE3XKHXBiwZMr9', '1994-02-02', '', '', '', '', 0, NULL, NULL, 'Y', 'U', '2019-05-28 13:53:11', '2019-06-04 13:02:46'),
(4, 'Kiran', 'Rao', 'kiran@gmail.com', '$2y$10$ntXpAInH7H02dPd4VNrjWu2efLEsTFVcsjNN4N868YHY/TZc6D25u', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'M', '2019-05-30 06:04:37', '2019-05-31 12:24:14'),
(5, 'Amir', 'Khan', 'Amir@gmail.com', '$2y$10$8xsAyNOQIlu1azOw7Kyc1ettWmPF5ktN.fdO34QfWn55utJVcj5tm', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'SM', '2019-05-30 06:11:40', '2019-05-30 06:11:40'),
(10, 'sagar', '', 's@gmail.com', '$2y$10$xjXWVysr0zXLACXUWNQH8OpLf7uy652ylLugdOSjEztsvzoxLQ1B6', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'U', '2019-06-04 10:31:28', '2019-06-04 10:31:28');

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
-- Indexes for table `tblOrderStatus`
--
ALTER TABLE `tblOrderStatus`
  ADD PRIMARY KEY (`OrderStatusId`);

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
-- Indexes for table `tblStoreDepartment`
--
ALTER TABLE `tblStoreDepartment`
  ADD PRIMARY KEY (`StoreDepartment`);

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
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `tblOrderItems`
--
ALTER TABLE `tblOrderItems`
  MODIFY `OrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tblOrders`
--
ALTER TABLE `tblOrders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tblOrderStatus`
--
ALTER TABLE `tblOrderStatus`
  MODIFY `OrderStatusId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblPostComments`
--
ALTER TABLE `tblPostComments`
  MODIFY `PostCommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblPostLikes`
--
ALTER TABLE `tblPostLikes`
  MODIFY `PostLikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `ProductCataloguePriceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblProductCatalogue`
--
ALTER TABLE `tblProductCatalogue`
  MODIFY `ProductCatalogueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblProductCategory`
--
ALTER TABLE `tblProductCategory`
  MODIFY `ProductCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblStore`
--
ALTER TABLE `tblStore`
  MODIFY `StoreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblStoreAddress`
--
ALTER TABLE `tblStoreAddress`
  MODIFY `StoreAddressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblStoreCategory`
--
ALTER TABLE `tblStoreCategory`
  MODIFY `StoreCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblStoreComments`
--
ALTER TABLE `tblStoreComments`
  MODIFY `StoreCommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblStoreDepartment`
--
ALTER TABLE `tblStoreDepartment`
  MODIFY `StoreDepartment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
