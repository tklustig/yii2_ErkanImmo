<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Firma */

?>
<div class="firma-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        'firmenname',
        [
            'attribute' => 'lRechtsform.id',
            'label' => Yii::t('app', 'L Rechtsform'),
        ],
        'strasse',
        'hausnummer',
        [
            'attribute' => 'lPlz.id',
            'label' => Yii::t('app', 'L Plz'),
        ],
        'ort',
        'geschaeftsfuehrer',
        'prokurist',
        'umsatzsteuerID',
        'anzahlMitarbeiter',
        [
            'attribute' => 'angelegtVon.id',
            'label' => Yii::t('app', 'Angelegt Von'),
        ],
        [
            'attribute' => 'aktualisiertVon.id',
            'label' => Yii::t('app', 'Aktualisiert Von'),
        ],
        'angelegt_am',
        'aktualisiert_am',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>