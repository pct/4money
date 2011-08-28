--
-- Table structure for table `customer`
--
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
    `customer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `customer_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `invoice_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `customer_uid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `customer_addr` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `invoice_addr` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `customer_phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `customer_fax` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `contact_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `contact_phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `contact_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `customer_notes` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`customer_id`),
    KEY `customer_uid` (`customer_uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
