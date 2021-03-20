-- --------------------------------------------------------
-- Host:                         127.0.0.1
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
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`AdvertisementID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblAdvertisements: ~18 rows (approximately)
/*!40000 ALTER TABLE `tblAdvertisements` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `tblAdvertisements` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblAdvertisementsImages
CREATE TABLE IF NOT EXISTS `tblAdvertisementsImages` (
  `AdvertisementsPhotoID` int(11) NOT NULL AUTO_INCREMENT,
  `AdvertisementID` int(11) NOT NULL,
  `AdvImageURL` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`AdvertisementsPhotoID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblAdvertisementsImages: ~9 rows (approximately)
/*!40000 ALTER TABLE `tblAdvertisementsImages` DISABLE KEYS */;
INSERT INTO `tblAdvertisementsImages` (`AdvertisementsPhotoID`, `AdvertisementID`, `AdvImageURL`) VALUES
	(4, 13, '1559125605.jpg'),
	(5, 14, '1559126015.jpg'),
	(6, 15, '1559126049.jpg'),
	(7, 18, '15591262910.jpg'),
	(8, 18, '15591262911.png'),
	(9, 18, '15591262912.png'),
	(13, 20, '15591271050.png'),
	(19, 19, '15591278760.png'),
	(20, 19, '15591278761.png'),
	(21, 3, '15591278761.png'),
	(22, 3, '15591278761.png');
