<?php

use yii\helpers\Html;
?>
<?php
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="container-fluid">
    <br>
    <p>
        <?= Html::a(Yii::t('app', 'Tiefergehende Suche'), '#', ['class' => 'btn btn-info search-button']) ?>
        <?= Html::a(Yii::t('app', 'zurück'), ['/site/index'], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="col-md-12">
        <div class="page-header">
            <h2>Immobilienofferten <small><?= $count ?> Angebote gefunden</small></h2>
            <p>Pushen Sie auf die Immobilienbilder, um mehr Informationen abzurufen </p>
        </div>
        <div class="row">
            <?php
            for ($i = 0; $i < count($ArrayOfFilename); $i++) {
                ?>
                <div class="col-md-3">
                    <?=
                    Html::a(
                            Html::img(
                                    "@web/img/$ArrayOfFilename[$i]", ['alt' => 'PicNotFound', 'class' => 'img-circle', 'style' => 'width:125px;height:125px']
                            ), ['/immobilien/index', 'id' => $ArrayOfImmo[$i]], ['title' => 'Immobiliendaten abrufen', 'data' => ['pjax' => '0']]
                    )
                    ?>
                </div>
                <div class="col-md-3">
                    <?= "<label>Standort: $ArrayOfPlz[$i] $ArrayOfTown[$i]</label><br>" ?>
                    <?= "<label>Strasse: $ArrayOfStreet[$i]</label><br>" ?>
                    <?= "<label>Wohnfläche: $ArrayOfGroesse[$i] m<sup>2</sup></label><br>" ?>
                    <?= "<label>Zimmer: $ArrayOfRooms[$i]</label><br>" ?>
                    <?= "<label>$ArrayOfArt[$i]: $ArrayOfMoney[$i] €<br>" ?>
                </div>
                <?php
                if ($i >= 1 && $i % 2 != 0) {
                    ?>
                    <script>
                        var myWidth = 0;
                        if (typeof (window.innerWidth) == 'number') {
                            myWidth = window.innerWidth;
                        }
                        if (myWidth >= 1000) {
                            document.write("<br><br><br><br><br><br><br>");
                        }
                    </script>
                    <?php
                }
                ?>
            </div>
            <hr>
            <h3>Immobilienofferten <small>ohne Bilder</small></h3>
            <hr>
            <?php
            foreach ($ArrayOfDifference as $attribute) {
                $town = frontend\models\Immobilien::findOne(['id' => $attribute])->stadt;
                $street = frontend\models\ImmobilienSearch::findOne(['id' => $attribute])->strasse;
                $wohnflaeche = frontend\models\ImmobilienSearch::findOne(['id' => $attribute])->wohnflaeche;
                $raeume = frontend\models\ImmobilienSearch::findOne(['id' => $attribute])->raeume;
                $kosten = frontend\models\ImmobilienSearch::findOne(['id' => $attribute])->geldbetrag;
                $betrag = number_format(
                        $kosten, // zu konvertierende zahl
                        2, // Anzahl an Nochkommastellen
                        ",", // Dezimaltrennzeichen
                        "."    // 1000er-Trennzeichen
                );
                if (frontend\models\ImmobilienSearch::findOne(['id' => $attribute])->l_art_id == 1) {
                    $begriff = "Kaltmiete";
                } else if ((frontend\models\ImmobilienSearch::findOne(['id' => $attribute])->l_art_id == 2)) {
                    $begriff = "Kaufpreis";
                }
                ?>
                <div class="col-md-12">
                    <label>Standort:</label><label><?= $town ?>,</label>
                    <label>Strasse:</label><label><?= $street ?>,</label>
                    <label>Wohnfläche:</label><label><?= $wohnflaeche ?>,</label>
                    <label>Zimmer:</label><label><?= $raeume ?>,</label>
                    <label><?= $begriff ?>:</label><label><?= $betrag ?> €</label> <?= Html::a(Yii::t('app', 'zum Angebot'), ['/immobilien/index', 'id' => $attribute], ['class' => 'btn btn-success']) ?>
                </div>
            <?php }
            ?>
    </div>
</div>



