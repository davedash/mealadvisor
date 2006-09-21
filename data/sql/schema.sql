
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- user
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;


CREATE TABLE `user`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`userid` VARCHAR(255),
	`email` VARCHAR(128),
	`password_md5` VARCHAR(32),
	`open_id` INTEGER,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- restaurant
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `restaurant`;


CREATE TABLE `restaurant`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`stripped_title` VARCHAR(128),
	`approved` INTEGER,
	`average_rating` FLOAT(2,1),
	`num_ratings` INTEGER,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	`version_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `restaurant_FI_1` (`version_id`),
	CONSTRAINT `restaurant_FK_1`
		FOREIGN KEY (`version_id`)
		REFERENCES `restaurant_version` (`id`)
		ON DELETE SET NULL
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- restaurant_search_index
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `restaurant_search_index`;


CREATE TABLE `restaurant_search_index`
(
	`restaurant_id` INTEGER,
	`word` VARCHAR(255),
	`weight` INTEGER,
	KEY `word_index`(`word`),
	INDEX `restaurant_search_index_FI_1` (`restaurant_id`),
	CONSTRAINT `restaurant_search_index_FK_1`
		FOREIGN KEY (`restaurant_id`)
		REFERENCES `restaurant` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- restaurant_version
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `restaurant_version`;


CREATE TABLE `restaurant_version`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`chain` INTEGER,
	`description` TEXT,
	`html_description` TEXT,
	`url` VARCHAR(255),
	`created_at` DATETIME,
	`restaurant_id` INTEGER,
	`user_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `restaurant_version_FI_1` (`restaurant_id`),
	CONSTRAINT `restaurant_version_FK_1`
		FOREIGN KEY (`restaurant_id`)
		REFERENCES `restaurant` (`id`)
		ON DELETE CASCADE,
	INDEX `restaurant_version_FI_2` (`user_id`),
	CONSTRAINT `restaurant_version_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
		ON DELETE SET NULL
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- location
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `location`;


CREATE TABLE `location`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`restaurant_id` INTEGER,
	`stripped_title` VARCHAR(255),
	`name` VARCHAR(255),
	`address` VARCHAR(255),
	`city` VARCHAR(128),
	`state` VARCHAR(16),
	`zip` VARCHAR(9),
	`country_id` CHAR(2),
	`latitude` FLOAT(10,7),
	`longitude` FLOAT(10,7),
	`phone` VARCHAR(16),
	`approved` INTEGER,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `location_FI_1` (`restaurant_id`),
	CONSTRAINT `location_FK_1`
		FOREIGN KEY (`restaurant_id`)
		REFERENCES `restaurant` (`id`),
	INDEX `location_FI_2` (`country_id`),
	CONSTRAINT `location_FK_2`
		FOREIGN KEY (`country_id`)
		REFERENCES `country` (`iso`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- menu_image
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `menu_image`;


CREATE TABLE `menu_image`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`restaurant_id` INTEGER,
	`location_id` INTEGER,
	`filename` VARCHAR(255),
	`approved` INTEGER,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `menu_image_FI_1` (`restaurant_id`),
	CONSTRAINT `menu_image_FK_1`
		FOREIGN KEY (`restaurant_id`)
		REFERENCES `restaurant` (`id`),
	INDEX `menu_image_FI_2` (`location_id`),
	CONSTRAINT `menu_image_FK_2`
		FOREIGN KEY (`location_id`)
		REFERENCES `location` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- menu_item
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `menu_item`;


CREATE TABLE `menu_item`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`url` VARCHAR(255),
	`version_id` INTEGER,
	`restaurant_id` INTEGER,
	`approved` INTEGER,
	`average_rating` FLOAT(2,1),
	`num_ratings` INTEGER,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `menu_item_FI_1` (`version_id`),
	CONSTRAINT `menu_item_FK_1`
		FOREIGN KEY (`version_id`)
		REFERENCES `menuitem_version` (`id`),
	INDEX `menu_item_FI_2` (`restaurant_id`),
	CONSTRAINT `menu_item_FK_2`
		FOREIGN KEY (`restaurant_id`)
		REFERENCES `restaurant` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- menuitem_version
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `menuitem_version`;


CREATE TABLE `menuitem_version`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`description` TEXT,
	`html_description` TEXT,
	`location_id` INTEGER,
	`menuitem_id` INTEGER,
	`user_id` INTEGER,
	`price` VARCHAR(16),
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `menuitem_version_FI_1` (`location_id`),
	CONSTRAINT `menuitem_version_FK_1`
		FOREIGN KEY (`location_id`)
		REFERENCES `location` (`id`),
	INDEX `menuitem_version_FI_2` (`menuitem_id`),
	CONSTRAINT `menuitem_version_FK_2`
		FOREIGN KEY (`menuitem_id`)
		REFERENCES `menu_item` (`id`)
		ON DELETE CASCADE,
	INDEX `menuitem_version_FI_3` (`user_id`),
	CONSTRAINT `menuitem_version_FK_3`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- menuitem_search_index
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `menuitem_search_index`;


CREATE TABLE `menuitem_search_index`
(
	`menuitem_id` INTEGER,
	`word` VARCHAR(255),
	`weight` INTEGER,
	KEY `word_index`(`word`),
	INDEX `menuitem_search_index_FI_1` (`menuitem_id`),
	CONSTRAINT `menuitem_search_index_FK_1`
		FOREIGN KEY (`menuitem_id`)
		REFERENCES `menu_item` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- restaurant_note
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `restaurant_note`;


CREATE TABLE `restaurant_note`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER,
	`note` TEXT,
	`html_note` TEXT,
	`restaurant_id` INTEGER,
	`location_id` INTEGER,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `restaurant_note_FI_1` (`user_id`),
	CONSTRAINT `restaurant_note_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
		ON DELETE SET NULL,
	INDEX `restaurant_note_FI_2` (`restaurant_id`),
	CONSTRAINT `restaurant_note_FK_2`
		FOREIGN KEY (`restaurant_id`)
		REFERENCES `restaurant` (`id`)
		ON DELETE CASCADE,
	INDEX `restaurant_note_FI_3` (`location_id`),
	CONSTRAINT `restaurant_note_FK_3`
		FOREIGN KEY (`location_id`)
		REFERENCES `location` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- menuitem_note
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `menuitem_note`;


