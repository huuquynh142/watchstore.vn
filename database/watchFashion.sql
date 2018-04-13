-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.17-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for watch_fashion
DROP DATABASE IF EXISTS `watch_fashion`;
CREATE DATABASE IF NOT EXISTS `watch_fashion` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `watch_fashion`;

-- Dumping structure for table watch_fashion.banner
DROP TABLE IF EXISTS `banner`;
CREATE TABLE IF NOT EXISTS `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` int(11) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `image` varchar(5000) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.banner: ~0 rows (approximately)
DELETE FROM `banner`;
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `banner` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.department
DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.department: ~3 rows (approximately)
DELETE FROM `department`;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` (`id`, `department_name`) VALUES
	(100, 'Product'),
	(101, 'Sale'),
	(102, 'CRM');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.member
DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(200) DEFAULT NULL,
  `sex` tinyint(1) DEFAULT '0',
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `number_hits` int(11) DEFAULT '0',
  `address` varchar(200) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.member: ~1 rows (approximately)
DELETE FROM `member`;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` (`id`, `fullname`, `sex`, `phone_number`, `email`, `password`, `number_hits`, `address`, `avatar`, `created_at`) VALUES
	(100, 'Nguyễn Hữu Quỳnh', 1, '01672729181', 'huuquynh142@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 0, 'hà nội , việt nam', 'WP_09 Tháng Năm 2015_qstore.jpg', '2018-01-28 11:51:21');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.pay_partner
DROP TABLE IF EXISTS `pay_partner`;
CREATE TABLE IF NOT EXISTS `pay_partner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(500) DEFAULT NULL,
  `link` varchar(1000) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.pay_partner: ~0 rows (approximately)
DELETE FROM `pay_partner`;
/*!40000 ALTER TABLE `pay_partner` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_partner` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.producer
DROP TABLE IF EXISTS `producer`;
CREATE TABLE IF NOT EXISTS `producer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(200) DEFAULT NULL COMMENT 'tên công ty',
  `trademark` varchar(200) DEFAULT NULL COMMENT 'thương hiệu',
  `country_of_origin` varchar(100) DEFAULT NULL COMMENT 'nước sản xuất',
  `address` varchar(300) DEFAULT NULL COMMENT 'địa chỉ',
  `phone_number` varchar(100) DEFAULT NULL COMMENT 'số điện thoại',
  `email` varchar(300) DEFAULT NULL COMMENT 'email',
  `website` varchar(500) DEFAULT NULL COMMENT 'trang chủ công ty',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.producer: ~4 rows (approximately)
DELETE FROM `producer`;
/*!40000 ALTER TABLE `producer` DISABLE KEYS */;
INSERT INTO `producer` (`id`, `company_name`, `trademark`, `country_of_origin`, `address`, `phone_number`, `email`, `website`, `created_at`) VALUES
	(100, 'EPOS', 'EPOS SWISS', 'Thụy Sĩ', 'Solothurnstrasse 44 2543 Lengnau Switzerland', ' +41 32 323 81 82', 'info@epos.ch', 'https://www.epos.ch/', '2018-01-28 12:19:16'),
	(101, 'Casio Computer Co., Ltd', 'CASIO', 'Nhật Bản', ' Tokyo, Nhật Bản', '+603-2742-1253', 'corporateenquiry@casio.co.in', 'http://www.casio-intl.com/vn/vi/', '2018-01-28 12:36:17'),
	(102, 'Stuhrling', 'Stuhrling Original Swiss', 'Thụy sĩ', '449 20th Street Brooklyn, NY 11215-6247 U.S.A.', '718.840.5760', 'customerservice@stuhrling.com', 'https://www.stuhrling.com/', '2018-01-28 12:44:26'),
	(103, 'Atlantic Watch Production Ltd.', 'ATLANTIC SWISS', 'Thụy Sỹ', 'Solothurnstrasse 44 CH - 2543 Lengnau', '', 'info@atlantic-watch.ch', 'https://www.atlantic-watches.ch/', '2018-01-28 12:47:28');
/*!40000 ALTER TABLE `producer` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.product
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producer_id` int(11) NOT NULL DEFAULT '0',
  `description_id` varchar(100) DEFAULT NULL,
  `product_detail_id` int(11) DEFAULT '0',
  `quantity` int(11) DEFAULT '0',
  `import_price` varchar(50) DEFAULT '0',
  `sale_price` varchar(50) DEFAULT '0',
  `discount` double DEFAULT '0',
  `image_id` int(11) DEFAULT '0',
  `view` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.product: ~14 rows (approximately)
DELETE FROM `product`;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`id`, `producer_id`, `description_id`, `product_detail_id`, `quantity`, `import_price`, `sale_price`, `discount`, `image_id`, `view`, `created_at`) VALUES
	(100, 103, 'AT-61756.43.61G', 100, 50, '20000000', '25750000', 0, 0, 0, '2018-01-28 12:54:09'),
	(101, 103, 'AT-64751.45.31', 101, 100, '23000000', '26350000', 0, 0, 0, '2018-01-28 13:01:52'),
	(102, 103, 'AT-50354.45.21', 102, 100, '10000000', '13980000', 0, 0, 0, '2018-01-28 13:07:05'),
	(103, 103, 'AT-20347.45.21', 103, 100, '6000000', '9780000', 0, 0, 0, '2018-01-28 13:12:03'),
	(104, 102, 'ST-213T.333X2', 104, 100, '230000000', '255000000', 0, 0, 0, '2018-01-28 13:19:19'),
	(105, 102, 'ST-536.333X2', 105, 100, '80000000', '86000000', 0, 0, 0, '2018-01-28 13:27:06'),
	(106, 101, 'CA-MTP-1169G-9ARDF', 106, 100, '1000000', '1328000', 0, 0, 0, '2018-01-28 13:32:45'),
	(107, 101, 'CA- EQB-510DC-1ADR', 107, 100, '14500000', '16403000', 0, 0, 0, '2018-01-28 13:36:51'),
	(108, 101, 'CA-SHE-4023DP-7ADR', 108, 100, '3500000', '4365000', 0, 0, 0, '2018-01-28 13:39:43'),
	(109, 101, 'CA-LTP-1237D-7ADF', 109, 100, '950000', '1080000', 0, 0, 0, '2018-01-28 13:42:49'),
	(110, 101, 'CA-LTP-1275D-1ADF', 110, 100, '600000', '878000', 0, 0, 0, '2018-01-28 13:45:28'),
	(111, 100, 'E-3437.132.22.18.27', 111, 100, '30000000', '33300000', 0, 0, 0, '2018-01-28 13:49:24'),
	(112, 100, 'E-3420.132.22.18.27', 112, 100, '36500000', '39700000', 0, 0, 0, '2018-01-28 13:52:25'),
	(113, 100, 'E-8000.700.22.65.15', 114, 100, '13000000', '15000000', 0, 0, 0, '2018-01-28 13:55:59');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.product_detail