/*!40000 ALTER TABLE `tblAdvertisementsImages` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblDepartment
CREATE TABLE IF NOT EXISTS `tblDepartment` (
  `DepartmentID` int(11) NOT NULL AUTO_INCREMENT,
  `DepartmentName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DepartmentCode` int(11) NOT NULL,
  `Lat` double NOT NULL,
  `Lng` double NOT NULL,
  PRIMARY KEY (`DepartmentID`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblDepartment: 102 rows
/*!40000 ALTER TABLE `tblDepartment` DISABLE KEYS */;
INSERT INTO `tblDepartment` (`DepartmentID`, `DepartmentName`, `DepartmentCode`, `Lat`, `Lng`) VALUES
	(1, 'Ain(01)', 0, 46.247571, 5.130768),
	(2, 'Aisne(02)', 0, 49.47692, 3.441737),
	(3, 'Allier(03)', 0, 46.311555, 3.416765),
	(5, 'Hautes-Alpes(05)', 0, 44.600872, 6.322607),
	(4, 'Alpes-de-Haute-Provence(04)', 0, 44.077872, 6.237595),
	(6, 'Alpes-Maritimes(06)', 0, 43.946679, 7.179026),
	(7, 'Ardèche(07)', 0, 44.759629, 4.562443),
	(8, 'Ardennes(08)', 0, 49.762464, 4.628505),
	(9, 'Ariège(09)', 0, 42.932629, 1.443469),
	(10, 'Aube(10)', 0, 48.156342, 4.373246),
	(11, 'Aude(11)', 0, 43.072467, 2.381362),
	(12, 'Aveyron(12)', 0, 44.217975, 2.618927),
	(13, 'Bouches-du-Rhône(13)', 0, 43.591168, 5.31025),
	(14, 'Calvados(14)', 0, 33.610659, -117.837927),
	(15, 'Cantal(15)', 0, 45.1192, 2.632606),
	(16, 'Charente(16)', 0, 45.751996, 0.153476),
	(17, 'Charente-Maritime(17)', 0, 45.74949, -0.773319),
	(18, 'Cher(18)', 0, 46.954005, 2.467191),
	(19, 'Corrèze(19)', 0, 45.432008, 2.019591),
	(20, 'Corse-du-sud(2a)', 0, 41.810263, 8.924534),
	(21, 'Haute-corse(2b)', 0, 42.409788, 9.278558),
	(22, 'Côte-d\'or(21)', 0, 47.51268, 4.635412),
	(23, 'Côtes-d\'armor(22)', 0, 48.51081, -3.326368),
	(24, 'Creuse(23)', 0, 46.037763, 2.062783),
	(25, 'Dordogne(24)', 0, 45.146949, 0.757221),
	(26, 'Doubs(25)', 0, 47.196982, 6.3126),
	(27, 'Drôme(26)', 0, 44.73119, 5.226667),
	(28, 'Eure(27)', 0, 49.118176, 0.958211),
	(29, 'Eure-et-Loir(28)', 0, 48.552524, 1.198981),
	(30, 'Finistère(29)', 0, 48.252025, -3.930052),
	(31, 'Gard(30)', 0, 43.9447, 4.151376),
	(32, 'Haute-Garonne(31)', 0, 43.401046, 1.135302),
	(33, 'Gers(32)', 0, 43.636648, 0.450237),
	(34, 'Gironde(33)', 0, 44.849665, -0.450237),
	(35, 'Hérault(34)', 0, 43.591236, 3.258363),
	(36, 'Ile-et-Vilaine(35)', 0, 48.229202, -1.530069),
	(37, 'Indre(36)', 0, 46.661397, 1.448266),
	(38, 'Indre-et-Loire(37)', 0, 47.289492, 0.816097),
	(39, 'Isère(38)', 0, 44.995775, 5.929348),
	(40, 'Jura(39)', 0, 46.762475, 5.672916),
	(41, 'Landes(40)', 0, 43.941205, -0.753281),
	(42, 'Loir-et-Cher(41)', 0, 47.676191, 1.415907),
	(43, 'Loire(42)', 0, 45.984648, 4.052545),
	(44, 'Haute-Loire(43)', 0, 45.082123, 3.926637),
	(45, 'Loire-Atlantique(44)', 0, 47.278047, -1.815765),
	(46, 'Loiret(45)', 0, 47.900771, 2.201817),
	(47, 'Lot(46)', 0, 44.537936, 1.676069),
	(48, 'Lot-et-Garonne(47)', 0, 44.247017, 0.450237),
	(49, 'Lozère(48)', 0, 44.494203, 3.581269),
	(50, 'Maine-et-Loire(49)', 0, 47.291355, -0.487785),
	(51, 'Manche(50)', 0, 49.114712, -1.311595),
	(52, 'Marne(51)', 0, 49.128754, 4.147544),
	(53, 'Haute-Marne(52)', 0, 48.126097, 5.107132),
	(54, 'Mayenne(53)', 0, 48.23825, -0.504256),
	(55, 'Meurthe-et-Moselle(54)', 0, 48.799701, 6.094701),
	(56, 'Meuse(55)', 0, 49.082432, 5.2824),
	(57, 'Morbihan(56)', 0, 47.885293, -2.900187),
	(58, 'Moselle(57)', 0, 49.098384, 6.552764),
	(59, 'Nièvre(58)', 0, 47.238171, 3.529452),
	(60, 'Nord(59)', 0, 50.385125, 3.264244),
	(61, 'Oise(60)', 0, 49.421457, 2.41464),
	(62, 'Orne(61)', 0, 48.638857, 0.08482),
	(63, 'Pas-de-Calais(62)', 0, 50.573277, 2.324468),
	(64, 'Puy-de-Dôme(63)', 0, 45.712414, 3.015582),
	(65, 'Pyrénées-Atlantiques(64)', 0, 43.326994, -0.753281),
	(66, 'Hautes-Pyrénées(65)', 0, 43.019392, 0.149499),
	(67, 'Pyrénées-Orientales(66)', 0, 42.601291, 2.539603),
	(68, 'Bas-Rhin(67)', 0, 48.634317, 7.525294),
	(69, 'Haut-Rhin(68)', 0, 47.931504, 7.24411),
	(70, 'Rhône(69)', 0, 45.735146, 4.610804),
	(71, 'Haute-Saône(70)', 0, 47.756981, 6.155628),
	(72, 'Saône-et-Loire(71)', 0, 46.582751, 4.486671),
	(73, 'Sarthe(72)', 0, 47.921701, 0.16558),
	(74, 'Savoie(73)', 0, 45.493205, 6.4724),
	(75, 'Haute-Savoie(74)', 0, 46.175679, 6.538962),
	(76, 'Paris(75)', 0, 48.856582, 2.352215),
	(77, 'Seine-Maritime(76)', 0, 49.605419, 0.974844),
	(78, 'Seine-et-Marne(77)', 0, 48.841082, 2.999366),
	(79, 'Yvelines(78)', 0, 48.785094, 1.825657),
	(80, 'Deux-Sèvres(79)', 0, 46.592654, -0.396284),
	(81, 'Somme(80)', 0, 49.914518, 2.27071),
	(82, 'Tarn(81)', 0, 43.92644, 1.988153),
	(83, 'Tarn-et-Garonne(82)', 0, 44.012668, 1.289104),
	(84, 'Var(83)', 0, 43.467646, 6.237595),
	(85, 'Vaucluse(84)', 0, 44.056505, 5.143207),
	(86, 'Vendée(85)', 0, 46.661397, -1.448266),
	(87, 'Vienne(86)', 0, 46.669542, 0.477286),
	(88, 'Haute-Vienne(87)', 0, 45.743517, 1.402548),
	(89, 'Vosges(88)', 0, 48.144643, 6.335593),
	(90, 'Yonne(89)', 0, 47.865273, 3.607982),
	(91, 'Territoire de Belfort(90)', 0, 47.594657, 6.920772),
	(92, 'Essonne(91)', 0, 48.45857, 2.156942),
	(93, 'Hauts-de-Seine(92)', 0, 48.828508, 2.218807),
	(94, 'Seine-Saint-Denis(93)', 0, 48.936181, 2.357443),
	(95, 'Val-de-Marne(94)', 0, 48.793143, 2.474034),
	(96, 'Val-d\'oise(95)', 0, 49.06159, 2.158135),
	(97, 'Mayotte(976)', 0, -12.755121, 45.232242),
	(98, 'Guadeloupe(971)', 0, 27.104463, -82.426337),
	(99, 'Guyane(973)', 0, 4.926615, -52.3001),
	(100, 'Martinique(972)', 0, 32.798854, -96.745316),
	(101, 'Réunion(974)', 0, -20.88837, 55.45834),
	(102, 'Pune Shivajinagar', 0, 18.5314, 73.8546);
