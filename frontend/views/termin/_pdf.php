<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$fk = \frontend\models\Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $model->id])->kunde_id;
$geschlechtKunde = \frontend\models\Kunde::findOne(['id' => $fk])->geschlecht0->typus;
$nameKunde = \frontend\models\Kunde::findOne(['id' => $fk])->vorname . ' ' . \frontend\models\Kunde::findOne(['id' => $fk])->nachname;
$this->title = $geschlechtKunde . ' ' . $nameKunde;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bewerber-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Terminbestätigung für') . ' ' . Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            'id',
            [
                'attribute' => 'angelegt_am',
                'label' => Yii::t('app', 'Termin wurde erstellt am'),
                'format' => ['datetime', 'php:d-M-Y H:i:s'],
                'value' => function($model) {
                    $time = new DateTime($model->angelegt_am);
                    ($model->angelegt_am) ? $value = $time : $value = 'kein Besichtigungstermin abrufbar';
                    return $value;
                }
            ],
            [
                'attribute' => 'uhrzeit',
                'label' => Yii::t('app', 'Besichtigungstermin ist am'),
                'format' => ['datetime', 'php:d-M-Y H:i:s'],
                'value' => function($model) {
                    $time = new DateTime($model->uhrzeit);
                    ($model->uhrzeit) ? $value = $time : $value = 'kein Besichtigungstermin abrufbar';
                    return $value;
                }
            ],
            [
                'attribute' => 'Relevanz',
                'label' => Yii::t('app', 'Abwicklungsinteresse vorhanden'),
                'value' => function($model) {
                    ($model->Relevanz == 1) ? $value = 'Ja' : $value = 'Nein';
                    return $value;
                }
            ],
            [
                'attribute' => '',
                'label' => Yii::t('app', 'Makler'),
                'value' => function($model) {
                    $fk = \frontend\models\Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $model->id])->admin_id;
                    $maklerName = \common\models\User::findOne(['id' => $fk])->username;
                    return $maklerName;
                }
            ],
            [
                'attribute' => '',
                'label' => Yii::t('app', 'Treffpunkt ist...'),
                'format' => 'html',
                'value' => function($model, $id) {
                    $kundenId = frontend\models\Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $model->id])->kunde_id;
                    $plzKunde = \frontend\models\Kunde::findOne(['id' => $kundenId])->lPlz->plz;
                    $StrasseKunde = \frontend\models\Kunde::findOne(['id' => $kundenId])->strasse;
                    $wohnortKunde = \frontend\models\Kunde::findOne(['id' => $kundenId])->stadt;
                    $giveBack = 'in ' . $plzKunde . ' ' . $wohnortKunde . '<br>' . $StrasseKunde;
                    return $giveBack;
                }
            ],
            'Immobilien_id',
            [
                'attribute' => '',
                'label' => Yii::t('app', 'Immobilie befindet sich..'),
                'format' => 'html',
                'value' => function($model, $id) {
                    $fk = $model->Immobilien_id;
                    $plzImmo = frontend\models\Immobilien::findOne(['id' => $fk])->lPlz->plz;
                    $StrasseImmo = \backend\models\Immobilien::findOne(['id' => $fk])->strasse;
                    $ortImmo = \backend\models\Immobilien::findOne(['id' => $fk])->stadt;
                    $giveBack = 'in ' . $plzImmo . ' ' . $ortImmo . '<br>' . $StrasseImmo;
                    return $giveBack;
                }
            ],
            [
                'attribute' => '',
                'label' => Yii::t('app', 'Zusatzinformation'),
                'format' => 'html',
                'style' => 'center',
                'value' => function($model, $id) {
                    $zusatz = 'Den geographischen Standpunkt der Immobilie lässt sich über unsere WebSite aufrufen und auch ausdrucken.';
                    return $zusatz;
                }
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
    <div class="row">
    </div>
</div>


