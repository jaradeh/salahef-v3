-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}amz_assets`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}amz_assets` (
	`id` BIGINT(15) UNSIGNED NOT NULL AUTO_INCREMENT,
	`post_id` BIGINT(20) NOT NULL,
	`asset` VARCHAR(225) NULL DEFAULT NULL,
	`thumb` VARCHAR(225) NULL DEFAULT NULL,
	`download_status` VARCHAR(20) NULL DEFAULT 'new' COMMENT 'new, success, inprogress, error, remote',
	`hash` VARCHAR(32) NULL DEFAULT NULL,
	`media_id` BIGINT(20) NULL DEFAULT '0',
	`msg` TEXT NULL,
	`date_added` DATETIME NULL DEFAULT NULL,
	`date_download` DATETIME NULL DEFAULT NULL,
	`image_sizes` TEXT NULL,
	PRIMARY KEY (`id`),
	INDEX `post_id` (`post_id`),
	INDEX `hash` (`hash`),
	INDEX `media_id` (`media_id`),
	INDEX `download_status` (`download_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}amz_products`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}amz_products` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`post_id` BIGINT(20) NOT NULL,
	`post_parent` BIGINT(20) NULL DEFAULT '0',
	`type` ENUM('post','variation') NULL DEFAULT 'post',
	`title` TEXT NULL,
	`nb_assets` INT(4) NULL DEFAULT '0',
	`nb_assets_done` INT(4) NULL DEFAULT '0',
	`status` ENUM('new','success') NULL DEFAULT 'new',
	PRIMARY KEY (`post_id`, `id`),
	UNIQUE INDEX `post_id` (`post_id`),
	INDEX `post_parent` (`post_parent`),
	INDEX `type` (`type`),
	INDEX `nb_assets` (`nb_assets`),
	INDEX `nb_assets_done` (`nb_assets_done`),
	INDEX `id` (`id`),
	INDEX `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}amz_cross_sell`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}amz_cross_sell` (
	`ASIN` VARCHAR(10) NOT NULL,
	`products` TEXT NULL,
	`nr_products` INT(11) NULL DEFAULT NULL,
	`add_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`is_variable` CHAR(1) NULL DEFAULT 'N',
	`nb_tries` TINYINT(1) UNSIGNED NULL DEFAULT '0',
	PRIMARY KEY (`ASIN`),
	UNIQUE INDEX `ASIN` (`ASIN`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}amz_report_log`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}amz_report_log` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`log_id` VARCHAR(50) NULL DEFAULT NULL,
	`log_action` VARCHAR(50) NULL DEFAULT NULL,
	`desc` VARCHAR(255) NULL DEFAULT NULL,
	`log_data_type` VARCHAR(50) NULL DEFAULT NULL,
	`log_data` LONGTEXT NULL,
	`source` TEXT NULL,
	`date_add` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `log_id` (`log_id`),
	INDEX `log_action` (`log_action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}amz_queue`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}amz_queue` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`asin` VARCHAR(100) NOT NULL,
	`status` VARCHAR(20) NOT NULL,
	`status_msg` TEXT NOT NULL,
	`from_op` VARCHAR(30) NOT NULL,
	`created_date` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00',
	`imported_date` TIMESTAMP NULL DEFAULT NULL,
	`nb_tries` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`nb_tries_prev` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`product_title` TEXT NULL,
	`country` VARCHAR(10) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `asin_from_op` (`asin`, `from_op`),
	INDEX `nb_tries` (`nb_tries`),
	INDEX `from_op` (`from_op`),
	INDEX `status` (`status`),
	INDEX `country` (`country`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}amz_search`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}amz_search` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`code` VARCHAR(32) NOT NULL,
	`publish` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`status` VARCHAR(20) NOT NULL,
	`status_msg` TEXT NOT NULL,
	`params` TEXT NOT NULL,
	`provider` VARCHAR(20) NOT NULL,
	`search_title` VARCHAR(100) NOT NULL,
	`country` VARCHAR(10) NOT NULL,
	`recurrency` VARCHAR(10) NOT NULL,
	`created_date` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00',
	`started_at` TIMESTAMP NULL DEFAULT NULL,
	`ended_at` TIMESTAMP NULL DEFAULT NULL,
	`run_date` TIMESTAMP NULL DEFAULT NULL,
	`nb_tries` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `code` (`code`),
	INDEX `provider` (`provider`),
	INDEX `country` (`country`),
	INDEX `recurrency` (`recurrency`),
	INDEX `status` (`status`),
	INDEX `publish` (`publish`),
	INDEX `run_date` (`run_date`),
	INDEX `nb_tries` (`nb_tries`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}amz_locale_reference`
--
-- `country` ENUM('BR','CA','CN','DE','ES','FR','IN','IT','JP','MX','UK','US') NOT NULL DEFAULT 'US',

CREATE TABLE IF NOT EXISTS `{wp_prefix}amz_locale_reference` (
	`ID` INT(10) NOT NULL AUTO_INCREMENT,
	`country` VARCHAR(3) NOT NULL DEFAULT 'US',
	`searchIndex` VARCHAR(50) NOT NULL,
	`department` VARCHAR(100) NOT NULL,
	`browseNode` BIGINT(20) NOT NULL DEFAULT '0',
	`sortValues` TEXT NOT NULL,
	`itemSearchParams` TEXT NOT NULL,
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `country_searchIndex` (`country`, `searchIndex`),
	INDEX `searchIndex` (`searchIndex`),
	INDEX `department` (`department`),
	INDEX `browseNode` (`browseNode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}amz_amzkeys`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}amz_amzkeys` (
	`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`access_key` VARCHAR(100) NOT NULL,
	`secret_key` VARCHAR(100) NOT NULL,
	`publish` ENUM('Y','N') NOT NULL DEFAULT 'Y',
	`locked` CHAR(1) NOT NULL DEFAULT 'N',
	`lock_time` TIMESTAMP NULL DEFAULT NULL,
	`nb_requests` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`nb_requests_valid` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`ratio_success` FLOAT(5,2) UNSIGNED NOT NULL DEFAULT '0.00',
	`last_request_time` TIMESTAMP NULL DEFAULT NULL,
	`last_request_status` VARCHAR(50) NULL DEFAULT NULL,
	`last_request_input` MEDIUMTEXT NULL,
	`last_request_output` MEDIUMTEXT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `access_key_secret_key` (`access_key`, `secret_key`),
	INDEX `publish_locked_lock_time` (`publish`, `locked`, `lock_time`),
	INDEX `locked_lock_time` (`locked`, `lock_time`),
	INDEX `lock_time` (`lock_time`),
	INDEX `last_request_time` (`last_request_time`),
	INDEX `ratio_success` (`ratio_success`),
	INDEX `nb_requests` (`nb_requests`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}amz_amazon_cache`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}amz_amazon_cache` (
	`ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`cache_name` VARCHAR(100) NOT NULL,
	`cache_type` VARCHAR(20) NOT NULL,
	`country` VARCHAR(10) NOT NULL,
	`response` LONGTEXT NOT NULL,
	`response_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `cache_name_cache_type` (`cache_name`, `cache_type`),
	INDEX `cache_type` (`cache_type`),
	INDEX `response_date` (`response_date`)
)  ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;
