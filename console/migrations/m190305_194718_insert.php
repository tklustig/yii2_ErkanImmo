<?php

class m190305_194718_insert extends \yii\db\Migration {

    public function safeUp() {
        $connection = \Yii::$app->db;
        $connection->createCommand()
                ->batchInsert('l_art', ['id', 'bezeichnung'], [
                    [1, 'Vermietung'],
                    [2, 'Verkauf']
                ])
                ->execute();
        $connection->createCommand()
                ->batchInsert('l_dateianhang_art', ['id', 'bezeichnung'], [
                    [1, 'Bilder zu einem Haus'],
                    [2, 'Bilder zu einem Grundstück'],
                    [3, 'Bilder zu einer Wohnung'],
                    [4, 'Bilder zu einer Villa'],
                    [5, 'Dokumente zu einem Haus'],
                    [6, 'Dokumente zu einem Grundstück'],
                    [7, 'Dokumente zu einer Wohnung'],
                    [8, 'Dokumente zu einer Villa'],
                    [9, 'Bilder und Dokumente zu einem Objekt'],
                    [10, 'Frontendbilder']
                ])
                ->execute();
        $connection->createCommand()
                ->batchInsert('l_geschlecht', ['id', 'typus'], [
                    [1, 'Herr'],
                    [2, 'Frau'],
                    [3, 'Sonstiges'],
                    [4, 'Transgender'],
                    [5, 'Bi'],
                    [6, 'Homo'],
                    [7, 'Kind'],
                    [8, 'Mama'],
                    [9, 'Papa'],
                    [10, 'Opa'],
                    [11, 'Oma']
                ])
                ->execute();
                $connection->createCommand()
                ->batchInsert('l_mwst', ['id', 'satz'], [
                    [1, '9.00'],
                    [2, '16.00'],
                    [3, '19.00'],
                    [4, '7.00'],
                    [5, '32.00'],
                    [6, '51.00'],
                    [7, '54.00']
                ])
                ->execute();
        $connection->createCommand()
                ->batchInsert('l_heizungsart', ['id', 'bezeichnung'], [
                    [1, 'Gas'],
                    [2, 'Strom'],
                    [3, 'Kohle'],
                    [4, 'Sonstiges'],
                ])
                ->execute();
        print_r("++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n");
        print_r("ATTENTION !! ATTENTION !!  ATTENTION!!\n");
        print_r("++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n");
        print_r("U intially have to insert records for l_plz and l_landerkennung manually!\n");
        print_r("Use Batchfile(for Windows) or Shellfile(for Linux) in order to insert these values into database.\n");
        print_r("Without these records, application will crush down.\n");
        print_r("++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n");
    }

}