/*!40000 ALTER TABLE `tblDepartment` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblOrderItems
CREATE TABLE IF NOT EXISTS `tblOrderItems` (
  `OrderItemID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NOT NULL DEFAULT '0',
  `ProductCatalogueID` int(11) NOT NULL DEFAULT '0',
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `Quantity` int(11) NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`OrderItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblOrderItems: ~27 rows (approximately)
/*!40000 ALTER TABLE `tblOrderItems` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `tblOrderItems` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblOrders
CREATE TABLE IF NOT EXISTS `tblOrders` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `OrderDate` datetime DEFAULT NULL,
  `TotalAmount` double NOT NULL DEFAULT '0',
  `PaymentStatus` char(50) NOT NULL DEFAULT 'N',
  `Status` varchar(50) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`OrderID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblOrders: ~14 rows (approximately)
/*!40000 ALTER TABLE `tblOrders` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `tblOrders` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblOrderStatus
CREATE TABLE IF NOT EXISTS `tblOrderStatus` (
  `OrderStatusId` int(11) NOT NULL AUTO_INCREMENT,
  `StatusId` int(11) NOT NULL,
  `StatusValue` varchar(100) NOT NULL,
  PRIMARY KEY (`OrderStatusId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblOrderStatus: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblOrderStatus` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblOrderStatus` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblPaymentInformation
CREATE TABLE IF NOT EXISTS `tblPaymentInformation` (
  `PaymentInformationId` int(11) NOT NULL AUTO_INCREMENT,
  `IBAN` varchar(50) DEFAULT NULL,
  `BIC` varchar(50) DEFAULT NULL,
  `AccountOwner` varchar(150) DEFAULT NULL,
  `AccountAddress` varchar(200) DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL,
  `PayOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`PaymentInformationId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblPaymentInformation: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblPaymentInformation` DISABLE KEYS */;
INSERT INTO `tblPaymentInformation` (`PaymentInformationId`, `IBAN`, `BIC`, `AccountOwner`, `AccountAddress`, `AddedBy`, `PayOn`, `UpdatedOn`) VALUES
	(4, '1234567890', '777', '3', 'Pune', 2, '2019-06-13 13:30:28', '2019-06-13 13:30:28'),
	(5, '123456789099', '888', '3', 'Alandi', 2, '2019-06-13 13:32:59', '2019-06-13 13:32:59');
/*!40000 ALTER TABLE `tblPaymentInformation` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblPostComments
CREATE TABLE IF NOT EXISTS `tblPostComments` (
  `PostCommentID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `PostID` int(11) NOT NULL DEFAULT '0',
  `Comment` text,
  `CommentedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`PostCommentID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblPostComments: ~3 rows (approximately)
/*!40000 ALTER TABLE `tblPostComments` DISABLE KEYS */;
INSERT INTO `tblPostComments` (`PostCommentID`, `UserID`, `PostID`, `Comment`, `CommentedOn`, `UpdatedOn`) VALUES
	(1, 1, 2, 'Hi AA', '2019-05-28 10:39:29', '2019-05-28 10:39:29'),
	(2, 2, 2, 'Hi Hello', '2019-05-28 10:40:15', '2019-05-28 10:40:15'),
	(3, 2, 3, 'Valid', '2019-05-29 05:35:40', '2019-05-29 05:35:40');
