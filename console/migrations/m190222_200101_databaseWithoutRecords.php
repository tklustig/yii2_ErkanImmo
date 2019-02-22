<?php

use yii\db\Schema;

class m190222_200101_databaseWithoutRecords extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
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
                $this->createTable('l_art', [
            'id' => $this->primaryKey(),
            'bezeichnung' => $this->string(32)->notNull(),
            ], $tableOptions);
                $this->createTable('l_heizungsart', [
            'id' => $this->primaryKey(),
            'bezeichnung' => $this->string(255)->notNull(),
            ], $tableOptions);
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
                $this->createTable('bankverbindung', [
            'id' => $this->primaryKey(),
            'laenderkennung' => $this->string(3)->notNull(),
            'institut' => $this->string(255),
            'blz' => $this->integer(11)->notNull(),
            'kontoNr' => $this->integer(11)->notNull(),
            'iban' => $this->string(32),
            'bic' => $this->string(8),
            'angelegt_am' => $this->datetime(),
            'aktualisiert_am' => $this->datetime(),
            'angelegt_von' => $this->integer(11),
            'aktualisiert_von' => $this->integer(11),
            'FOREIGN KEY ([[aktualisiert_von]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
                $this->createTable('kunde', [
            'id' => $this->primaryKey(),
            'l_plz_id' => $this->integer(11)->notNull(),
            'geschlecht' => $this->string(64)->notNull(),
            'vorname' => $this->string(255)->notNull(),
            'nachname' => $this->string(255)->notNull(),
            'stadt' => $this->string(255)->notNull(),
            'strasse' => $this->string(44)->notNull(),
            'geburtsdatum' => $this->date(),
            'solvenz' => $this->tinyint(1),
            'bankverbindung_id' => $this->integer(11),
            'angelegt_am' => $this->datetime(),
            'aktualisiert_am' => $this->datetime(),
            'angelegt_von' => $this->integer(11),
            'aktualisiert_von' => $this->integer(11),
            'FOREIGN KEY ([[bankverbindung_id]]) REFERENCES bankverbindung ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[aktualisiert_von]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
                $this->createTable('adminbesichtigungkunde', [
            'id' => $this->primaryKey(),
            'besichtigungstermin_id' => $this->integer(11)->notNull(),
            'admin_id' => $this->integer(11)->notNull(),
            'kunde_id' => $this->integer(11)->notNull(),
            'FOREIGN KEY ([[admin_id]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[besichtigungstermin_id]]) REFERENCES besichtigungstermin ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[kunde_id]]) REFERENCES kunde ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
                $this->createTable('e_dateianhang', [
            'id' => $this->primaryKey(),
            'immobilien_id' => $this->integer(11),
            'user_id' => $this->integer(11),
            'kunde_id' => $this->integer(11),
            'FOREIGN KEY ([[user_id]]) REFERENCES user ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[immobilien_id]]) REFERENCES immobilien ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[kunde_id]]) REFERENCES kunde ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
                $this->createTable('l_dateianhang_art', [
            'id' => $this->primaryKey(),
            'bezeichnung' => $this->string(255)->notNull(),
            ], $tableOptions);
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
                $this->createTable('kundeimmobillie', [
            'id' => $this->primaryKey(),
            'kunde_id' => $this->integer(11)->notNull(),
            'immobilien_id' => $this->integer(11)->notNull(),
            'FOREIGN KEY ([[immobilien_id]]) REFERENCES immobilien ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[kunde_id]]) REFERENCES kunde ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
                $this->createTable('l_laenderkennung', [
            'code' => $this->char(2)->notNull(),
            'en' => $this->string(100)->notNull()->defaultValue(''),
            'de' => $this->string(100)->notNull()->defaultValue(''),
            'es' => $this->string(100)->notNull(),
            'fr' => $this->string(100)->notNull(),
            'it' => $this->string(100)->notNull(),
            'ru' => $this->string(100)->notNull(),
            'PRIMARY KEY ([[code]])',
            ], $tableOptions);
                $this->createTable('l_plz', [
            'id' => $this->primaryKey(),
            'plz' => $this->string(5)->notNull(),
            'ort' => $this->string(255)->notNull(),
            'bl' => $this->string(255)->notNull(),
            ], $tableOptions);
                $this->createTable('migration', [
            'version' => $this->string(180)->notNull(),
            'apply_time' => $this->integer(11),
            'PRIMARY KEY ([[version]])',
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('migration');
        $this->dropTable('l_plz');
        $this->dropTable('l_laenderkennung');
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
