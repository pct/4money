--
-- Table structure for table `option`
--

DROP TABLE IF EXISTS `option`;
CREATE TABLE `option` (
    `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `option_key` varchar(255) NOT NULL,
    `option_value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`option_id`),
    UNIQUE KEY `option_key` (`option_key`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `quotation`
--

DROP TABLE IF EXISTS `quotation`;
CREATE TABLE `quotation` (
    `quotation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `quotation_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `company_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `company_info` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `company_contact` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `customer_info` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `customer_notes` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `items` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `sub_total_price` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `vat_price` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `total_price` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `bank_info` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `status` set('confirmed','canceled','paid','wait') NOT NULL DEFAULT 'wait',
    PRIMARY KEY (`quotation_id`),
    KEY `quotation_name` (`quotation_name`),
    KEY `confirm` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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

--
-- `account`
--
DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `acc_name` varchar(32) NOT NULL,
  `acc_pwd` text,
  `acc_salt` text,
  `acc_auth_type` set('db','pop3') NOT NULL DEFAULT 'db',
  `acc_flag` set('enable','disable') NOT NULL DEFAULT 'disable',
  `acc_company` text,
  PRIMARY KEY  (`acc_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Default User/Password  admin/admin
--
INSERT INTO `account` (`acc_name`, `acc_pwd`, `acc_salt`, `acc_auth_type`, `acc_flag`, `acc_company`) VALUES
('admin', '4777989ca4bb45db927125baf62221537370c557', 'default', 'db', 'enable', NULL);

--
-- `account_log`
--
DROP TABLE IF EXISTS `account_log`;
CREATE TABLE IF NOT EXISTS `account_log` (
`log_time` TEXT NOT NULL ,
`log_user` TEXT NOT NULL ,
`log_event` TEXT NOT NULL ,
`log_ip` TEXT NOT NULL
) ENGINE = MyISAM DEFAULT CHARSET=utf8;