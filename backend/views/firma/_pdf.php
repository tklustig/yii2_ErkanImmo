<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Firma */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Firma'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firma-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Firma').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        'firmenname',
        [
                'attribute' => 'lRechtsform.id',
                'label' => Yii::t('app', 'L Rechtsform')
            ],
        'strasse',
        'hausnummer',
        [
                'attribute' => 'lPlz.id',
                'label' => Yii::t('app', 'L Plz')
            ],
        'ort',
        'geschaeftsfuehrer',
        'prokurist',
        'umsatzsteuerID',
        'anzahlMitarbeiter',
        [
                'attribute' => 'angelegtVon.id',
                'label' => Yii::t('app', 'Angelegt Von')
            ],
        [
                'attribute' => 'aktualisiertVon.id',
                'label' => Yii::t('app', 'Aktualisiert Von')
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