/*!40000 ALTER TABLE `tblPostComments` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblPostDocuments
CREATE TABLE IF NOT EXISTS `tblPostDocuments` (
  `PostDocumentId` int(11) NOT NULL AUTO_INCREMENT,
  `PostID` int(11) DEFAULT NULL,
  `PostDocument` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`PostDocumentId`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblPostDocuments: ~10 rows (approximately)
/*!40000 ALTER TABLE `tblPostDocuments` DISABLE KEYS */;
INSERT INTO `tblPostDocuments` (`PostDocumentId`, `PostID`, `PostDocument`) VALUES
	(1, 6, '1560257479.png'),
	(2, 6, '1560257479.png'),
	(11, 8, '1560319267.png'),
	(12, 11, '1560337171.png'),
	(13, 12, '1560337188.png'),
	(14, 13, '1560337208.png'),
	(15, 15, '1560337847.png'),
	(16, 16, '1560337870.png'),
	(17, 17, '1560337935.pdf'),
	(18, 7, '1560343777.png');
/*!40000 ALTER TABLE `tblPostDocuments` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblPostLikes
CREATE TABLE IF NOT EXISTS `tblPostLikes` (
  `PostLikeID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `PostID` int(11) NOT NULL DEFAULT '0',
  `LikeedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`PostLikeID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblPostLikes: ~3 rows (approximately)
/*!40000 ALTER TABLE `tblPostLikes` DISABLE KEYS */;
INSERT INTO `tblPostLikes` (`PostLikeID`, `UserID`, `PostID`, `LikeedOn`, `UpdatedOn`) VALUES
	(1, 2, 2, '2019-05-28 11:14:52', '2019-05-28 11:14:52'),
	(2, 2, 2, '2019-05-28 11:22:11', '2019-05-28 11:22:11'),
	(3, 2, 1, '2019-06-04 09:41:04', '2019-06-04 09:41:04');
/*!40000 ALTER TABLE `tblPostLikes` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblPosts
CREATE TABLE IF NOT EXISTS `tblPosts` (
  `PostID` int(11) NOT NULL AUTO_INCREMENT,
  `PostDescription` text,
  `PostStatus` char(1) NOT NULL DEFAULT 'Y',
  `UserID` int(11) NOT NULL,
  `PostedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`PostID`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblPosts: ~15 rows (approximately)
/*!40000 ALTER TABLE `tblPosts` DISABLE KEYS */;
INSERT INTO `tblPosts` (`PostID`, `PostDescription`, `PostStatus`, `UserID`, `PostedOn`, `UpdatedOn`) VALUES
	(2, 'Hello', 'Y', 2, '2019-05-28 09:59:25', '2019-05-28 09:59:25'),
	(3, 'Validations check', 'N', 2, '2019-05-29 05:29:29', '2019-06-03 06:08:37'),
	(4, 'Hiiiii', 'Y', 2, '2019-06-11 12:50:40', '2019-06-11 12:50:40'),
	(5, 'Hiiiii', 'Y', 2, '2019-06-11 12:51:04', '2019-06-11 12:51:04'),
	(6, 'Hiiiii', 'Y', 2, '2019-06-11 12:51:19', '2019-06-11 12:51:19'),
	(7, 'File Check', 'Y', 2, '2019-06-11 12:53:08', '2019-06-12 12:49:37'),
	(8, 'Hiiiii', 'Y', 2, '2019-06-12 06:01:06', '2019-06-12 06:01:06'),
	(9, 'Hiiiii', 'Y', 0, '2019-06-12 10:42:25', '2019-06-12 10:42:25'),
	(10, 'Hiiiii', 'Y', 0, '2019-06-12 10:43:48', '2019-06-12 10:43:48'),
	(11, 'Hiiiii', 'Y', 0, '2019-06-12 10:59:31', '2019-06-12 10:59:31'),
	(12, 'Hiiiii', 'Y', 0, '2019-06-12 10:59:48', '2019-06-12 10:59:48'),
	(13, 'Hiiiii', 'Y', 0, '2019-06-12 11:00:07', '2019-06-12 11:00:07'),
	(14, 'Hiiiii', 'Y', 0, '2019-06-12 11:10:15', '2019-06-12 11:10:15'),
	(15, 'Hiiiii', 'Y', 0, '2019-06-12 11:10:47', '2019-06-12 11:10:47'),
	(16, 'Hiiiiihjhjhj', 'Y', 0, '2019-06-12 11:11:10', '2019-06-12 11:11:10'),
	(17, 'Hiiiiihjhjhj', 'Y', 0, '2019-06-12 11:12:15', '2019-06-12 11:12:15');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblProductBasket: ~4 rows (approximately)
