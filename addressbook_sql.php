CREATE TABLE addressbook_entries (
	`addressbook_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`addressbook_title` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`addressbook_firstname` VARCHAR(75) DEFAULT NULL,
	`addressbook_lastname` VARCHAR(75) DEFAULT NULL,
	`addressbook_addr1` VARCHAR(75) DEFAULT NULL,
	`addressbook_addr2` VARCHAR(75) DEFAULT NULL,
	`addressbook_city` VARCHAR(75) DEFAULT NULL,
	`addressbook_county` VARCHAR(75) DEFAULT NULL,
	`addressbook_country` CHAR(2) NOT NULL DEFAULT 'GB',
	`addressbook_postcode` CHAR(15)  DEFAULT NULL,
	`addressbook_phone` CHAR(20) DEFAULT NULL,
	`addressbook_mobile` CHAR(20) DEFAULT NULL,
	`addressbook_email1` VARCHAR(50) DEFAULT NULL,
	`addressbook_email2` VARCHAR(50) DEFAULT NULL,
	`addressbook_website` VARCHAR(50) DEFAULT NULL,
	`addressbook_comments` TEXT,
	`addressbook_category` INT(10) UNSIGNED DEFAULT '0',
	`addressbook_role` INT(10) UNSIGNED DEFAULT '0',
	`addressbook_photo` VARCHAR(50) DEFAULT '0',
	`addressbook_lastupdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`addressbook_id`),
	FULLTEXT INDEX `addressbook_firstname` (`addressbook_firstname`),
	FULLTEXT INDEX `addressbook_lastname` (`addressbook_lastname`),
	FULLTEXT INDEX `addressbook_city` (`addressbook_city`),
	FULLTEXT INDEX `addressbook_email1` (`addressbook_email1`)
) ENGINE=MyISAM;
CREATE TABLE `addressbook_roles` (
	`addressbook_roles_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`addressbook_roles_role` CHAR(45) NOT NULL,
	`addressbook_roles_lastupdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`addressbook_roles_id`),
	FULLTEXT INDEX `addressbook_roles_role` (`addressbook_roles_role`)
) ENGINE=MyISAM;
CREATE TABLE `addressbook_titles` (
	`addressbook_titles_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`addressbook_titles_title` CHAR(10) NOT NULL,
	`addressbook_titles_lastupdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`addressbook_titles_id`),
	FULLTEXT INDEX `addressbook_titles_title` (`addressbook_titles_title`)
) ENGINE=MyISAM;
CREATE TABLE `addressbook_countries` (
	`addressbook_countries_id` CHAR(2) NOT NULL,
	`addressbook_countries_name` VARCHAR(50) NOT NULL,
	`addressbook_countries_lastupdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`addressbook_countries_id`),
	FULLTEXT INDEX `addressbook_countries_name` (`addressbook_countries_name`)
) ENGINE=MyISAM;
CREATE TABLE `addressbook_categories` (
	`addressbook_categories_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`addressbook_categories_name` CHAR(35) NOT NULL,
	`addressbook_categories_lastupdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`addressbook_categories_id`),
	FULLTEXT INDEX `addressbook_categories_name` (`addressbook_categories_name`)
) ENGINE=MyISAM;
