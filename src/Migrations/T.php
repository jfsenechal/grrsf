<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 8/03/19
 * Time: 22:20
 */

namespace App\Migrations;


class T
{
    public function t()
    {
        $this->addSql('ALTER TABLE grr_setting DROP PRIMARY KEY');

        $this->addSql(
            'ALTER TABLE grr_setting ADD id INT AUTO_INCREMENT NOT NULL, CHANGE NAME NAME VARCHAR(32) NOT NULL'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B09F0A2D68B693B2 ON grr_setting (NAME)');
        $this->addSql('ALTER TABLE grr_setting ADD PRIMARY KEY (id)');
        $this->addSql(
            "ALTER TABLE `grr_area` CHANGE `access` `access_back` CHAR(1) NOT NULL DEFAULT ''"
        );
        $this->addSql(
            "ALTER TABLE `grr_area` CHANGE `display_days` `display_days_back` VARCHAR(7) NOT NULL DEFAULT 'yyyyyyy'"
        );
        $this->addSql(
            "ALTER TABLE `grr_utilisateurs` DROP PRIMARY KEY"
        );

        $this->addSql(
            "ALTER TABLE `grr_area` CHANGE `display_days` `display_days_back` VARCHAR(7) NOT NULL DEFAULT 'yyyyyyy'"
        );
    }
}