DROP TABLE IF EXISTS `product_detail`;
CREATE TABLE IF NOT EXISTS `product_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(200) DEFAULT NULL COMMENT 'tên sản phẩm',
  `shell_material` varchar(200) DEFAULT NULL COMMENT 'chất liệu vỏ',
  `wire_material` varchar(200) DEFAULT NULL COMMENT 'chất liệu dây',
  `guarantee` varchar(200) DEFAULT NULL COMMENT 'Bảo hành',
  `glasses` varchar(200) DEFAULT NULL COMMENT 'mắt kính',
  `shell_diameter` varchar(200) DEFAULT NULL COMMENT 'đường kính vỏ',
  `shell_thickness` varchar(200) DEFAULT NULL COMMENT 'độ dày vỏ',
  `water_resistant` varchar(200) DEFAULT NULL COMMENT 'chống nước',
  `type` varchar(200) DEFAULT NULL COMMENT 'loại đồng hô',
  `is_electronic` tinyint(4) DEFAULT '0' COMMENT 'có phải là đồng hồ điện tử',
  `motor` varchar(200) DEFAULT NULL COMMENT 'động cơ',
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.product_detail: ~15 rows (approximately)
DELETE FROM `product_detail`;
/*!40000 ALTER TABLE `product_detail` DISABLE KEYS */;
INSERT INTO `product_detail` (`id`, `product_name`, `shell_material`, `wire_material`, `guarantee`, `glasses`, `shell_diameter`, `shell_thickness`, `water_resistant`, `type`, `is_electronic`, `motor`, `comment`) VALUES
	(100, 'ATLANTIC 61756.43.61G', 'Thép không gỉ', 'Thép không gỉ', '10 năm', 'Sapphire', '42mm', '', '10 atm', 'NAM', 0, '', ''),
	(101, 'ALANTIC 64751.45.31', 'Thép không gỉ', 'Dây da', ' 10 năm', 'Sapphire', '42 mm', '', '5 ATM', 'NAM', 0, '', ''),
	(102, 'ATLANTIC 50354.45.21', 'Thép không gỉ', 'dây da', '10 năm', 'Sapphire', '42 mm', '7 mm', '5 ATM', 'NU', 0, 'Thụy Sỹ', ''),
	(103, 'ATLANTIC 20347.45.21', 'Thép không gỉ', 'Thép không gỉ', '10 năm', 'Sapphire', '28mm', '', '5 atm', 'NU', 0, '', ''),
	(104, 'STUHRLING ORIGINAL TOURBILLON 213T.333X2', 'Mạ vàng 18k', 'Dây da cá sấu', '10 năm', 'Sapphire', '44,8 mm', '13 mm', '10 ATM', 'NAM', 0, 'Tourbillon', ''),
	(105, 'STUHRLING ORIGINAL TOURBILLON 536.333X2', 'Thép không gỉ mạ vang 18K', ' Dây da cá sấu', '10 năm', 'Saphia', '44mm', '13mm', '10 ATM', 'NAM', 0, '', ''),
	(106, 'CASIO MTP-1169G-9ARDF', 'Thép không gỉ', 'Thép không gỉ', '1 năm', 'Mineral Crystal', '', '', '', 'NAM', 1, '', ''),
	(107, 'CASIO EQB-510DC-1ADR', 'Thép không gỉ', 'Thép không gỉ', '1 năm', 'Mineral Crystal', '', '', '', 'NAM', 1, '', ''),
	(108, 'CASIO SHE-4023DP-7ADR', 'Thép không gỉ', 'Thép không gỉ', '1 năm', 'Mineral Crystal', '', '', '', 'NU', 1, '', ''),
	(109, 'CASIO LTP-1237D-7ADF', 'Thép không gỉ', 'Thép không gỉ', '1 năm', 'Mineral Crystal', '', '', '', 'NU', 1, '', ''),
	(110, 'CASIO LTP-1275D-1ADF', 'Thép không gỉ', 'Thép không gỉ', '1 năm', 'Mineral Crystal', '', '', '', 'NU', 1, '', ''),
	(111, 'EPOS SWISS 3437.132.22.18.27', 'Thép không gỉ', 'Dây da', '10 năm', 'Sapphire chống xước', '40 mm', '7,7 mm', '3 ATM', 'NAM', 0, 'Cơ tự động', ''),
	(112, 'EPOS SWISS 3420.132.22.18.27', 'Thép không gỉ mạ vàng PVD', 'Thép không gỉ mạ vàng PVD', '10 năm', 'Sapphire chống xước', '40 mm', '7,7 mm', ' 3 ATM', 'NAM', 0, '', ''),
	(113, ' EPOS 8000.700.22.65.15', 'Mạ vàng PVD', '', '10 năm', 'Sapphire', '34 mm', '7 mm', '3 ATM', 'NU', 1, '', ''),
	(114, ' EPOS 8000.700.22.65.15', 'Mạ vàng PVD', '', '10 năm', 'Sapphire', '34 mm', '7 mm', '3 ATM', 'NU', 1, '', '');
/*!40000 ALTER TABLE `product_detail` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.product_image
DROP TABLE IF EXISTS `product_image`;
CREATE TABLE IF NOT EXISTS `product_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `image` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.product_image: ~18 rows (approximately)
DELETE FROM `product_image`;
/*!40000 ALTER TABLE `product_image` DISABLE KEYS */;
INSERT INTO `product_image` (`id`, `product_id`, `image`) VALUES
	(100, 100, '1815446753_61756.43.61G.jpg'),
	(101, 101, '1074803314_64751.45.31.jpg'),
	(102, 102, '1666096416_AT-50354.45.21 12.780.000.jpg'),
	(103, 103, '50028282_20347.45.21_4s.jpg'),
	(104, 104, '1668051262_ST-213T.333X2.jpg'),
	(105, 104, '857843265_ST-Smaill1.jpg'),
	(106, 104, '588434944_ST-Smaill2.jpg'),
	(107, 105, 'images(5).jpg'),
	(108, 106, '1006720152_Dong-ho-Casio-MTP-1169G-9ARDF.png'),
	(109, 107, '444973204_Dong-ho-Casio-EQB-510DC-1ADR.jpg'),
	(110, 108, 'Dong-ho-Casio-SHE-4023DP-7ADR.jpg'),
	(111, 109, '397104567_Dong-ho-casio-LTP-1237D-7ADF.jpg'),
	(112, 110, '578891491_Dong-ho-casio-LTP-1275D-1ADF.jpg'),
	(113, 111, '541181865__3437_gold_brown.jpg'),
	(114, 112, '416029194_E-3420.152.22.16.32.jpg'),
	(115, 113, '382964343_Dong-ho-Epos-Swiss-8000.700.22.65.15.jpg'),
	(116, 113, '1747154983_12430547812382.jpg'),
	(117, 113, '1762863812_12430547746846.jpg');
