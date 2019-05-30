<?php

use yii\db\Schema;
use yii\db\Migration;

class m190530_125712_Mass extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable('adminbesichtigungkunde',[
            'id'=> $this->primaryKey(11),
            'besichtigungstermin_id'=> $this->integer(11)->notNull(),
            'admin_id'=> $this->integer(11)->null()->defaultValue(null),
            'kunde_id'=> $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('admin_id','{{%adminbesichtigungkunde}}',['admin_id'],false);
        $this->createIndex('kunde_id','{{%adminbesichtigungkunde}}',['kunde_id'],false);
        $this->createIndex('besichtigungstermin_id','{{%adminbesichtigungkunde}}',['besichtigungstermin_id'],false);

        $this->createTable('bankverbindung',[
            'id'=> $this->primaryKey(11),
            'laenderkennung'=> $this->string(3)->notNull(),
            'institut'=> $this->string(255)->null()->defaultValue(null),
            'blz'=> $this->integer(11)->notNull(),
            'kontoNr'=> $this->integer(11)->notNull(),
            'iban'=> $this->string(32)->null()->defaultValue(null),
            'bic'=> $this->string(16)->null()->defaultValue(null),
            'kunde_id'=> $this->integer(11)->null()->defaultValue(null),
            'angelegt_am'=> $this->datetime()->null()->defaultValue(null),
            'aktualisiert_am'=> $this->datetime()->null()->defaultValue(null),
            'angelegt_von'=> $this->integer(11)->null()->defaultValue(null),
            'aktualisiert_von'=> $this->integer(11)->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('aktualisiert_von','{{%bankverbindung}}',['aktualisiert_von'],false);
        $this->createIndex('laenderkennung','{{%bankverbindung}}',['laenderkennung'],false);
        $this->createIndex('kundenId','{{%bankverbindung}}',['kunde_id'],false);
        $this->createIndex('angelegt_von','{{%bankverbindung}}',['angelegt_von'],false);

        $this->createTable('besichtigungstermin',[
            'id'=> $this->primaryKey(11),
            'uhrzeit'=> $this->datetime()->notNull(),
            'Relevanz'=> $this->tinyInteger(1)->null()->defaultValue(null),
            'angelegt_am'=> $this->datetime()->null()->defaultValue(null),
            'aktualisiert_am'=> $this->datetime()->null()->defaultValue(null),
            'angelegt_von'=> $this->integer(11)->null()->defaultValue(null),
            'aktualisiert_von'=> $this->integer(11)->null()->defaultValue(null),
            'Immobilien_id'=> $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('aktualisiert_von','{{%besichtigungstermin}}',['aktualisiert_von'],false);
        $this->createIndex('Immobilien_id','{{%besichtigungstermin}}',['Immobilien_id'],false);

        $this->createTable('dateianhang',[
            'id'=> $this->primaryKey(11),
            'bezeichnung'=> $this->string(255)->null()->defaultValue(null),
            'dateiname'=> $this->string(255)->notNull(),
            'angelegt_am'=> $this->datetime()->null()->defaultValue(null),
            'aktualisert_am'=> $this->datetime()->null()->defaultValue(null),
            'angelegt_von'=> $this->integer(11)->null()->defaultValue(null),
            'aktualisiert_von'=> $this->integer(11)->null()->defaultValue(null),
            'l_dateianhang_art_id'=> $this->integer(11)->notNull(),
            'e_dateianhang_id'=> $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('aktualisiert_von','{{%dateianhang}}',['aktualisiert_von'],false);
        $this->createIndex('e_dateianhang_id','{{%dateianhang}}',['e_dateianhang_id'],false);
        $this->createIndex('l_dateianhang_art_id','{{%dateianhang}}',['l_dateianhang_art_id'],false);
        $this->createIndex('angelegt_von','{{%dateianhang}}',['angelegt_von'],false);

        $this->createTable('e_dateianhang',[
            'id'=> $this->primaryKey(11),
            'immobilien_id'=> $this->integer(11)->null()->defaultValue(null),
            'user_id'=> $this->integer(11)->null()->defaultValue(null),
            'kunde_id'=> $this->integer(11)->null()->defaultValue(null),
            'mail_id'=> $this->integer(11)->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('user_id','{{%e_dateianhang}}',['user_id'],false);
        $this->createIndex('kunde_id','{{%e_dateianhang}}',['kunde_id'],false);
        $this->createIndex('immobilien_id','{{%e_dateianhang}}',['immobilien_id'],false);
        $this->createIndex('mail_id','{{%e_dateianhang}}',['mail_id'],false);

        $this->createTable('firma',[
            'id'=> $this->primaryKey(11),
            'firmenname'=> $this->string(64)->notNull(),
            'l_rechtsform_id'=> $this->integer(11)->notNull(),
            'strasse'=> $this->string(64)->notNull(),
            'hausnummer'=> $this->smallInteger(6)->null()->defaultValue(null),
            'l_plz_id'=> $this->integer(11)->notNull(),
            'ort'=> $this->string(64)->notNull(),
            'geschaeftsfuehrer'=> $this->string(32)->null()->defaultValue(null),
            'prokurist'=> $this->string(32)->null()->defaultValue(null),
            'umsatzsteuerID'=> $this->string(64)->null()->defaultValue(null),
            'bankdaten'=> $this->string(256)->null()->defaultValue(null),
            'anzahlMitarbeiter'=> $this->smallInteger(6)->null()->defaultValue(null),
            'angelegt_von'=> $this->integer(11)->null()->defaultValue(null),
            'aktualisiert_von'=> $this->integer(11)->null()->defaultValue(null),
            'angelegt_am'=> $this->datetime()->notNull(),
            'aktualisiert_am'=> $this->datetime()->notNull(),
        ], $tableOptions);

        $this->createIndex('plzId','{{%firma}}',['l_plz_id'],true);
        $this->createIndex('aktualisertVon','{{%firma}}',['aktualisiert_von'],true);
        $this->createIndex('angelegtVon','{{%firma}}',['angelegt_von'],true);
        $this->createIndex('rechtsformId','{{%firma}}',['l_rechtsform_id'],false);

        $this->createTable('immobilien',[
            'id'=> $this->primaryKey(11),
            'bezeichnung'=> $this->text()->null()->defaultValue(null),
            'sonstiges'=> $this->text()->null()->defaultValue(null),
            'strasse'=> $this->string(45)->notNull(),
            'wohnflaeche'=> $this->smallInteger(6)->notNull(),
            'raeume'=> $this->smallInteger(6)->notNull(),
            'geldbetrag'=> $this->decimal(10)->notNull(),
            'k_grundstuecksgroesse'=> $this->smallInteger(6)->null()->defaultValue(null),
            'k_provision'=> $this->decimal(10, 2)->null()->defaultValue(null),
            'v_nebenkosten'=> $this->decimal(10, 2)->null()->defaultValue(null),
            'balkon_vorhanden'=> $this->tinyInteger(1)->null()->defaultValue(null),
            'fahrstuhl_vorhanden'=> $this->tinyInteger(1)->null()->defaultValue(null),
            'l_plz_id'=> $this->integer(11)->notNull(),
            'stadt'=> $this->string(255)->notNull(),
            'user_id'=> $this->integer(11)->notNull(),
            'l_art_id'=> $this->integer(11)->notNull(),
            'l_heizungsart_id'=> $this->integer(11)->null()->defaultValue(null),
            'angelegt_am'=> $this->datetime()->null()->defaultValue(null),
            'aktualisiert_am'=> $this->datetime()->null()->defaultValue(null),
            'angelegt_von'=> $this->integer(11)->null()->defaultValue(null),
            'aktualisiert_von'=> $this->integer(11)->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('aktualisiert_von','{{%immobilien}}',['aktualisiert_von'],false);
        $this->createIndex('angelegt_von','{{%immobilien}}',['angelegt_von'],false);
        $this->createIndex('l_heizungsart_id','{{%immobilien}}',['l_heizungsart_id'],false);
        $this->createIndex('l_art_id','{{%immobilien}}',['l_art_id'],false);

        $this->createTable('kopf',[
            'id'=> $this->primaryKey(11),
            'data'=> $this->text()->notNull(),
            'user_id'=> $this->integer(11)->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('user','{{%kopf}}',['user_id'],false);

        $this->createTable('kunde',[
            'id'=> $this->primaryKey(11),
            'l_plz_id'=> $this->integer(11)->notNull(),
            'geschlecht'=> $this->integer(11)->notNull(),
            'vorname'=> $this->string(255)->notNull(),
            'nachname'=> $this->string(255)->notNull(),
            'stadt'=> $this->string(255)->notNull(),
            'strasse'=> $this->string(44)->notNull(),
            'geburtsdatum'=> $this->date()->null()->defaultValue(null),
            'solvenz'=> $this->tinyInteger(1)->null()->defaultValue(null),
            'telefon'=> $this->string(32)->null()->defaultValue(null),
            'email'=> $this->string(64)->notNull(),
            'bankverbindung_id'=> $this->integer(11)->null()->defaultValue(null),
            'angelegt_am'=> $this->datetime()->null()->defaultValue(null),
            'aktualisiert_am'=> $this->datetime()->null()->defaultValue(null),
            'angelegt_von'=> $this->integer(11)->null()->defaultValue(null),
            'aktualisiert_von'=> $this->integer(11)->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('bankverbindung_id','{{%kunde}}',['bankverbindung_id'],false);
        $this->createIndex('geschlecht','{{%kunde}}',['geschlecht'],false);
        $this->createIndex('plz','{{%kunde}}',['l_plz_id'],false);
        $this->createIndex('aktualisiert_von','{{%kunde}}',['aktualisiert_von'],false);

        $this->createTable('kundeimmobillie',[
            'id'=> $this->primaryKey(11),
            'kunde_id'=> $this->integer(11)->notNull(),
            'immobilien_id'=> $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('immobilien_id','{{%kundeimmobillie}}',['immobilien_id'],false);
        $this->createIndex('kunde_id','{{%kundeimmobillie}}',['kunde_id'],false);

        $this->createTable('l_art',[
            'id'=> $this->primaryKey(11),
            'bezeichnung'=> $this->string(32)->notNull(),
        ], $tableOptions);


        $this->createTable('l_begriffe',[
            'id'=> $this->primaryKey(11),
            'typ'=> $this->string(256)->notNull(),
            'data'=> $this->string(128)->notNull(),
        ], $tableOptions);


        $this->createTable('l_dateianhang_art',[
            'id'=> $this->primaryKey(11),
            'bezeichnung'=> $this->string(255)->notNull(),
        ], $tableOptions);


        $this->createTable('l_geschlecht',[
            'id'=> $this->primaryKey(11),
            'typus'=> $this->string(16)->notNull(),
        ], $tableOptions);


        $this->createTable('l_heizungsart',[
            'id'=> $this->primaryKey(11),
            'bezeichnung'=> $this->string(255)->notNull(),
        ], $tableOptions);


        $this->createTable('l_laenderkennung',[
            'code'=> $this->char(2)->notNull(),
            'en'=> $this->string(100)->notNull()->defaultValue(''),
            'de'=> $this->string(100)->notNull()->defaultValue(''),
            'es'=> $this->string(100)->notNull(),
            'fr'=> $this->string(100)->notNull(),
            'it'=> $this->string(100)->notNull(),
            'ru'=> $this->string(100)->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_on_l_laenderkennung','{{%l_laenderkennung}}',['code']);

        $this->createTable('l_mwst',[
            'id'=> $this->primaryKey(11),
            'satz'=> $this->float()->notNull(),
        ], $tableOptions);


        $this->createTable('l_plz',[
            'id'=> $this->primaryKey(11),
            'plz'=> $this->string(5)->notNull(),
            'ort'=> $this->string(255)->notNull(),
            'bl'=> $this->string(255)->notNull(),
        ], $tableOptions);


        $this->createTable('l_rechnungsart',[
            'id'=> $this->primaryKey(11),
            'data'=> $this->text()->notNull(),
            'art'=> $this->string(32)->notNull(),
        ], $tableOptions);


        $this->createTable('l_rechtsform',[
            'id'=> $this->primaryKey(11),
            'typus'=> $this->string(32)->notNull(),
        ], $tableOptions);


        $this->createTable('l_textbaustein',[
            'id'=> $this->primaryKey(11),
            'beschreibung'=> $this->string(64)->notNull(),
            'data'=> $this->text()->notNull(),
        ], $tableOptions);


        $this->createTable('mail',[
            'id'=> $this->primaryKey(11),
            'id_mailserver'=> $this->integer(11)->notNull(),
            'mail_from'=> $this->string(64)->notNull(),
            'mail_to'=> $this->string(256)->notNull(),
            'mail_cc'=> $this->string(256)->null()->defaultValue(null),
            'mail_bcc'=> $this->string(256)->null()->defaultValue(null),
            'betreff'=> $this->string(64)->notNull(),
            'bodytext'=> $this->text()->notNull(),
            'textbaustein_id'=> $this->integer(11)->null()->defaultValue(null),
            'vorlage'=> $this->text()->null()->defaultValue(null),
            'angelegt_am'=> $this->datetime()->null()->defaultValue(null),
            'angelegt_von'=> $this->integer(11)->null()->defaultValue(null),
            'aktualisiert_am'=> $this->datetime()->null()->defaultValue(null),
            'aktualisiert_von'=> $this->integer(11)->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('mailServer','{{%mail}}',['id_mailserver'],false);
        $this->createIndex('angelegtVon','{{%mail}}',['angelegt_von'],false);
        $this->createIndex('aktualisiertVon','{{%mail}}',['id'],false);
        $this->createIndex('mail_ibfk_3','{{%mail}}',['aktualisiert_von'],false);
        $this->createIndex('textbausteinId','{{%mail}}',['textbaustein_id'],false);

        $this->createTable('mailserver',[
            'id'=> $this->primaryKey(11),
            'serverURL'=> $this->string(15)->null()->defaultValue(null),
            'serverHost'=> $this->string(64)->null()->defaultValue(null),
            'username'=> $this->string(32)->notNull(),
            'password'=> $this->string(32)->notNull(),
            'port'=> $this->smallInteger(6)->notNull(),
            'useEncryption'=> $this->tinyInteger(1)->notNull(),
            'encryption'=> $this->string(6)->notNull(),
            'angelegt_von'=> $this->integer(11)->null()->defaultValue(null),
            'aktualisiert_von'=> $this->integer(11)->null()->defaultValue(null),
            'angelegt_am'=> $this->timestamp()->null()->defaultValue(null),
            'aktualisiert_am'=> $this->datetime()->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('angelegtVon','{{%mailserver}}',['angelegt_von'],false);
        $this->createIndex('aktualisiertVon','{{%mailserver}}',['aktualisiert_von'],false);

        $this->createTable('migration',[
            'version'=> $this->string(180)->notNull(),
            'apply_time'=> $this->integer(11)->null()->defaultValue(null),
        ], $tableOptions);

        $this->addPrimaryKey('pk_on_migration','{{%migration}}',['version']);

        $this->createTable('rechnung',[
            'id'=> $this->primaryKey(11),
            'datumerstellung'=> $this->date()->notNull(),
            'datumfaellig'=> $this->date()->notNull(),
            'beschreibung'=> $this->text()->null()->defaultValue(null),
            'vorlage'=> $this->text()->null()->defaultValue(null),
            'geldbetrag'=> $this->decimal(10)->notNull(),
            'mwst_id'=> $this->integer(11)->null()->defaultValue(null),
            'kunde_id'=> $this->integer(11)->notNull(),
            'makler_id'=> $this->integer(11)->null()->defaultValue(null),
            'kopf_id'=> $this->integer(11)->null()->defaultValue(null),
            'rechungsart_id'=> $this->integer(11)->null()->defaultValue(null),
            'rechnungPlain'=> $this->text()->notNull(),
            'aktualisiert_von'=> $this->integer(11)->null()->defaultValue(null),
            'angelegt_von'=> $this->integer(11)->null()->defaultValue(null),
            'aktualisiert_am'=> $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
            'angelegt_am'=> $this->timestamp()->notNull()->defaultValue('0000-00-00 00:00:00'),
        ], $tableOptions);

        $this->createIndex('mwst','{{%rechnung}}',['mwst_id'],false);
        $this->createIndex('kopf','{{%rechnung}}',['kopf_id'],false);
        $this->createIndex('makler','{{%rechnung}}',['makler_id'],false);
        $this->createIndex('kunde','{{%rechnung}}',['kunde_id'],false);
        $this->createIndex('aktualisiertVon','{{%rechnung}}',['aktualisiert_von'],false);
        $this->createIndex('angelegtVon','{{%rechnung}}',['angelegt_von'],false);
        $this->createIndex('artId','{{%rechnung}}',['rechungsart_id'],false);

        $this->createTable('user',[
            'id'=> $this->primaryKey(11),
            'username'=> $this->string(255)->notNull(),
            'auth_key'=> $this->string(32)->notNull(),
            'password_hash'=> $this->string(255)->notNull(),
            'password_reset_token'=> $this->string(255)->null()->defaultValue(null),
            'email'=> $this->string(32)->notNull(),
            'telefon'=> $this->string(32)->notNull(),
            'status'=> $this->smallInteger(6)->notNull()->defaultValue(10),
            'created_at'=> $this->integer(11)->notNull(),
            'updated_at'=> $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_adminbesichtigungkunde_admin_id',
            '{{%adminbesichtigungkunde}}', 'admin_id',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_adminbesichtigungkunde_besichtigungstermin_id',
            '{{%adminbesichtigungkunde}}', 'besichtigungstermin_id',
            '{{%besichtigungstermin}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_adminbesichtigungkunde_kunde_id',
            '{{%adminbesichtigungkunde}}', 'kunde_id',
            '{{%kunde}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_bankverbindung_aktualisiert_von',
            '{{%bankverbindung}}', 'aktualisiert_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_bankverbindung_angelegt_von',
            '{{%bankverbindung}}', 'angelegt_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_bankverbindung_kunde_id',
            '{{%bankverbindung}}', 'kunde_id',
            '{{%kunde}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_besichtigungstermin_aktualisiert_von',
            '{{%besichtigungstermin}}', 'aktualisiert_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_besichtigungstermin_Immobilien_id',
            '{{%besichtigungstermin}}', 'Immobilien_id',
            '{{%immobilien}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_dateianhang_aktualisiert_von',
            '{{%dateianhang}}', 'aktualisiert_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_dateianhang_e_dateianhang_id',
            '{{%dateianhang}}', 'e_dateianhang_id',
            '{{%e_dateianhang}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_dateianhang_l_dateianhang_art_id',
            '{{%dateianhang}}', 'l_dateianhang_art_id',
            '{{%l_dateianhang_art}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_dateianhang_angelegt_von',
            '{{%dateianhang}}', 'angelegt_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_e_dateianhang_user_id',
            '{{%e_dateianhang}}', 'user_id',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_e_dateianhang_immobilien_id',
            '{{%e_dateianhang}}', 'immobilien_id',
            '{{%immobilien}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_e_dateianhang_kunde_id',
            '{{%e_dateianhang}}', 'kunde_id',
            '{{%kunde}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_e_dateianhang_mail_id',
            '{{%e_dateianhang}}', 'mail_id',
            '{{%mail}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_firma_angelegt_von',
            '{{%firma}}', 'angelegt_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_firma_aktualisiert_von',
            '{{%firma}}', 'aktualisiert_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_firma_l_plz_id',
            '{{%firma}}', 'l_plz_id',
            '{{%l_plz}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_firma_l_rechtsform_id',
            '{{%firma}}', 'l_rechtsform_id',
            '{{%l_rechtsform}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_immobilien_l_art_id',
            '{{%immobilien}}', 'l_art_id',
            '{{%l_art}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_immobilien_aktualisiert_von',
            '{{%immobilien}}', 'aktualisiert_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_immobilien_l_heizungsart_id',
            '{{%immobilien}}', 'l_heizungsart_id',
            '{{%l_heizungsart}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_immobilien_angelegt_von',
            '{{%immobilien}}', 'angelegt_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_kopf_user_id',
            '{{%kopf}}', 'user_id',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_kunde_bankverbindung_id',
            '{{%kunde}}', 'bankverbindung_id',
            '{{%bankverbindung}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_kunde_aktualisiert_von',
            '{{%kunde}}', 'aktualisiert_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_kunde_l_plz_id',
            '{{%kunde}}', 'l_plz_id',
            '{{%l_plz}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_kunde_geschlecht',
            '{{%kunde}}', 'geschlecht',
            '{{%l_geschlecht}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_kundeimmobillie_immobilien_id',
            '{{%kundeimmobillie}}', 'immobilien_id',
            '{{%immobilien}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_kundeimmobillie_kunde_id',
            '{{%kundeimmobillie}}', 'kunde_id',
            '{{%kunde}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_mail_id_mailserver',
            '{{%mail}}', 'id_mailserver',
            '{{%mailserver}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_mail_angelegt_von',
            '{{%mail}}', 'angelegt_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_mail_aktualisiert_von',
            '{{%mail}}', 'aktualisiert_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_mail_textbaustein_id',
            '{{%mail}}', 'textbaustein_id',
            '{{%l_textbaustein}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_mailserver_angelegt_von',
            '{{%mailserver}}', 'angelegt_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_mailserver_aktualisiert_von',
            '{{%mailserver}}', 'aktualisiert_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_rechnung_kopf_id',
            '{{%rechnung}}', 'kopf_id',
            '{{%kopf}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_rechnung_kunde_id',
            '{{%rechnung}}', 'kunde_id',
            '{{%kunde}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_rechnung_makler_id',
            '{{%rechnung}}', 'makler_id',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_rechnung_mwst_id',
            '{{%rechnung}}', 'mwst_id',
            '{{%l_mwst}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_rechnung_angelegt_von',
            '{{%rechnung}}', 'angelegt_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_rechnung_aktualisiert_von',
            '{{%rechnung}}', 'aktualisiert_von',
            '{{%user}}', 'id',
            'RESTRICT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_rechnung_rechungsart_id',
            '{{%rechnung}}', 'rechungsart_id',
            '{{%l_rechnungsart}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    public function safeDown()
    {
            $this->dropForeignKey('fk_adminbesichtigungkunde_admin_id', '{{%adminbesichtigungkunde}}');
            $this->dropForeignKey('fk_adminbesichtigungkunde_besichtigungstermin_id', '{{%adminbesichtigungkunde}}');
            $this->dropForeignKey('fk_adminbesichtigungkunde_kunde_id', '{{%adminbesichtigungkunde}}');
            $this->dropForeignKey('fk_bankverbindung_aktualisiert_von', '{{%bankverbindung}}');
            $this->dropForeignKey('fk_bankverbindung_angelegt_von', '{{%bankverbindung}}');
            $this->dropForeignKey('fk_bankverbindung_kunde_id', '{{%bankverbindung}}');
            $this->dropForeignKey('fk_besichtigungstermin_aktualisiert_von', '{{%besichtigungstermin}}');
            $this->dropForeignKey('fk_besichtigungstermin_Immobilien_id', '{{%besichtigungstermin}}');
            $this->dropForeignKey('fk_dateianhang_aktualisiert_von', '{{%dateianhang}}');
            $this->dropForeignKey('fk_dateianhang_e_dateianhang_id', '{{%dateianhang}}');
            $this->dropForeignKey('fk_dateianhang_l_dateianhang_art_id', '{{%dateianhang}}');
            $this->dropForeignKey('fk_dateianhang_angelegt_von', '{{%dateianhang}}');
            $this->dropForeignKey('fk_e_dateianhang_user_id', '{{%e_dateianhang}}');
            $this->dropForeignKey('fk_e_dateianhang_immobilien_id', '{{%e_dateianhang}}');
            $this->dropForeignKey('fk_e_dateianhang_kunde_id', '{{%e_dateianhang}}');
            $this->dropForeignKey('fk_e_dateianhang_mail_id', '{{%e_dateianhang}}');
            $this->dropForeignKey('fk_firma_angelegt_von', '{{%firma}}');
            $this->dropForeignKey('fk_firma_aktualisiert_von', '{{%firma}}');
            $this->dropForeignKey('fk_firma_l_plz_id', '{{%firma}}');
            $this->dropForeignKey('fk_firma_l_rechtsform_id', '{{%firma}}');
            $this->dropForeignKey('fk_immobilien_l_art_id', '{{%immobilien}}');
            $this->dropForeignKey('fk_immobilien_aktualisiert_von', '{{%immobilien}}');
            $this->dropForeignKey('fk_immobilien_l_heizungsart_id', '{{%immobilien}}');
            $this->dropForeignKey('fk_immobilien_angelegt_von', '{{%immobilien}}');
            $this->dropForeignKey('fk_kopf_user_id', '{{%kopf}}');
            $this->dropForeignKey('fk_kunde_bankverbindung_id', '{{%kunde}}');
            $this->dropForeignKey('fk_kunde_aktualisiert_von', '{{%kunde}}');
            $this->dropForeignKey('fk_kunde_l_plz_id', '{{%kunde}}');
            $this->dropForeignKey('fk_kunde_geschlecht', '{{%kunde}}');
            $this->dropForeignKey('fk_kundeimmobillie_immobilien_id', '{{%kundeimmobillie}}');
            $this->dropForeignKey('fk_kundeimmobillie_kunde_id', '{{%kundeimmobillie}}');
            $this->dropForeignKey('fk_mail_id_mailserver', '{{%mail}}');
            $this->dropForeignKey('fk_mail_angelegt_von', '{{%mail}}');
            $this->dropForeignKey('fk_mail_aktualisiert_von', '{{%mail}}');
            $this->dropForeignKey('fk_mail_textbaustein_id', '{{%mail}}');
            $this->dropForeignKey('fk_mailserver_angelegt_von', '{{%mailserver}}');
            $this->dropForeignKey('fk_mailserver_aktualisiert_von', '{{%mailserver}}');
            $this->dropForeignKey('fk_rechnung_kopf_id', '{{%rechnung}}');
            $this->dropForeignKey('fk_rechnung_kunde_id', '{{%rechnung}}');
            $this->dropForeignKey('fk_rechnung_makler_id', '{{%rechnung}}');
            $this->dropForeignKey('fk_rechnung_mwst_id', '{{%rechnung}}');
            $this->dropForeignKey('fk_rechnung_angelegt_von', '{{%rechnung}}');
            $this->dropForeignKey('fk_rechnung_aktualisiert_von', '{{%rechnung}}');
            $this->dropForeignKey('fk_rechnung_rechungsart_id', '{{%rechnung}}');
            $this->dropTable('adminbesichtigungkunde');
            $this->dropTable('bankverbindung');
            $this->dropTable('besichtigungstermin');
            $this->dropTable('dateianhang');
            $this->dropTable('e_dateianhang');
            $this->dropTable('firma');
            $this->dropTable('immobilien');
            $this->dropTable('kopf');
            $this->dropTable('kunde');
            $this->dropTable('kundeimmobillie');
            $this->dropTable('l_art');
            $this->dropTable('l_begriffe');
            $this->dropTable('l_dateianhang_art');
            $this->dropTable('l_geschlecht');
            $this->dropTable('l_heizungsart');
            $this->dropPrimaryKey('pk_on_l_laenderkennung','{{%l_laenderkennung}}');
            $this->dropTable('l_laenderkennung');
            $this->dropTable('l_mwst');
            $this->dropTable('l_plz');
            $this->dropTable('l_rechnungsart');
            $this->dropTable('l_rechtsform');
            $this->dropTable('l_textbaustein');
            $this->dropTable('mail');
            $this->dropTable('mailserver');
            $this->dropPrimaryKey('pk_on_migration','{{%migration}}');
            $this->dropTable('migration');
            $this->dropTable('rechnung');
            $this->dropTable('user');
    }
}
