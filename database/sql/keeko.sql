
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
    `parent_id` INTEGER(10),
    `macrolanguage_status` CHAR(1),
    `name` VARCHAR(128),
    `native_name` VARCHAR(128),
    `collate` VARCHAR(10),
    `subtag` VARCHAR(76),
    `prefix` VARCHAR(76),
    `scope_id` INTEGER(10) NOT NULL,
    `type_id` INTEGER(10),
    `family_id` INTEGER(10),
    `default_script_id` INTEGER(10),
    PRIMARY KEY (`id`),
    INDEX `language_fi_parent` (`parent_id`),
    INDEX `language_fi_scope` (`scope_id`),
    INDEX `language_fi_type` (`type_id`),
    INDEX `language_fi_script` (`default_script_id`),
    INDEX `language_fi_family` (`family_id`),
    CONSTRAINT `language_fk_parent`
        FOREIGN KEY (`parent_id`)
        REFERENCES `kk_language` (`id`),
    CONSTRAINT `language_fk_scope`
        FOREIGN KEY (`scope_id`)
        REFERENCES `kk_language_scope` (`id`),
    CONSTRAINT `language_fk_type`
        FOREIGN KEY (`type_id`)
        REFERENCES `kk_language_type` (`id`),
    CONSTRAINT `language_fk_script`
        FOREIGN KEY (`default_script_id`)
        REFERENCES `kk_language_script` (`id`),
    CONSTRAINT `language_fk_family`
        FOREIGN KEY (`family_id`)
        REFERENCES `kk_language_family` (`id`)
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
-- kk_language_script
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_language_script`;

CREATE TABLE `kk_language_script`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `alpha_4` CHAR(4),
    `numeric` INTEGER,
    `name` VARCHAR(255),
    `alias` VARCHAR(255),
    `direction` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_language_family
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_language_family`;

CREATE TABLE `kk_language_family`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `parent_id` INTEGER,
    `alpha_3` VARCHAR(3),
    `name` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_language_variant
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_language_variant`;

CREATE TABLE `kk_language_variant`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `subtag` VARCHAR(76),
    `prefixes` VARCHAR(255),
    `comment` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_currency
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_currency`;

CREATE TABLE `kk_currency`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `numeric` INTEGER,
    `alpha_3` CHAR(3),
    `name` VARCHAR(45),
    `symbol_left` VARCHAR(45),
    `symbol_right` VARCHAR(45),
    `decimal_digits` INTEGER,
    `sub_divisor` INTEGER,
    `sub_symbol_left` VARCHAR(45),
    `sub_symbol_right` VARCHAR(45),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_country
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_country`;

CREATE TABLE `kk_country`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `numeric` INTEGER,
    `alpha_2` CHAR(2),
    `alpha_3` CHAR(3),
    `short_name` VARCHAR(128),
    `ioc` CHAR(3),
    `tld` VARCHAR(3),
    `phone` VARCHAR(16),
    `capital` VARCHAR(128),
    `postal_code_format` VARCHAR(255),
    `postal_code_regex` VARCHAR(255),
    `continent_id` INTEGER NOT NULL,
    `currency_id` INTEGER NOT NULL,
    `type_id` INTEGER(10) NOT NULL,
    `subtype_id` INTEGER(10),
    `sovereignity_id` INTEGER(10),
    `formal_name` VARCHAR(128),
    `formal_native_name` VARCHAR(128),
    `short_native_name` VARCHAR(128),
    `bbox_sw_lat` FLOAT,
    `bbox_sw_lng` FLOAT,
    `bbox_ne_lat` FLOAT,
    `bbox_ne_lng` FLOAT,
    PRIMARY KEY (`id`),
    INDEX `country_fi_continent` (`continent_id`),
    INDEX `country_fi_currency` (`currency_id`),
    INDEX `country_fi_type` (`type_id`),
    INDEX `country_fi_subtype` (`subtype_id`),
    INDEX `country_fi_sovereignity` (`sovereignity_id`),
    CONSTRAINT `country_fk_continent`
        FOREIGN KEY (`continent_id`)
        REFERENCES `kk_continent` (`id`),
    CONSTRAINT `country_fk_currency`
        FOREIGN KEY (`currency_id`)
        REFERENCES `kk_currency` (`id`),
    CONSTRAINT `country_fk_type`
        FOREIGN KEY (`type_id`)
        REFERENCES `kk_region_type` (`id`),
    CONSTRAINT `country_fk_subtype`
        FOREIGN KEY (`subtype_id`)
        REFERENCES `kk_region_type` (`id`),
    CONSTRAINT `country_fk_sovereignity`
        FOREIGN KEY (`sovereignity_id`)
        REFERENCES `kk_country` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_continent
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_continent`;