/*!40000 ALTER TABLE `product_image` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.promotion
DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(200) DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.promotion: ~0 rows (approximately)
DELETE FROM `promotion`;
/*!40000 ALTER TABLE `promotion` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotion` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.purchase_invoices
DROP TABLE IF EXISTS `purchase_invoices`;
CREATE TABLE IF NOT EXISTS `purchase_invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producer_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.purchase_invoices: ~0 rows (approximately)
DELETE FROM `purchase_invoices`;
/*!40000 ALTER TABLE `purchase_invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_invoices` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.purchase_invoices_detail
DROP TABLE IF EXISTS `purchase_invoices_detail`;
CREATE TABLE IF NOT EXISTS `purchase_invoices_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_invoices_id` int(11) DEFAULT '0',
  `product_id` int(11) DEFAULT '0',
  `quantity` int(50) DEFAULT '0',
  `price` varchar(200) DEFAULT NULL,
  `name_seller` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.purchase_invoices_detail: ~0 rows (approximately)
DELETE FROM `purchase_invoices_detail`;
/*!40000 ALTER TABLE `purchase_invoices_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_invoices_detail` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.sales_invoice
DROP TABLE IF EXISTS `sales_invoice`;
CREATE TABLE IF NOT EXISTS `sales_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `member_id` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.sales_invoice: ~0 rows (approximately)
DELETE FROM `sales_invoice`;
/*!40000 ALTER TABLE `sales_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_invoice` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.sales_invoice_detail
DROP TABLE IF EXISTS `sales_invoice_detail`;
CREATE TABLE IF NOT EXISTS `sales_invoice_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_invoice_id` int(11) DEFAULT '0',
  `quantity` int(11) DEFAULT '0',
  `price` varchar(100) DEFAULT NULL,
  `discount` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.sales_invoice_detail: ~0 rows (approximately)
DELETE FROM `sales_invoice_detail`;
/*!40000 ALTER TABLE `sales_invoice_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_invoice_detail` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.slider
DROP TABLE IF EXISTS `slider`;
CREATE TABLE IF NOT EXISTS `slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `description` text,
  `priority` int(11) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.slider: ~0 rows (approximately)
DELETE FROM `slider`;
/*!40000 ALTER TABLE `slider` DISABLE KEYS */;
/*!40000 ALTER TABLE `slider` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `is_admin` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.users: ~2 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `active`, `is_admin`, `created_at`) VALUES
	(100, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 1, '2018-01-27 21:51:16'),
	(101, 'quynh', '202cb962ac59075b964b07152d234b70', 1, 1, '2018-01-27 21:51:31');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table watch_fashion.user_information
DROP TABLE IF EXISTS `user_information`;
CREATE TABLE IF NOT EXISTS `user_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `department_id` int(11) DEFAULT '0',
  `last_name` varchar(200) DEFAULT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `sex` tinyint(4) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `phone_number` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `avartar` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table watch_fashion.user_information: ~0 rows (approximately)
DELETE FROM `user_information`;
/*!40000 ALTER TABLE `user_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_information` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
