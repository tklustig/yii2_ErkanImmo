<?php

class m180705_140101_insert extends \yii\db\Migration {

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
                    [9, 'Bilder und Dokumente zu einem Objekt']
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
        $connection->createCommand()
                ->batchInsert('user', [`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `telefon`, `status`, `created_at`, `updated_at`], [
                    [1, 8, 'admin', '5ZYKHPy4hADG3yzHrEelfO0JLG9j5F5n', '$2y$13$u5SKbpjJ3v4ZP6JQ3OWfluprxY3WmFbSvSWZk5K25RKMqT13IxE6e', NULL, 'admin@gmx.net', '0176/2237680', 10, 1528207437, 1528207437],
                ])
                ->execute();
        $connection->createCommand()
                ->batchInsert('immobilien', [`id`, `bezeichnung`, `sonstiges`, `strasse`, `wohnflaeche`, `raeume`, `geldbetrag`, `k_grundstuecksgroesse`, `k_provision`, `v_nebenkosten`, `balkon_vorhanden`, `fahrstuhl_vorhanden`, `l_plz_id`, `stadt`, `user_id`, `l_art_id`, `l_heizungsart_id`, `angelegt_am`, `aktualisiert_am`, `angelegt_von`, `aktualisiert_von`], [
                    [1, '<p><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><strong>Objektbeschreibung:</strong></span></span></u></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Diese idyllisch gelegene Objekt<br />\r\nbefindet sich in bevorzugter,<br />\r\nruhiger Lage und bietet<br />\r\nmit seinen 6-7 Wohnungen<br />\r\njede Menge Platz. Somit<br />\r\neignet es sich perfekt<br />\r\nf&uuml;r die </span></span></span><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">gro&szlig;e Familie<br />\r\noder auch f&uuml;r die Untervermietung.</span></span></span></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Die Gesamtfl&auml;che betr&auml;gt ca. 600 qm.</span></span></span></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Das riesige idyllische Gartengrundst&uuml;ck<br />\r\nvon ca. 7.500 qm verf&uuml;gt &uuml;ber einen<br />\r\nBrunnen,</span></span></span><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">mehrere Teiche, einen gro&szlig;z&uuml;gigen Baumbestand,<br />\r\nB&auml;che, verschieden angelegte G&auml;rten<br />\r\nund eignet sich aufgrund der<br />\r\nGr&ouml;&szlig;e und Beschaffenheit ideal<br />\r\nf&uuml;r Tierhaltung.<br />\r\nDas Grundst&uuml;ck beinhaltet ca. 6500<br />\r\nqm Gartenland und ca. 1000 qm<br />\r\nBauland. Es befinden sich auch noch freie<br />\r\nFl&auml;chen die gegebenenfalls noch bebaut werden k&ouml;nnen.<br />\r\nAuf dem Grundst&uuml;ck befinden sich<br />\r\nau&szlig;erdem mehrere Unterstellm&ouml;glichkeiten f&uuml;r<br />\r\nTierhaltung und Fahrzeuge.<br />\r\nVor dem Haus befindet sich eine<br />\r\ngepflasterte Hoffl&auml;che. Das Grundst&uuml;ck ist<br />\r\n&uuml;ber eine Einfahrt befahrbar.<br />\r\nBei dem Haus handelte es sich<br />\r\nurspr&uuml;nglich um eine im Jahre 1890<br />\r\nerrichtetes Fachwerkhaus. Dieses wurde nach<br />\r\nund nach renoviert und erweitert.<br />\r\nBei den Fenstern handelt es sich<br />\r\num doppelt verglaste Holz- und Kunststofffenster.<br />\r\nEin Schornstein in dem bereits<br />\r\nbeide Z&uuml;ge verrohrt sind bietet<br />\r\ndie M&ouml;glichkeit auf eine andere Heizungsart umzur&uuml;sten.<br />\r\nBei der Zufahrtstr. Handelt es sich<br />\r\num eine Privatstr. Welche zu 1/3 zum Grundst&uuml;ck geh&ouml;rt.</span></span></span></p>\r\n', '<p style=\"margin-left:0px; margin-right:0px; text-align:left\"><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><strong>Energieausweis:</strong></span></span></u></p>\r\n\r\n<p style=\"margin-left:0px; margin-right:0px; text-align:left\"><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\">Art: Bedarfsausweis<br />\r\nG&uuml;ltig bis: 20.05.2028<br />\r\nEndenergiebedarf: 270.70 kWh/(m&sup2;*a)<br />\r\nBaujahr lt. Energieausweis: 1979<br />\r\nWesentlicher Energietr&auml;ger: &Ouml;l</span></span></p>\r\n\r\n<p><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\"><strong>Lage:</strong></span></span></span></u></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Der Ortsteil Fohlenplacken liegt als kleines<br />\r\nDorf rund 1,5 km (Luftlinie) nordwestlich<br />\r\nvom Ortskern Neuhaus entfernt.<br />\r\nDas Haus befindet sich in unmittelbarer<br />\r\nN&auml;he vom Flusslauf der Holzminde.<br />\r\nDas Objekt zeichnet sich durch<br />\r\nseine N&auml;he zur der sch&ouml;nen Natur aus.<br />\r\nAngrenzende Naturschutzgebiete machen das Wohnen<br />\r\nund Leben zu einem echten Erlebnis.<br />\r\nDie benachbarte Stadt Holzminden verf&uuml;gt<br />\r\n&uuml;ber eine gute Infrastruktur.<br />\r\nSchulen, Kinderg&auml;rten, Einkaufsm&ouml;glichkeiten, Vereine<br />\r\nund &Auml;rzte befinden sich in unmittelbarer N&auml;he.<br />\r\nDie Bundesstra&szlig;e ist mit dem Auto gut zu erreichen.</span></span></span></p>\r\n', 'Lilienweg 12', 230, 11, 999000, 420, 3.75, NULL, 1, 1, 9733, 'Kreuztal', 8, 2, 1, '2018-06-10 20:41:35', '2018-06-30 09:53:58', 8, 8],
                    [2, '<p><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><strong>Objektbeschreibung:</strong></span></span></u></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Willkommen im Architekturhimmel<br> f&uuml;r allerh&ouml;chste Anspr&uuml;che...<br />\r\nDiese Wohnresidenz bietet traumhaften<br />\r\nWohnkomfort auf 255 qm mit<br />\r\nherrlichem Blick ins Naturschutzgebiet.<br />\r\nH&ouml;chste Qualit&auml;t, edelste Materalien und<br />\r\nviel Liebe zum Detail schaffen meisterhaft<br />\r\ninszenierte R&auml;ume mit Deckenh&ouml;hen von 3,00 m.<br />\r\nDie gro&szlig;z&uuml;gigen Loggien sind<br />\r\nso angeordnet, dass sie ein Maximum<br />\r\nan Privatsph&auml;re und an Ausblick bieten....<br />\r\n3 PKW-Stellpl&auml;tze in der Tiefgarage<br />\r\nk&ouml;nnen f&uuml;r je 80 &euro; / Monat dazugemietet werden.<br />\r\nF&uuml;hlen Sie sich angesprochen?</span></span></span></p>\r\n', '<p style=\"margin-left:0px; margin-right:0px; text-align:left\"><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><strong>Energieausweis</strong></span></span></u><br />\r\n<span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\">Art: Bedarfsausweis<br />\r\nG&uuml;ltig bis: 15.10.2025<br />\r\nEndenergiebedarf: 39.00 kWh/(m&sup2;*a)<br />\r\nWesentlicher Energietr&auml;ger: Gas<br />\r\nKlasse: A</span></span></p>\r\n\r\n<p><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\"><strong>Lage:</strong></span></span></span></u></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">HIMMLISCHE RUHE UND VIEL GR&Uuml;N....<br />\r\nIsernhagen-S&uuml;d z&auml;hlt zu den besten und begehrtesten Wohnlagen Hannovers.&nbsp;<br />\r\nDer besondere Vorzug ist die Symbiose aus<br />\r\nhervorragender st&auml;dtischer Infrastruktur und l&auml;ndlicher Lage.<br />\r\nEinkaufsm&ouml;glichkeiten, &Auml;rzte und Kindergarten sowie<br />\r\ndie Stadtbahn sind fu&szlig;l&auml;ufig zu erreichen.</span></span></span></p>\r\n', 'Krumme Lanke 15', 45, 2, 440, NULL, NULL, 120.75, 1, 0, 11879, 'Freiburg im Breisgau', 8, 1, 2, '2018-06-10 21:32:05', '2018-06-30 10:07:16', NULL, 8],
                    [3, '<ul>\r\n<li>Asylanten-oder Gastarbeiterunterkunft</li>\r\n	<li>WC und Dusche seperat (auf der Etage)</li>\r\n	<li>Gemeinschaftsk&uuml;che</li>\r\n</ul>\r\n', '<ul>\r\n	<li>Schimmelbefall im Bad und in der K&uuml;che</li>\r\n</ul>\r\n', 'Prinz-Eugen-Str. 12', 28, 1, 220, NULL, NULL, 80.00, 0, 0, 5853, 'Misburg-Nord', 8, 1, 3, '2018-06-10 21:34:31', '2018-06-10 21:34:31', NULL, NULL],
                    [4, '<p><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><strong>Objektbeschreibung:</strong></span></span></u></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Gro&szlig;z&uuml;giges Reihenhaus mit offener Architektur in Misburg!<br />\r\nSie suchen Ihr neues Zuhause in toller Lage?<br />\r\nDann haben wir hier genau das Richtige f&uuml;r Sie.<br />\r\nDas Objekt wurde 1983 erbaut, jedoch laufend modernisiert.<br />\r\nBesonders interessant ist bei der Lage<br> die ausgesprochen niedrige Erbpacht von<br> &euro; 1.680 j&auml;hrlich. Der Erbbaurechtsvertrag<br> hat noch eine Laufzeit bis 2070.<br />\r\nTreten Sie ein und sp&uuml;ren Sie Schritt f&uuml;r<br> Schritt, warum das Wohnen hier so angenehm ist.</span></span></span></p>\r\n', '<p style=\"margin-left:0px; margin-right:0px; text-align:left\"><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><strong>Energieausweis</strong></span></span></u><br />\r\n<span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\">Art: Verbrauchsausweis<br />\r\nG&uuml;ltig bis: 19.03.2027<br />\r\nEndenergieverbrauch: 126.25 kWh/(m&sup2;*a)<br />\r\nBaujahr lt. Energieausweis: 2007<br />\r\nWesentlicher Energietr&auml;ger: Gas</span></span></p>\r\n\r\n<p><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\"><strong>Lage:</strong></span></span></span></u></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Misburg-Nord liegt an der &ouml;stlichen Stadtgrenze von Hannover.&nbsp;<br />\r\nEs ist der gr&ouml;&szlig;te Stadtteil<br> mit Bezirk Misburg-Anderten, verf&uuml;gt &uuml;ber<br> eine gute Infrastruktur und wirkt<br> fast schon wie eine unabh&auml;ngige Kleinstadt.&nbsp;<br />\r\n&Uuml;ber die n&ouml;rdlich des Wohngebietes verlaufende A2<br> und den Messeschnellweg kommt man mit<br> dem PKW schnell in das<br> Umland oder das Stadtzentrum von Hannover,<br> &Ouml;ffentliche Verkehrsmittel stehen ebenfalls zur Verf&uuml;gung.&nbsp;<br />\r\nDer riesige Misburger Wald und der Blaue See<br> sorgen f&uuml;r einen hohen Naherholungswert.</span></span></span></p>\r\n', 'Friedrich-Schiller-Weg 56', 180, 6, 86500, 230, 1.25, NULL, 1, 0, 8035, 'Stolberg (Rheinland)', 8, 2, 3, '2018-06-10 21:44:24', '2018-06-30 09:46:20', NULL, 8],
                    [5, '<p><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><u><strong>Objektbeschreibung:</strong></u></span></span></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Sie suchen das Besondere?<br />\r\nEine repr&auml;sentative Immobilie in einer<br> der begehrtesten Wohngegenden Hannovers?&nbsp;<br />\r\nDieses Objekt hebt sich in allen Standards<br> ab und bietet Ihnen ein ganz pers&ouml;nliches Wohnerlebnis.<br />\r\nIhre Immobilie befindet sich im 2. Obergeschoss<br> bzw. Staffelgeschoss (Penthaus) eines exklusiven Neubauprojektes mit<br> gro&szlig;z&uuml;giger Dachterrasse in S&uuml;d/Ost Ausrichtung in gehobener Lage!<br />\r\nDie Wohnung verf&uuml;gt &uuml;ber einen PKW-Stellplatz<br> in der Tiefgarage, der mit angemietet werden kann.<br />\r\nTreten Sie ein und sp&uuml;ren Sie Schritt<br> f&uuml;r Schritt, warum das Wohnen hier so angenehm ist:</span></span></span></p>\r\n', '<p style=\"margin-left:0px; margin-right:0px; text-align:left\"><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><u><strong>Energieausweis</strong></u></span></span><br />\r\n<span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\">Art: Bedarfsausweis<br />\r\nG&uuml;ltig bis: 15.10.2025<br />\r\nEndenergiebedarf: 41.00 kWh/(m&sup2;*a)<br />\r\nBaujahr lt. Energieausweis: 2014<br />\r\nWesentlicher Energietr&auml;ger: Gas</span></span></p>\r\n\r\n<p><strong><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Lage:</span></span></span></u></strong></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Isernhagen-S&uuml;d z&auml;hlt zu den besten Adressen Hannovers.&nbsp;<br />\r\nDer besondere Vorzug von Isernhagen-S&uuml;d ist<br> die Symbiose aus hervorragender Infrastruktur und l&auml;ndlicher Lage.&nbsp;<br />\r\nEinkaufsm&ouml;glichkeiten, &Auml;rzte und Kindergarten<br> sowie die Stadtbahn sind fu&szlig;l&auml;ufig erreichbar.&nbsp;<br />\r\nDie A 2 und den Flughafen Langenhagen<br> erreichen Sie in wenigen Autominuten.&nbsp;<br />\r\nDas Angebot an Sport- und Freizeitclubs<br> und guten Restaurants in der malerischen<br> Umgebung der umliegenden Bauerschaften, l&auml;sst<br> keine W&uuml;nsche offen.</span></span></span></p>\r\n', 'Lerchengasse 23-25', 90, 4, 650, NULL, NULL, 155.75, 0, 1, 6563, 'Walkenried', 8, 1, 1, '2018-06-11 17:38:41', '2018-06-30 09:51:40', 8, 8],
                    [6, '<p><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><strong>Objektbeschreibung:</strong></span></span></u></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">Willkommen im Architekturhimmel f&uuml;r allerh&ouml;chste Anspr&uuml;che...<br />\r\nDiese Wohnresidenz bietet traumhaften Wohnkomfort<br />\r\nauf 255 qm mit herrlichem Blick ins Naturschutzgebiet.<br />\r\nH&ouml;chste Qualit&auml;t, edelste Materalien<br />\r\nund viel Liebe zum Detail schaffen meisterhaft<br />\r\ninszenierte R&auml;ume mit Deckenh&ouml;hen von 3,00 m.<br />\r\nDie gro&szlig;z&uuml;gigen Loggien sind so angeordnet,<br />\r\ndass sie ein Maximum an<br />\r\nPrivatsph&auml;re und an Ausblick bieten....</span></span></span></p>\r\n', '<p style=\"margin-left:0px; margin-right:0px; text-align:left\"><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><strong>Energieausweis</strong></span></span></u><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\">:<br />\r\nArt: Bedarfsausweis<br />\r\nG&uuml;ltig bis: 15.10.2025<br />\r\nEndenergiebedarf: 39.00 kWh/(m&sup2;*a)<br />\r\nWesentlicher Energietr&auml;ger: Gas<br />\r\nKlasse: A</span></span></p>\r\n\r\n<p><u><span style=\"font-size:20px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\"><strong>Lage:</strong></span></span></span></u></p>\r\n\r\n<p><span style=\"font-size:16px\"><span style=\"font-family:Times New Roman,Times,serif\"><span style=\"background-color:#ffffff; color:#333333\">HIMMLISCHE RUHE UND VIEL GR&Uuml;N....<br />\r\nIsernhagen-S&uuml;d z&auml;hlt zu den besten<br />\r\nund begehrtesten Wohnlagen Hannovers.&nbsp;<br />\r\nDer besondere Vorzug ist die Symbiose<br />\r\naus hervorragender Infrastruktur und l&auml;ndlicher Lage.<br />\r\nEinkaufsm&ouml;glichkeiten, &Auml;rzte und Kindergarten sowie<br />\r\ndie Stadtbahn sind fu&szlig;l&auml;ufig zu erreichen.</span></span></span></p>\r\n', 'Lerchengasse 46', 255, 4, 660, NULL, NULL, 215.50, 1, 1, 5853, 'Groß Buchholz', 8, 1, 1, '2018-06-13 18:05:04', '2018-06-30 09:53:32', 8, 8]
                ])
                ->execute();
        $connection->createCommand()
                ->batchInsert('e_dateianhang', [`id`, `immobilien_id`, `user_id`, `kunde_id`], [
                    [21, 1, NULL, NULL],
                    [22, 2, NULL, NULL],
                    [24, 4, NULL, NULL],
                    [25, 5, NULL, NULL],
                    [26, 6, NULL, NULL],
                ])
                ->execute();
        $connection->createCommand()
                ->batchInsert('dateianhang', [`id`, `bezeichnung`, `dateiname`, `angelegt_am`, `aktualisert_am`, `angelegt_von`, `aktualisiert_von`, `l_dateianhang_art_id`, `e_dateianhang_id`], [
                    [14, 'Bild für eine Immobilie', 'villa1.jpg', '2018-06-10 20:41:35', NULL, 8, NULL, 4, 21],
                    [15, 'Bild für eine Immobilie', 'modern-minimalist-dining-room-3108037__340.jpg', '2018-06-10 21:32:05', NULL, 8, NULL, 3, 22],
                    [17, 'Bild für eine Immobilie', 'haus1.jpg', '2018-06-10 21:44:24', NULL, 8, NULL, 1, 24],
                    [18, 'Dokumente o.ä. für eine Immobilie', 'Expose.pdf', '2018-06-11 17:38:40', NULL, 8, NULL, 9, 25],
                    [19, 'Bild für eine Immobilie', 'kitchen-1940175__340.jpg', '2018-06-11 17:38:40', NULL, 8, NULL, 9, 25],
                    [20, 'Bild für eine Immobilie', 'modern-minimalist.jpg', '2018-06-13 18:05:04', NULL, 8, NULL, 3, 26]
                ])
                ->execute();
        $connection->createCommand()
                ->batchInsert('besichtigungstermin', [`id`, `uhrzeit`, `Relevanz`, `angelegt_am`, `aktualisiert_am`, `angelegt_von`, `aktualisiert_von`, `Immobilien_id`], [
                    [1, '2018-06-01 09:00:00', 1, '2018-05-31 14:21:29', NULL, 8, NULL, 28],
                ])
                ->execute();
    }

}