CREATE TABLE `menuitem_note`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`menu_item_id` INTEGER,
	`user_id` INTEGER,
	`note` TEXT,
	`html_note` TEXT,
	`updated_at` DATETIME,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `menuitem_note_FI_1` (`menu_item_id`),
	CONSTRAINT `menuitem_note_FK_1`
		FOREIGN KEY (`menu_item_id`)
		REFERENCES `menu_item` (`id`),
	INDEX `menuitem_note_FI_2` (`user_id`),
	CONSTRAINT `menuitem_note_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- restaurant_rating
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `restaurant_rating`;


CREATE TABLE `restaurant_rating`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`restaurant_id` INTEGER,
	`value` INTEGER,
	`location_id` INTEGER,
	`user_id` INTEGER,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `restaurant_rating_FI_1` (`restaurant_id`),
	CONSTRAINT `restaurant_rating_FK_1`
		FOREIGN KEY (`restaurant_id`)
		REFERENCES `restaurant` (`id`)
		ON DELETE CASCADE,
	INDEX `restaurant_rating_FI_2` (`location_id`),
	CONSTRAINT `restaurant_rating_FK_2`
		FOREIGN KEY (`location_id`)
		REFERENCES `location` (`id`)
		ON DELETE CASCADE,
	INDEX `restaurant_rating_FI_3` (`user_id`),
	CONSTRAINT `restaurant_rating_FK_3`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- menuitem_rating
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `menuitem_rating`;


CREATE TABLE `menuitem_rating`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`menu_item_id` INTEGER,
	`user_id` INTEGER,
	`value` INTEGER,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `menuitem_rating_FI_1` (`menu_item_id`),
	CONSTRAINT `menuitem_rating_FK_1`
		FOREIGN KEY (`menu_item_id`)
		REFERENCES `menu_item` (`id`)
		ON DELETE CASCADE,
	INDEX `menuitem_rating_FI_2` (`user_id`),
	CONSTRAINT `menuitem_rating_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- menuitem_tag
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `menuitem_tag`;


CREATE TABLE `menuitem_tag`
(
	`menu_item_id` INTEGER,
	`user_id` INTEGER,
	`tag` VARCHAR(100),
	`normalized_tag` VARCHAR(100),
	`created_at` DATETIME,
	KEY `normalized_tag_index`(`normalized_tag`),
	INDEX `menuitem_tag_FI_1` (`menu_item_id`),
	CONSTRAINT `menuitem_tag_FK_1`
		FOREIGN KEY (`menu_item_id`)
		REFERENCES `menu_item` (`id`),
	INDEX `menuitem_tag_FI_2` (`user_id`),
	CONSTRAINT `menuitem_tag_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- country
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `country`;


CREATE TABLE `country`
(
	`iso` CHAR(2)  NOT NULL,
	`name` VARCHAR(80),
	`printable_name` VARCHAR(80),
	`iso3` CHAR(3),
	`numcode` SMALLINT,
	PRIMARY KEY (`iso`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- menu_item_image
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `menu_item_image`;


CREATE TABLE `menu_item_image`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER,
	`menu_item_id` INTEGER,
	`data` LONGBLOB,
	`md5sum` VARCHAR(32),
	PRIMARY KEY (`id`),
	INDEX `menu_item_image_FI_1` (`user_id`),
	CONSTRAINT `menu_item_image_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`),
	INDEX `menu_item_image_FI_2` (`menu_item_id`),
	CONSTRAINT `menu_item_image_FK_2`
		FOREIGN KEY (`menu_item_id`)
		REFERENCES `menu_item` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- restaurant_tag
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `restaurant_tag`;


CREATE TABLE `restaurant_tag`
(
	`restaurant_id` INTEGER  NOT NULL,
	`user_id` INTEGER  NOT NULL,
	`created_at` DATETIME,
	`tag` VARCHAR(100),
	`normalized_tag` VARCHAR(100)  NOT NULL,
	PRIMARY KEY (`restaurant_id`,`user_id`,`normalized_tag`),
	KEY `normalized_tag_index`(`normalized_tag`),
	CONSTRAINT `restaurant_tag_FK_1`
		FOREIGN KEY (`restaurant_id`)
		REFERENCES `restaurant` (`id`),
	INDEX `restaurant_tag_FI_2` (`user_id`),
	CONSTRAINT `restaurant_tag_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
