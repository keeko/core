
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- kk_language
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_language`;

CREATE TABLE `kk_language`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `alpha_2` VARCHAR(2),
    `alpha_3T` VARCHAR(3),
    `alpha_3B` VARCHAR(3),
    `alpha_3` VARCHAR(3),
    `local_name` VARCHAR(128),
    `en_name` VARCHAR(128),
    `collate` VARCHAR(10),
    `scope_id` INTEGER(10),
    `type_id` INTEGER(10),
    PRIMARY KEY (`id`),
    INDEX `kk_language_fi_696d4c` (`scope_id`),
    INDEX `kk_language_fi_2bc68d` (`type_id`),
    CONSTRAINT `kk_language_fk_696d4c`
        FOREIGN KEY (`scope_id`)
        REFERENCES `kk_language_scope` (`id`),
    CONSTRAINT `kk_language_fk_2bc68d`
        FOREIGN KEY (`type_id`)
        REFERENCES `kk_language_type` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_language_scope
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_language_scope`;

CREATE TABLE `kk_language_scope`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_language_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_language_type`;

CREATE TABLE `kk_language_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_localization
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_localization`;

CREATE TABLE `kk_localization`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `parent_id` INTEGER(10),
    `language_id` INTEGER(10),
    `country_iso_nr` INTEGER(10),
    `is_default` TINYINT(1),
    PRIMARY KEY (`id`),
    INDEX `kk_localization_fi_a96561` (`parent_id`),
    INDEX `kk_localization_fi_d74563` (`language_id`),
    INDEX `kk_localization_fi_705f0c` (`country_iso_nr`),
    CONSTRAINT `kk_localization_fk_a96561`
        FOREIGN KEY (`parent_id`)
        REFERENCES `kk_localization` (`id`),
    CONSTRAINT `kk_localization_fk_d74563`
        FOREIGN KEY (`language_id`)
        REFERENCES `kk_language` (`id`),
    CONSTRAINT `kk_localization_fk_705f0c`
        FOREIGN KEY (`country_iso_nr`)
        REFERENCES `kk_country` (`iso_nr`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_country
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_country`;

