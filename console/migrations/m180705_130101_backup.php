<?php

use yii\db\Schema;

class m180705_130101_backup extends \yii\db\Migration
{
    public function up()
    {
        $tables = Yii::$app->db->schema->getTableNames();
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        if (!in_array(Yii::$app->db->tablePrefix.'user', $tables))  {
          $this->createTable('user', [
              'id' => $this->primaryKey(),
              'username' => $this->string(255)->notNull(),
              'auth_key' => $this->string(32)->notNull(),
              'password_hash' => $this->string(255)->notNull(),
              'password_reset_token' => $this->string(255),
              'email' => $this->string(32)->notNull(),
              'telefon' => $this->string(32)->notNull(),
              'status' => $this->smallInteger(6)->notNull()->defaultValue(10),
              'created_at' => $this->integer(11)->notNull(),
              'updated_at' => $this->integer(11)->notNull(),
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."user` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'l_art', $tables))  {
          $this->createTable('l_art', [
              'id' => $this->primaryKey(),
              'bezeichnung' => $this->string(32)->notNull(),
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."l_art` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'l_heizungsart', $tables))  {
          $this->createTable('l_heizungsart', [
              'id' => $this->primaryKey(),
              'bezeichnung' => $this->string(255)->notNull(),
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."l_heizungsart` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'immobilien', $tables))  {
          $this->createTable('immobilien', [
              'id' => $this->primaryKey(),
              'bezeichnung' => $this->text(),
              'sonstiges' => $this->text(),
              'strasse' => $this->string(45)->notNull(),
              'wohnflaeche' => $this->smallInteger(6)->notNull(),
              'raeume' => $this->smallInteger(6)->notNull(),
              'geldbetrag' => $this->decimal(10,0)->notNull(),
              'k_grundstuecksgroesse' => $this->smallInteger(6),
              'k_provision' => $this->decimal(10,2),
              'v_nebenkosten' => $this->decimal(10,2),
              'balkon_vorhanden' => $this->tinyint(1),
              'fahrstuhl_vorhanden' => $this->tinyint(1),
              'l_plz_id' => $this->integer(11)->notNull(),
              'stadt' => $this->string(255)->notNull(),
              'user_id' => $this->integer(11)->notNull(),
              'l_art_id' => $this->integer(11)->notNull(),
              'l_heizungsart_id' => $this->integer(11),
              'angelegt_am' => $this->datetime(),
              'aktualisiert_am' => $this->datetime(),
              'angelegt_von' => $this->integer(11),
              'aktualisiert_von' => $this->integer(11),
              'FOREIGN KEY ([[l_art_id]]) REFERENCES l_art ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[aktualisiert_von]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[l_heizungsart_id]]) REFERENCES l_heizungsart ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."immobilien` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'besichtigungstermin', $tables))  {
          $this->createTable('besichtigungstermin', [
              'id' => $this->primaryKey(),
              'uhrzeit' => $this->datetime()->notNull(),
              'Relevanz' => $this->tinyint(1),
              'angelegt_am' => $this->datetime(),
              'aktualisiert_am' => $this->datetime(),
              'angelegt_von' => $this->integer(11),
              'aktualisiert_von' => $this->integer(11),
              'Immobilien_id' => $this->integer(11)->notNull(),
              'FOREIGN KEY ([[aktualisiert_von]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[Immobilien_id]]) REFERENCES immobilien ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."besichtigungstermin` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'bankverbindung', $tables))  {
          $this->createTable('bankverbindung', [
              'id' => $this->primaryKey(),
              'art' => $this->string(32)->notNull(),
              'iban' => $this->string(32)->notNull(),
              'bic' => $this->string(32),
              'angelegt_am' => $this->datetime(),
              'aktualisiert_am' => $this->datetime(),
              'angelegt_von' => $this->integer(11),
              'aktualisiert_von' => $this->integer(11),
              'FOREIGN KEY ([[aktualisiert_von]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."bankverbindung` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'kunde', $tables))  {
          $this->createTable('kunde', [
              'id' => $this->primaryKey(),
              'l_plz_id' => $this->integer(11)->notNull(),
              'stadt' => $this->string(255)->notNull(),
              'strasse' => $this->string(44)->notNull(),
              'solvenz' => $this->tinyint(1),
              'bankverbindung_id' => $this->integer(11)->notNull(),
              'angelegt_am' => $this->datetime(),
              'aktualisiert_am' => $this->datetime(),
              'angelegt_von' => $this->integer(11),
              'aktualisiert_von' => $this->integer(11),
              'FOREIGN KEY ([[bankverbindung_id]]) REFERENCES bankverbindung ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[aktualisiert_von]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."kunde` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'adminbesichtigungkunde', $tables))  {
          $this->createTable('adminbesichtigungkunde', [
              'id' => $this->primaryKey(),
              'besichtigungstermin_id' => $this->integer(11)->notNull(),
              'admin_id' => $this->integer(11)->notNull(),
              'kunde_id' => $this->integer(11)->notNull(),
              'FOREIGN KEY ([[admin_id]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[besichtigungstermin_id]]) REFERENCES besichtigungstermin ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[kunde_id]]) REFERENCES kunde ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."adminbesichtigungkunde` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'e_dateianhang', $tables))  {
          $this->createTable('e_dateianhang', [
              'id' => $this->primaryKey(),
              'immobilien_id' => $this->integer(11),
              'user_id' => $this->integer(11),
              'kunde_id' => $this->integer(11),
              'FOREIGN KEY ([[user_id]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[immobilien_id]]) REFERENCES immobilien ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[kunde_id]]) REFERENCES kunde ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."e_dateianhang` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'l_dateianhang_art', $tables))  {
          $this->createTable('l_dateianhang_art', [
              'id' => $this->primaryKey(),
              'bezeichnung' => $this->string(255)->notNull(),
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."l_dateianhang_art` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'dateianhang', $tables))  {
          $this->createTable('dateianhang', [
              'id' => $this->primaryKey(),
              'bezeichnung' => $this->string(255),
              'dateiname' => $this->string(255)->notNull(),
              'angelegt_am' => $this->datetime(),
              'aktualisert_am' => $this->datetime(),
              'angelegt_von' => $this->integer(11),
              'aktualisiert_von' => $this->integer(11),
              'l_dateianhang_art_id' => $this->integer(11)->notNull(),
              'e_dateianhang_id' => $this->integer(11)->notNull(),
              'FOREIGN KEY ([[aktualisiert_von]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[e_dateianhang_id]]) REFERENCES e_dateianhang ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[l_dateianhang_art_id]]) REFERENCES l_dateianhang_art ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."dateianhang` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'kundeimmobillie', $tables))  {
          $this->createTable('kundeimmobillie', [
              'id' => $this->primaryKey(),
              'kunde_id' => $this->integer(11)->notNull(),
              'immobilien_id' => $this->integer(11)->notNull(),
              'FOREIGN KEY ([[immobilien_id]]) REFERENCES immobilien ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              'FOREIGN KEY ([[kunde_id]]) REFERENCES kunde ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."kundeimmobillie` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'l_plz', $tables))  {
          $this->createTable('l_plz', [
              'id' => $this->primaryKey(),
              'plz' => $this->string(5)->notNull(),
              'ort' => $this->string(255)->notNull(),
              'bl' => $this->string(255)->notNull(),
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."l_plz` already exists!\n";
        }
                 if (!in_array(Yii::$app->db->tablePrefix.'migration', $tables))  {
          $this->createTable('migration', [
              'version' => $this->string(180)->notNull(),
              'apply_time' => $this->integer(11),
              'PRIMARY KEY ([[version]])',
              ], $tableOptions);
                } else {
          echo "\nTable `".Yii::$app->db->tablePrefix."migration` already exists!\n";
        }
                 
    }

    public function down()
    {
        $this->dropTable('migration');
        $this->dropTable('l_plz');
        $this->dropTable('kundeimmobillie');
        $this->dropTable('dateianhang');
        $this->dropTable('l_dateianhang_art');
        $this->dropTable('e_dateianhang');
        $this->dropTable('adminbesichtigungkunde');
        $this->dropTable('kunde');
        $this->dropTable('bankverbindung');
        $this->dropTable('besichtigungstermin');
        $this->dropTable('immobilien');
        $this->dropTable('l_heizungsart');
        $this->dropTable('l_art');
        $this->dropTable('user');
    }
}
