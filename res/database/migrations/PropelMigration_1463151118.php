<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1463151118.
 * Generated on 2016-05-13 16:51:58 by thomas
 */
class PropelMigration_1463151118
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'keeko' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `kk_auth`;

ALTER TABLE `kk_application_uri` DROP FOREIGN KEY `application_uri_fk_application`;

ALTER TABLE `kk_application_uri` DROP FOREIGN KEY `application_uri_fk_localization`;

ALTER TABLE `kk_application_uri` ADD CONSTRAINT `application_uri_fk_application`
    FOREIGN KEY (`application_id`)
    REFERENCES `kk_application` (`id`)
    ON DELETE RESTRICT;

ALTER TABLE `kk_application_uri` ADD CONSTRAINT `application_uri_fk_localization`
    FOREIGN KEY (`localization_id`)
    REFERENCES `kk_localization` (`id`)
    ON DELETE RESTRICT;

ALTER TABLE `kk_group_action` DROP FOREIGN KEY `group_action_fk_action`;

ALTER TABLE `kk_group_action` DROP FOREIGN KEY `group_action_fk_group`;

ALTER TABLE `kk_group_action` ADD CONSTRAINT `group_action_fk_action`
    FOREIGN KEY (`action_id`)
    REFERENCES `kk_action` (`id`)
    ON DELETE RESTRICT;

ALTER TABLE `kk_group_action` ADD CONSTRAINT `group_action_fk_group`
    FOREIGN KEY (`group_id`)
    REFERENCES `kk_group` (`id`)
    ON DELETE RESTRICT;

ALTER TABLE `kk_localization_variant` DROP FOREIGN KEY `localization_variant_fk_localization`;

ALTER TABLE `kk_localization_variant` DROP FOREIGN KEY `localization_variant_fk_variant`;

ALTER TABLE `kk_localization_variant` ADD CONSTRAINT `localization_variant_fk_localization`
    FOREIGN KEY (`localization_id`)
    REFERENCES `kk_localization` (`id`)
    ON DELETE RESTRICT;

ALTER TABLE `kk_localization_variant` ADD CONSTRAINT `localization_variant_fk_variant`
    FOREIGN KEY (`variant_id`)
    REFERENCES `kk_language_variant` (`id`)
    ON DELETE RESTRICT;

ALTER TABLE `kk_user_group` DROP FOREIGN KEY `user_group_fk_group`;

ALTER TABLE `kk_user_group` DROP FOREIGN KEY `user_group_fk_user`;

ALTER TABLE `kk_user_group` ADD CONSTRAINT `user_group_fk_group`
    FOREIGN KEY (`group_id`)
    REFERENCES `kk_group` (`id`)
    ON DELETE RESTRICT;

ALTER TABLE `kk_user_group` ADD CONSTRAINT `user_group_fk_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `kk_user` (`id`)
    ON DELETE RESTRICT;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'keeko' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `kk_application_uri` DROP FOREIGN KEY `application_uri_fk_application`;

ALTER TABLE `kk_application_uri` DROP FOREIGN KEY `application_uri_fk_localization`;

ALTER TABLE `kk_application_uri` ADD CONSTRAINT `application_uri_fk_application`
    FOREIGN KEY (`application_id`)
    REFERENCES `kk_application` (`id`);

ALTER TABLE `kk_application_uri` ADD CONSTRAINT `application_uri_fk_localization`
    FOREIGN KEY (`localization_id`)
    REFERENCES `kk_localization` (`id`);

ALTER TABLE `kk_group_action` DROP FOREIGN KEY `group_action_fk_action`;

ALTER TABLE `kk_group_action` DROP FOREIGN KEY `group_action_fk_group`;

ALTER TABLE `kk_group_action` ADD CONSTRAINT `group_action_fk_action`
    FOREIGN KEY (`action_id`)
    REFERENCES `kk_action` (`id`);

ALTER TABLE `kk_group_action` ADD CONSTRAINT `group_action_fk_group`
    FOREIGN KEY (`group_id`)
    REFERENCES `kk_group` (`id`);

ALTER TABLE `kk_localization_variant` DROP FOREIGN KEY `localization_variant_fk_localization`;

ALTER TABLE `kk_localization_variant` DROP FOREIGN KEY `localization_variant_fk_variant`;

ALTER TABLE `kk_localization_variant` ADD CONSTRAINT `localization_variant_fk_localization`
    FOREIGN KEY (`localization_id`)
    REFERENCES `kk_localization` (`id`);

ALTER TABLE `kk_localization_variant` ADD CONSTRAINT `localization_variant_fk_variant`
    FOREIGN KEY (`variant_id`)
    REFERENCES `kk_language_variant` (`id`);

ALTER TABLE `kk_user_group` DROP FOREIGN KEY `user_group_fk_group`;

ALTER TABLE `kk_user_group` DROP FOREIGN KEY `user_group_fk_user`;

ALTER TABLE `kk_user_group` ADD CONSTRAINT `user_group_fk_group`
    FOREIGN KEY (`group_id`)
    REFERENCES `kk_group` (`id`);

ALTER TABLE `kk_user_group` ADD CONSTRAINT `user_group_fk_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `kk_user` (`id`);

CREATE TABLE `kk_auth`
(
    `token` VARCHAR(32) NOT NULL,
    `user_id` INTEGER(10) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`token`),
    INDEX `auth_fi_user` (`user_id`),
    CONSTRAINT `auth_fk_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `kk_user` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}