CREATE TABLE `kk_continent`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `parent_id` INTEGER,
    `numeric` INTEGER,
    `name` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_subdivision
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_subdivision`;

CREATE TABLE `kk_subdivision`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(45),
    `name` VARCHAR(128),
    `native_name` VARCHAR(128),
    `alt_names` VARCHAR(255),
    `parent_id` INTEGER,
    `country_id` INTEGER NOT NULL,
    `type_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `subdivision_fi_country` (`country_id`),
    INDEX `subdivision_fi_type` (`type_id`),
    CONSTRAINT `subdivision_fk_country`
        FOREIGN KEY (`country_id`)
        REFERENCES `kk_country` (`id`),
    CONSTRAINT `subdivision_fk_type`
        FOREIGN KEY (`type_id`)
        REFERENCES `kk_region_type` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_region_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_region_type`;

CREATE TABLE `kk_region_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(128),
    `area_id` INTEGER(10) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `region_type_fi_area` (`area_id`),
    CONSTRAINT `region_type_fk_area`
        FOREIGN KEY (`area_id`)
        REFERENCES `kk_region_area` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_region_area
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_region_area`;

CREATE TABLE `kk_region_area`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(128),
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
    `name` VARCHAR(128),
    `locale` VARCHAR(76),
    `language_id` INTEGER(10),
    `ext_language_id` INTEGER(10),
    `region` VARCHAR(3),
    `script_id` INTEGER(10),
    `is_default` TINYINT(1),
    PRIMARY KEY (`id`),
    INDEX `localization_fi_parent` (`parent_id`),
    INDEX `localization_fi_language` (`language_id`),
    INDEX `localization_fi_extlang` (`ext_language_id`),
    INDEX `localization_fi_script` (`script_id`),
    CONSTRAINT `localization_fk_parent`
        FOREIGN KEY (`parent_id`)
        REFERENCES `kk_localization` (`id`),
    CONSTRAINT `localization_fk_language`
        FOREIGN KEY (`language_id`)
        REFERENCES `kk_language` (`id`),
    CONSTRAINT `localization_fk_extlang`
        FOREIGN KEY (`ext_language_id`)
        REFERENCES `kk_language` (`id`),
    CONSTRAINT `localization_fk_script`
        FOREIGN KEY (`script_id`)
        REFERENCES `kk_language_script` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_localization_variant
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_localization_variant`;

CREATE TABLE `kk_localization_variant`
(
    `localization_id` INTEGER(10) NOT NULL,
    `variant_id` INTEGER(10) NOT NULL,
    PRIMARY KEY (`localization_id`,`variant_id`),
    INDEX `localization_variant_fi_variant` (`variant_id`),
    CONSTRAINT `localization_variant_fk_localization`
        FOREIGN KEY (`localization_id`)
        REFERENCES `kk_localization` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `localization_variant_fk_variant`
        FOREIGN KEY (`variant_id`)
        REFERENCES `kk_language_variant` (`id`)
        ON DELETE RESTRICT
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
    INDEX `application_uri_fi_application` (`application_id`),
    INDEX `application_uri_fi_localization` (`localization_id`),
    CONSTRAINT `application_uri_fk_application`
        FOREIGN KEY (`application_id`)
        REFERENCES `kk_application` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `application_uri_fk_localization`
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
    INDEX `action_fi_module` (`module_id`),
    CONSTRAINT `action_fk_module`
        FOREIGN KEY (`module_id`)
        REFERENCES `kk_module` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_extension
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_extension`;

CREATE TABLE `kk_extension`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(255) NOT NULL,
    `data` TEXT NOT NULL,
    `package_id` INTEGER(10) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `extension_fi_package` (`package_id`),
    CONSTRAINT `extension_fk_package`
        FOREIGN KEY (`package_id`)
        REFERENCES `kk_package` (`id`)
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
    INDEX `api_fi_action` (`action_id`),
    CONSTRAINT `api_fk_action`
        FOREIGN KEY (`action_id`)
        REFERENCES `kk_action` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- kk_session
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `kk_session`;

CREATE TABLE `kk_session`
(
    `token` VARCHAR(32) NOT NULL,
    `user_id` INTEGER(10) NOT NULL,
    `ip` VARCHAR(128),
    `user_agent` VARCHAR(512),
    `browser` VARCHAR(512),
    `device` VARCHAR(512),
    `os` VARCHAR(512),
    `location` VARCHAR(512),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`token`),
    INDEX `session_fi_user` (`user_id`),
    CONSTRAINT `session_fk_user`
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
    `user_name` VARCHAR(100),
    `password` VARCHAR(100),
    `given_name` VARCHAR(100),
    `family_name` VARCHAR(100),
    `nick_name` VARCHAR(100),
    `display_name` VARCHAR(100),
    `email` VARCHAR(255),
    `birthday` DATE,
    `sex` TINYINT DEFAULT -1 COMMENT '1 = male; 0 = female',
    `slug` VARCHAR(100),
    `password_recover_code` VARCHAR(32),
    `password_recover_time` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
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
    INDEX `user_group_fi_group` (`group_id`),
    CONSTRAINT `user_group_fk_group`
        FOREIGN KEY (`group_id`)
        REFERENCES `kk_group` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `user_group_fk_user`
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
    INDEX `group_action_fi_action` (`action_id`),
    CONSTRAINT `group_action_fk_group`
        FOREIGN KEY (`group_id`)
        REFERENCES `kk_group` (`id`)
        ON DELETE RESTRICT,
    CONSTRAINT `group_action_fk_action`
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
    INDEX `activity_fi_user` (`actor_id`),
    INDEX `activity_fi_object` (`object_id`),
    INDEX `activity_fi_target` (`target_id`),
    CONSTRAINT `activity_fk_user`
        FOREIGN KEY (`actor_id`)
        REFERENCES `kk_user` (`id`),
    CONSTRAINT `activity_fk_object`
        FOREIGN KEY (`object_id`)
        REFERENCES `kk_activity_object` (`id`),
    CONSTRAINT `activity_fk_target`
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