/*!40000 ALTER TABLE `tblProductBasket` DISABLE KEYS */;
INSERT INTO `tblProductBasket` (`ProductBasketID`, `UserID`, `ProductCatalogueID`, `StoreID`, `Quantity`, `Amount`) VALUES
	(3, 2, 3, 3, 100, 2000),
	(4, 2, 3, 1, 2, 500),
	(5, 2, 3, 2, 2, 600);
/*!40000 ALTER TABLE `tblProductBasket` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblProductCalaloguePrice
CREATE TABLE IF NOT EXISTS `tblProductCalaloguePrice` (
  `ProductCataloguePriceID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductCatalogueID` int(11) NOT NULL DEFAULT '0',
  `PriceType` varchar(50) DEFAULT NULL,
  `PromotionalPrice` double NOT NULL DEFAULT '0',
  `SellPrice` double NOT NULL DEFAULT '0',
  `Amount` double NOT NULL DEFAULT '0',
  `TotalQuantity` double DEFAULT '0',
  `AvailableQuantity` double DEFAULT '0',
  PRIMARY KEY (`ProductCataloguePriceID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblProductCalaloguePrice: ~0 rows (approximately)
/*!40000 ALTER TABLE `tblProductCalaloguePrice` DISABLE KEYS */;
INSERT INTO `tblProductCalaloguePrice` (`ProductCataloguePriceID`, `ProductCatalogueID`, `PriceType`, `PromotionalPrice`, `SellPrice`, `Amount`, `TotalQuantity`, `AvailableQuantity`) VALUES
	(1, 2, 'A', 1, 12, 1, 1, 2);
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
  `AddFlashSell` char(1) DEFAULT 'N',
  PRIMARY KEY (`ProductCatalogueID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblProductCatalogue: ~3 rows (approximately)
/*!40000 ALTER TABLE `tblProductCatalogue` DISABLE KEYS */;
INSERT INTO `tblProductCatalogue` (`ProductCatalogueID`, `StoreID`, `StoreProductGroupID`, `ProductName`, `ShortDescription`, `LongDescription`, `PhotoURL`, `AddFlashSell`) VALUES
	(2, 2, 1, 'Moto', 'Test', NULL, '1558766727.jpeg', 'Y'),
	(3, 3, 1, 'Dell', 'Test', NULL, '1558766727.jpeg', 'N'),
	(4, 2, 1, 'Mo', 'Test', NULL, '1558766727.jpeg', 'N');
/*!40000 ALTER TABLE `tblProductCatalogue` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblProductCategory
CREATE TABLE IF NOT EXISTS `tblProductCategory` (
  `ProductCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductCategoryName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ProductCategoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblProductCategory: ~2 rows (approximately)
/*!40000 ALTER TABLE `tblProductCategory` DISABLE KEYS */;
INSERT INTO `tblProductCategory` (`ProductCategoryID`, `ProductCategoryName`) VALUES
	(1, 'Cloth'),
	(2, 'Creta');
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
  `IsNonPhysicalStore` char(1) NOT NULL DEFAULT 'N',
  `StoreLink` varchar(255) DEFAULT NULL,
  `Lat` double DEFAULT '0',
  `Lng` double DEFAULT '0',
  PRIMARY KEY (`StoreID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStore: ~19 rows (approximately)
/*!40000 ALTER TABLE `tblStore` DISABLE KEYS */;
INSERT INTO `tblStore` (`StoreID`, `StoreName`, `StoreLogo`, `BackgroundPhoto`, `Description`, `TelephoneNumber`, `StoreCategoryID`, `DeliveryPrice`, `MinimumDelivery`, `ShowOnHomepage`, `CategoryType`, `ShippingCost`, `Presentation`, `IsActive`, `IsNonPhysicalStore`, `StoreLink`, `Lat`, `Lng`) VALUES
	(1, 'MRF', '', '', 'The MRF', '9090909090', 1, 123, 120, '', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(2, 'MRF', '', '', 'TATA Birla', '9999999999', 1, 1, 11, '', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(3, 'Bio', '1559222144.png', '1559222144.png', 'Test', '9898989898', 1, 100, 80, 'Y', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(4, 'Bio', '1559223309.png', '1559223309.png', 'Apple Bio', '9898989898', 1, 100, 80, 'Y', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(5, 'Bio', '1559223527.png', '', 'Apple Bio', '9898989898', 1, 100, 80, '', '', 0, '0', 'N', 'N', NULL, 19.0862, 74.7359),
	(6, 'Men', '1559223544.png', '1559223544.png', 'Apple Bio', '9898989898', 1, 100, 80, 'Y', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(7, 'Bio', '1559645628.jpg', '', 'Apple Bio', '9898989898', 1, 100, 80, '', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(8, 'MRF', '1559726765.jpeg', '', 'The MRF', '9090909090', 2, 123, 120, '', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(9, 'MRF', '1559726826.jpeg', '', 'The MRF', '9090909090', 2, 123, 120, '', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(10, 'MRF', '1559728968.jpeg', '', 'The MRF', '9090909090', 2, 123, 120, '', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(11, 'TATA', '1559728223.jpeg', '', 'The TATA N', '9090909090', 2, 123, 120, '', '', 0, '0', 'Y', 'N', NULL, 19.0862, 74.7359),
	(12, 'TA', '1559886396.jpeg', '', 'The MRF', '9090909090', 2, 123, 120, '', '', 0, '0', 'Y', 'Y', NULL, 19.0862, 74.7359),
	(13, 'TATA', '1559886641.jpeg', '', 'The TATA', '9878787878', 2, 123, 120, '', '', 0, '0', 'Y', 'Y', NULL, 19.0862, 74.7359),
	(14, 'MRF', '', '', 'The MRF', '9090909090', 2, 123, 120, '', '', 0, '0', 'Y', 'N', 'fa', 19.0862, 74.7359),
	(15, 'MRF', '', '', 'The MRF', '9090909090', 2, 123, 120, '', '', 0, '0', 'Y', 'N', 'fa', 19.0862, 74.7359),
	(20, 'MRF', '', '', 'The MRF', '9090909090', 2, 123, 120, '', '', 0, '0', 'Y', 'N', 'fa', 19.0862, 74.7359),
	(21, 'MRF', '', '', 'The MRF', '9090909090', 4, 123, 120, '', '', 0, '0', 'Y', 'N', 'fa', 18.5786, 73.8759),
	(22, 'SKF', '1559979302.png', '', 'The MRF', '9090909090', 4, 123, 120, '', '', 0, '0', 'Y', 'N', 'http://www.google.com', 18.4955, 73.9302),
	(23, 'LT', '1560161359.png', '1560161359.png', 'The MRF', '9090909090', 4, 123, 120, '', '', 0, '0', 'Y', 'N', 'http://www.google.com', 18.5188, 73.8576);
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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreAddress: ~38 rows (approximately)
/*!40000 ALTER TABLE `tblStoreAddress` DISABLE KEYS */;
INSERT INTO `tblStoreAddress` (`StoreAddressID`, `StoreID`, `AddressType`, `StreetName`, `PostalCode`, `City`) VALUES
	(3, 2, 'P', 'Gali No 14', '989890', 'Nashik'),
	(4, 2, 'O', 'Gali no 15', '987654', 'Nagar'),
	(6, 4, 'P', 'Apple', '89876', 'Pune'),
	(7, 5, 'P', 'Apple', '89876', 'Pune'),
	(8, 6, 'P', 'Apple', '89876', 'Pune'),
	(9, 7, 'P', 'Apple', '89876', 'Pune'),
	(10, 8, 'P', 'Gali No 14', '989890', 'Pune'),
	(11, 8, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(12, 9, 'P', 'Gali No 14', '989890', 'Pune'),
	(13, 9, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(16, 11, 'P', 'Gali No 14', '989890', 'Pune'),
	(17, 11, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(26, 10, 'P', 'Gali No 14', '989890', 'Pune'),
	(27, 10, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(28, 12, 'P', 'Gali No 14', '989890', 'Pune'),
	(29, 12, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(34, 13, 'P', 'Gali No 14', '989890', 'Pune'),
	(35, 13, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(36, 14, 'P', 'Gali No 14', '989890', 'Pune'),
	(37, 14, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(38, 15, 'P', 'Gali No 14', '989890', 'Pune'),
	(39, 15, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(40, 16, 'P', 'Gali No 14', '989890', 'Pune'),
	(41, 16, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(42, 17, 'P', 'Gali No 14', '989890', 'Pune'),
	(43, 17, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(44, 18, 'P', 'Gali No 14', '989890', 'Pune'),
	(45, 18, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(46, 19, 'P', 'Gali No 14', '989890', 'Pune'),
	(47, 19, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(48, 20, 'P', 'Gali No 14', '989890', 'Pune'),
	(49, 20, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(50, 21, 'P', 'Gali No 14', '989890', 'Pune'),
	(51, 21, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(52, 22, 'P', 'Gali No 14', '989890', 'Pune'),
	(53, 22, 'O', 'Gali no 15', '987654', 'Mumbai'),
	(54, 23, 'P', 'Gali No 14', '989890', 'Pune'),
	(55, 23, 'O', 'Gali no 15', '987654', 'Mumbai');
/*!40000 ALTER TABLE `tblStoreAddress` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreCategory
CREATE TABLE IF NOT EXISTS `tblStoreCategory` (
  `StoreCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreCategoryName` varchar(100) NOT NULL,
  `StoreCategoryIconURL` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`StoreCategoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreCategory: ~3 rows (approximately)
/*!40000 ALTER TABLE `tblStoreCategory` DISABLE KEYS */;
INSERT INTO `tblStoreCategory` (`StoreCategoryID`, `StoreCategoryName`, `StoreCategoryIconURL`) VALUES
	(1, 'Shops', '1559207217.jpg'),
	(2, 'Bio', '1559207699.jpg'),
	(4, 'Games toys', '1559210595.jpg');
/*!40000 ALTER TABLE `tblStoreCategory` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreComments
CREATE TABLE IF NOT EXISTS `tblStoreComments` (
  `StoreCommentID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `Comment` text,
  `CommentedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`StoreCommentID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreComments: ~2 rows (approximately)
/*!40000 ALTER TABLE `tblStoreComments` DISABLE KEYS */;
INSERT INTO `tblStoreComments` (`StoreCommentID`, `StoreID`, `UserID`, `Comment`, `CommentedOn`, `UpdatedOn`) VALUES
	(1, 2, 1, 'Hi Amit', '2019-05-28 07:32:55', '2019-05-28 07:32:55');
/*!40000 ALTER TABLE `tblStoreComments` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreDepartment
CREATE TABLE IF NOT EXISTS `tblStoreDepartment` (
  `StoreDepartment` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL,
  `DepartmentID` int(11) NOT NULL,
  PRIMARY KEY (`StoreDepartment`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreDepartment: ~26 rows (approximately)
/*!40000 ALTER TABLE `tblStoreDepartment` DISABLE KEYS */;
INSERT INTO `tblStoreDepartment` (`StoreDepartment`, `StoreID`, `DepartmentID`) VALUES
	(4, 1, 1),
	(5, 4, 3),
	(6, 4, 4),
	(7, 2, 1),
	(8, 3, 3),
	(9, 5, 4),
	(10, 6, 2),
	(11, 5, 2),
	(12, 7, 2),
	(13, 8, 2),
	(14, 8, 3),
	(15, 9, 2),
	(16, 9, 3),
	(19, 11, 2),
	(20, 11, 3),
	(29, 10, 2),
	(30, 10, 3),
	(33, 2, 2),
	(34, 14, 2),
	(35, 15, 2),
	(36, 16, 2),
	(37, 17, 2),
	(38, 18, 2),
	(39, 19, 2),
	(40, 20, 2),
	(41, 21, 2),
	(42, 22, 2),
	(43, 23, 2);
/*!40000 ALTER TABLE `tblStoreDepartment` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreFavourites
CREATE TABLE IF NOT EXISTS `tblStoreFavourites` (
  `StoreFavouritesID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `Status` char(2) NOT NULL DEFAULT 'N',
  `CreatedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`StoreFavouritesID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreFavourites: ~4 rows (approximately)
/*!40000 ALTER TABLE `tblStoreFavourites` DISABLE KEYS */;
INSERT INTO `tblStoreFavourites` (`StoreFavouritesID`, `StoreID`, `UserID`, `Status`, `CreatedOn`, `UpdatedOn`) VALUES
	(1, 2, 2, 'N', NULL, '2019-05-28 06:58:11'),
	(2, 2, 2, 'Y', '2019-05-28 06:42:53', '2019-05-28 06:42:53'),
	(3, 1, 2, 'N', '2019-05-28 06:43:08', '2019-05-28 06:43:08'),
	(4, 1, 2, 'Y', '2019-05-28 06:55:48', '2019-05-28 06:55:48');
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
  `StoreId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`StoreProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreProducts: ~21 rows (approximately)
/*!40000 ALTER TABLE `tblStoreProducts` DISABLE KEYS */;
INSERT INTO `tblStoreProducts` (`StoreProductID`, `StoreCategoryID`, `ProductCategoryID`, `StoreId`) VALUES
	(1, 2, 3, 0),
	(7, 1, 0, 10),
	(8, 2, 0, 10),
	(11, 1, 0, 13),
	(12, 2, 0, 13),
	(13, 1, 0, 14),
	(14, 2, 0, 14),
	(15, 1, 0, 15),
	(16, 2, 0, 15),
	(17, 1, 0, 16),
	(18, 2, 0, 16),
	(19, 1, 0, 17),
	(20, 2, 0, 17),
	(21, 1, 0, 18),
	(22, 2, 0, 18),
	(23, 1, 0, 19),
	(24, 2, 0, 19),
	(25, 1, 0, 20),
	(26, 2, 0, 20),
	(27, 1, 0, 21),
	(28, 2, 0, 21),
	(29, 1, 0, 22),
	(30, 2, 0, 22),
	(31, 1, 0, 23),
	(32, 2, 0, 23);
/*!40000 ALTER TABLE `tblStoreProducts` ENABLE KEYS */;

-- Dumping structure for table tout-paie.tblStoreRatings
CREATE TABLE IF NOT EXISTS `tblStoreRatings` (
  `StoreRatingID` int(11) NOT NULL AUTO_INCREMENT,
  `StoreID` int(11) NOT NULL DEFAULT '0',
  `UserID` int(11) NOT NULL DEFAULT '0',
  `Rating` int(11) NOT NULL DEFAULT '0',
  `CommentedOn` datetime DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`StoreRatingID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblStoreRatings: ~3 rows (approximately)
/*!40000 ALTER TABLE `tblStoreRatings` DISABLE KEYS */;
INSERT INTO `tblStoreRatings` (`StoreRatingID`, `StoreID`, `UserID`, `Rating`, `CommentedOn`, `UpdatedOn`) VALUES
	(1, 2, 1, 4, '2019-05-28 07:52:35', '2019-05-28 07:52:35'),
	(2, 1, 1, 5, '2019-05-28 07:52:52', '2019-05-28 07:52:52'),
	(3, 3, 1, 3, '2019-05-29 06:47:45', '2019-05-29 06:47:45'),
	(4, 1, 1, 3, '2019-05-29 06:47:45', '2019-05-29 06:47:45');
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table tout-paie.tblUsers: ~5 rows (approximately)
/*!40000 ALTER TABLE `tblUsers` DISABLE KEYS */;
INSERT INTO `tblUsers` (`UserID`, `FirstName`, `LastName`, `Email`, `password`, `RememberToken`, `DateOfBirth`, `MobileNumber`, `StreetName`, `PostalCode`, `City`, `DepartmentID`, `Status`, `ProfilePhotoURL`, `isActive`, `Role`, `CreatedOn`, `UpdatedOn`) VALUES
	(2, 'Mahesh', 'Bhosale', 'maheshab555@gmail.com', '$2y$10$RtxgpVTuTJ4wAHUXWhDs2eQb.hb8um/GJc1sw6qqT8IBqf7W/4u8G', 'ZksJS1ggGaQh6YfNn77taFkKEp7FTzX9jseDLlqIWxxpZXvFbkx7YnsmIy0Q', NULL, '', '', '', '', 102, NULL, '1558533328.png', 'Y', 'U', '2019-05-22 13:37:22', '2019-06-13 09:57:18'),
	(3, 'mamamam', 'nnnnn', 'mahesh@gmail.com', '$2y$10$RtxgpVTuTJ4wAHUXWhDs2eQb.hb8um/GJc1sw6qqT8IBqf7W/4u8G', 'UqV6rGxUrxcSzQtCsHJh96YZe6z5yRUGb69BpWLC5AeVatXE3XKHXBiwZMr9', '1994-02-02', '', '', '', '', 0, NULL, NULL, 'Y', 'U', '2019-05-28 13:53:11', '2019-06-04 13:02:46'),
	(4, 'Kiran', 'Rao', 'kiran@gmail.com', '$2y$10$ntXpAInH7H02dPd4VNrjWu2efLEsTFVcsjNN4N868YHY/TZc6D25u', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'M', '2019-05-30 06:04:37', '2019-05-31 12:24:14'),
	(5, 'Amir', 'Khan', 'Amir@gmail.com', '$2y$10$8xsAyNOQIlu1azOw7Kyc1ettWmPF5ktN.fdO34QfWn55utJVcj5tm', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'SA', '2019-05-30 06:11:40', '2019-05-30 06:11:40'),
	(10, 'sagar', '', 's@gmail.com', '$2y$10$xjXWVysr0zXLACXUWNQH8OpLf7uy652ylLugdOSjEztsvzoxLQ1B6', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'U', '2019-06-04 10:31:28', '2019-06-04 10:31:28'),
	(11, 'dsfgds', '', 'sh@gmail.com', '$2y$10$s9EN/NUivzqwJfbOhf4Oa.YrEdrLXnhoxd.QWzCFaYM5jApirZIUy', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'U', '2019-06-06 14:08:16', '2019-06-06 14:08:16');
/*!40000 ALTER TABLE `tblUsers` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