CREATE TABLE `kk_country`
(
    `iso_nr` INTEGER NOT NULL,
    `alpha_2` CHAR(2),
    `alpha_3` CHAR(3),
    `ioc` CHAR(3),
    `capital` VARCHAR(128),
    `tld` VARCHAR(3),
    `phone` VARCHAR(16),
    `territory_iso_nr` INTEGER NOT NULL,
    `currency_iso_nr` INTEGER NOT NULL,
    `official_local_name` VARCHAR(128),
    `official_en_name` VARCHAR(128),
    `short_local_name` VARCHAR(128),
    `short_en_name` VARCHAR(128),
    `bbox_sw_lat` FLOAT,
    `bbox_sw_lng` FLOAT,
    `bbox_ne_lat` FLOAT,
    `bbox_ne_lng` FLOAT,
    PRIMARY KEY (`iso_nr`),
    INDEX `kk_country_fi_5a119b` (`territory_iso_nr`),
    INDEX `kk_country_fi_816fd6` (`currency_iso_nr`),
    CONSTRAINT `kk_country_fk_5a119b`
        FOREIGN KEY (`territory_iso_nr`)
        REFERENCES `kk_territory` (`iso_nr`),
    CONSTRAINT `kk_country_fk_816fd6`
        FOREIGN KEY (`currency_iso_nr`)
        REFERENCES `kk_currency` (`iso_nr`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_territory
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_territory`;

CREATE TABLE `kk_territory`
(
    `iso_nr` INTEGER NOT NULL,
    `parent_iso_nr` INTEGER,
    `name_en` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`iso_nr`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_currency
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_currency`;

CREATE TABLE `kk_currency`
(
    `iso_nr` INTEGER NOT NULL,
    `iso3` CHAR(3) NOT NULL,
    `en_name` VARCHAR(45),
    `symbol_left` VARCHAR(45),
    `symbol_right` VARCHAR(45),
    `decimal_digits` INTEGER,
    `sub_divisor` INTEGER,
    `sub_symbol_left` VARCHAR(45),
    `sub_symbol_right` VARCHAR(45),
    PRIMARY KEY (`iso_nr`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_subdivision
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_subdivision`;

CREATE TABLE `kk_subdivision`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `iso` VARCHAR(45),
    `name` VARCHAR(128),
    `local_name` VARCHAR(128),
    `en_name` VARCHAR(128),
    `alt_names` VARCHAR(255),
    `parent_id` INTEGER,
    `country_iso_nr` INTEGER NOT NULL,
    `subdivision_type_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `kk_subdivision_fi_705f0c` (`country_iso_nr`),
    INDEX `kk_subdivision_fi_6bba99` (`subdivision_type_id`),
    CONSTRAINT `kk_subdivision_fk_705f0c`
        FOREIGN KEY (`country_iso_nr`)
        REFERENCES `kk_country` (`iso_nr`),
    CONSTRAINT `kk_subdivision_fk_6bba99`
        FOREIGN KEY (`subdivision_type_id`)
        REFERENCES `kk_subdivision_type` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_subdivision_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_subdivision_type`;

CREATE TABLE `kk_subdivision_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(128),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_package
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_package`;

CREATE TABLE `kk_package`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `title` VARCHAR(255),
    `description` TEXT,
    `installed_version` VARCHAR(50),
    `descendant_class` VARCHAR(100),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_application
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_application`;

CREATE TABLE `kk_application`
(
    `class_name` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL,
    `name` VARCHAR(255),
    `title` VARCHAR(255),
    `description` TEXT,
    `installed_version` VARCHAR(50),
    PRIMARY KEY (`id`),
    CONSTRAINT `kk_application_fk_080aef`
        FOREIGN KEY (`id`)
        REFERENCES `kk_package` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_application_uri
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_application_uri`;

CREATE TABLE `kk_application_uri`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `httphost` VARCHAR(255) NOT NULL,
    `basepath` VARCHAR(255) NOT NULL,
    `secure` TINYINT(1),
    `application_id` INTEGER(10) NOT NULL,
    `localization_id` INTEGER(10) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `kk_application_uri_fi_b1bb40` (`application_id`),
    INDEX `kk_application_uri_fi_4f151e` (`localization_id`),
    CONSTRAINT `kk_application_uri_fk_b1bb40`
        FOREIGN KEY (`application_id`)
        REFERENCES `kk_application` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `kk_application_uri_fk_4f151e`
        FOREIGN KEY (`localization_id`)
        REFERENCES `kk_localization` (`id`)
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_module
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_module`;

CREATE TABLE `kk_module`
(
    `class_name` VARCHAR(255) NOT NULL,
    `activated_version` VARCHAR(50),
    `default_action` VARCHAR(255),
    `slug` VARCHAR(255),
    `has_api` TINYINT(1) DEFAULT 0,
    `id` INTEGER NOT NULL,
    `name` VARCHAR(255),
    `title` VARCHAR(255),
    `description` TEXT,
    `installed_version` VARCHAR(50),
    PRIMARY KEY (`id`),
    CONSTRAINT `kk_module_fk_080aef`
        FOREIGN KEY (`id`)
        REFERENCES `kk_package` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_action
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_action`;

CREATE TABLE `kk_action`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255),
    `class_name` VARCHAR(255) NOT NULL,
    `module_id` INTEGER(10) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `kk_action_fi_326eef` (`module_id`),
    CONSTRAINT `kk_action_fk_326eef`
        FOREIGN KEY (`module_id`)
        REFERENCES `kk_module` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_preference
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_preference`;

CREATE TABLE `kk_preference`
(
    `key` VARCHAR(255) NOT NULL,
    `value` TEXT,
    `module_id` INTEGER(10),
    PRIMARY KEY (`key`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_api
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_api`;

CREATE TABLE `kk_api`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `route` VARCHAR(255) NOT NULL,
    `method` VARCHAR(255) NOT NULL,
    `action_id` INTEGER(10) NOT NULL,
    `required_params` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `kk_api_fi_716bac` (`action_id`),
    CONSTRAINT `kk_api_fk_716bac`
        FOREIGN KEY (`action_id`)
        REFERENCES `kk_action` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_auth
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_auth`;

CREATE TABLE `kk_auth`
(
    `token` VARCHAR(32) NOT NULL,
    `user_id` INTEGER(10) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`token`),
    INDEX `kk_auth_fi_1efe60` (`user_id`),
    CONSTRAINT `kk_auth_fk_1efe60`
        FOREIGN KEY (`user_id`)
        REFERENCES `kk_user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_user`;

CREATE TABLE `kk_user`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `login_name` VARCHAR(100),
    `password` VARCHAR(100),
    `given_name` VARCHAR(100),
    `family_name` VARCHAR(100),
    `display_name` VARCHAR(100),
    `email` VARCHAR(255),
    `birthday` DATE,
    `sex` TINYINT,
    `password_recover_code` VARCHAR(32),
    `password_recover_time` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `login_name_UNIQUE` (`login_name`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_user_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_user_group`;

CREATE TABLE `kk_user_group`
(
    `user_id` INTEGER(10) NOT NULL,
    `group_id` INTEGER(10) NOT NULL,
    PRIMARY KEY (`user_id`,`group_id`),
    INDEX `kk_user_group_fi_8134fe` (`group_id`),
    CONSTRAINT `kk_user_group_fk_8134fe`
        FOREIGN KEY (`group_id`)
        REFERENCES `kk_group` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `kk_user_group_fk_1efe60`
        FOREIGN KEY (`user_id`)
        REFERENCES `kk_user` (`id`)
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_group`;

CREATE TABLE `kk_group`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `owner_id` INTEGER(10),
    `name` VARCHAR(64),
    `is_guest` TINYINT(1),
    `is_default` TINYINT(1),
    `is_active` TINYINT(1) DEFAULT 1,
    `is_system` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_group_action
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_group_action`;

CREATE TABLE `kk_group_action`
(
    `group_id` INTEGER(10) NOT NULL,
    `action_id` INTEGER(10) NOT NULL,
    PRIMARY KEY (`group_id`,`action_id`),
    INDEX `kk_group_action_fi_716bac` (`action_id`),
    CONSTRAINT `kk_group_action_fk_8134fe`
        FOREIGN KEY (`group_id`)
        REFERENCES `kk_group` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `kk_group_action_fk_716bac`
        FOREIGN KEY (`action_id`)
        REFERENCES `kk_action` (`id`)
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_activity
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_activity`;

CREATE TABLE `kk_activity`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `actor_id` INTEGER NOT NULL,
    `verb` VARCHAR(255),
    `object_id` INTEGER NOT NULL,
    `target_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `kk_activity_fi_9bf6dc` (`actor_id`),
    INDEX `kk_activity_fi_9a3d86` (`object_id`),
    INDEX `kk_activity_fi_1e33a8` (`target_id`),
    CONSTRAINT `kk_activity_fk_9bf6dc`
        FOREIGN KEY (`actor_id`)
        REFERENCES `kk_user` (`id`),
    CONSTRAINT `kk_activity_fk_9a3d86`
        FOREIGN KEY (`object_id`)
        REFERENCES `kk_activity_object` (`id`),
    CONSTRAINT `kk_activity_fk_1e33a8`
        FOREIGN KEY (`target_id`)
        REFERENCES `kk_activity_object` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_activity_object
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_activity_object`;

CREATE TABLE `kk_activity_object`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `class_name` VARCHAR(255),
    `type` VARCHAR(255),
    `display_name` VARCHAR(255),
    `url` VARCHAR(255),
    `reference_id` INTEGER,
    `version` INTEGER,
    `extra` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
