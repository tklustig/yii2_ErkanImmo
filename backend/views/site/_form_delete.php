<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Themes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kunde-index">
    <div class="page-header"><h1><?= Html::encode($this->title) ?><small> löschen Sie entweder alle oder ausgewählte Themes</small></h1>   
    </div>
    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        [
            /*
              Hier wird das Bewerberbild in einer eigenen Spalte implementiert.Das jeweilige Bild liefert die Methode GetBewerberBild(model),welche
              drei JOINs und eine dynamische WHERE-Klausel enthält,die auf den FK id_person von bewerber prüft. Das Bild liegt physikalisch auf dem Webspace,
              dessen Zugriffspfade in der Datenbank in einer ganz bestimmten Reihenfolge hinterlegt sein müssen!
             */
            'attribute' => '',
            'label' => Yii::t('app', ''),
            'format' => 'html', // sorgt dafür,dass das HTML im return gerendert wird
            'vAlign' => 'middle',
            'value' => function($model, $id) {
                $url = '@web/img/' . $model->dateiname;
                return Html::img($url, ['alt' => 'Theme nicht vorhanden', 'class' => 'img-circle', 'style' => 'width:100px;height:100px']);
            }
        ],
        'bezeichnung',
        'dateiname',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
        ],
    ];
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'options' => [
            'style' => 'overflow: auto; word-wrap: break-word;'
        ],
        'condensed' => true,
        'responsiveWrap' => true,
        'hover' => true,
        'persistResize' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            "heading" => "<h3 class='panel-title'><i class='glyphicon glyphicon-globe'></i> " . $this->title . "</h3>",
            'toggleDataOptions' => ['minCount' => 10],
        ],
        'toolbar' => [
            ['content' =>
                Html::a('<span class=" fa fa-cut"> Alle löschen <span class="fa fa-cut">', ['/site/deleteall'], ['class' => 'btn btn-warning', 'title' => 'löscht alle Themes auf einen Schlag', 'data' => ['pjax' => '0']])
            ],
            '{toggleData}'
        ],
        'toggleDataOptions' => ['minCount' => 10],
    ]);
    ?>

</div>