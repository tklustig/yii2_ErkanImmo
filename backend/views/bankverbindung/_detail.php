<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Bankverbindung */

?>
<div class="bankverbindung-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        'laenderkennung',
        'institut',
        'blz',
        'kontoNr',
        'iban',
        'bic',
        'angelegt_am',
        'aktualisiert_am',
        'angelegt_von',
        [
            'attribute' => 'aktualisiertVon.id',
            'label' => Yii::t('app', 'Aktualisiert Von'),
        ],
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>