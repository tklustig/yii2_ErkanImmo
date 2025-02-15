<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
$url = Yii::getAlias("@web") . '/img/';
?>
<div id="Wrapper">
    <div id="Content">
        <div class="content_wrapper clearfix">
            <div class="sections_group">
                <div class="entry-content" itemprop="mainContentOfPage">
                    <div style="background:url(<?= $url ?>themeImpressum.jpg);  background-repeat: no-repeat;  background-size: 100% 100%;">
                        <div class="section_wrapper mcb-section-inner">
                            <div class="wrap mcb-wrap one-second  valign-top clearfix" style="padding:0 4% 0 0 ">
                                <div class="mcb-wrap-inner">
                                    <div class="column mcb-column one column_column  column-margin-">
                                        <div class="column_attr clearfix" style="">
                                            <div class='impressum'><h2>Impressum</h2>
                                                <?= Html::a(Yii::t('app', 'zurück zur Übersicht'), ['/site/index']) ?>
                                                <p>Angaben gemäß § 5 TMG</p>
                                                <ul><li><?= $arrayOfBegriffe[0]; ?> </li>
                                                    <li><?= $arrayOfBegriffe[1]; ?>, <?= $arrayOfBegriffe[2]; ?> <?= $arrayOfBegriffe[3]; ?> </li>
                                                    <p> <strong>Vertreten durch: </strong></p>
                                                    <li> <?= $arrayOfBegriffe[4]; ?></li>
                                                    <p><strong>Kontakt:</strong></p>
                                                    <li><?= $arrayOfBegriffe[5]; ?></li>
                                                    <li><?= $arrayOfBegriffe[6]; ?></li>
                                                    <li> E-Mail: <a href='mailto:<?= $arrayOfBegriffe[7]; ?>'><?= $arrayOfBegriffe[7]; ?></a></li>
                                                    <p><strong>Registereintrag: </strong></p>
                                                    Gewerbeerlaubnis nach § 34c Gewerbeordnung erteilt;<br>
                                                    Umsatzsteuer-Identifikationsnummer gemäß §27a Umsatzsteuergesetz:<li><?= $arrayOfBegriffe[8]; ?></li>
                                                    <p><strong>Aufsichtsbehörde:</strong></p>
                                                    <li>  <?= $arrayOfBegriffe[9]; ?></li>
                                                </ul>
                                            </p><p><strong>Haftungsausschluss: </strong><br><br><strong>Haftung für Inhalte</strong><br><br>
                                            Die Inhalte unserer Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen. Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.<br><br><strong>Haftung für Links</strong><br><br>
                                            Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.<br><br><strong>Urheberrecht</strong><br><br>
                                            Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.<br><br><strong>Datenschutz</strong><br><br>
                                            Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten möglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder eMail-Adressen) erhoben werden, erfolgt dies, soweit möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben. <br>
                                            Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich. <br>
                                            Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten durch Dritte zur Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien wird hiermit ausdrücklich widersprochen. Die Betreiber der Seiten behalten sich ausdrücklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor.<br>
                                        </p><br>
                                        Website Impressum erstellt durch <a href="https://www.impressum-generator.de" target="_blank">impressum-generator.de</a> von der Kanzlei Hasselbach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrap mcb-wrap one-second  valign-top clearfix" style="padding:60px 0 0"  >
                        <div class="mcb-wrap-inner">
                            <div class="column mcb-column one column_image ">
                                <div class="image_frame image_item no_link scale-with-grid aligncenter no_border" >
                                    <div class="image_wrapper">
                                        <!-- stellt das große Bild dar -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>



