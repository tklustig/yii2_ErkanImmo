<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;

$this->title = Yii::t('app', 'Rechnung');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});

 var isIE = /*@cc_on!@*/false || !!document.documentMode;
    var isEdge = !isIE && !!window.StyleMedia;
    if (isEdge)
        alert('Die Druckoption kann mit Browser aus dem Hause Microsoft nicht aufgerufen werden. Verwenden Sie besser Chrome, Safari oder Firefox!');

";
$this->registerJs($search);
?>
<div class="rechnung-index">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($searchModel, 'choice_date')->radioList([0 => 'Vorher', 1 => 'Nachher'], ['itemOptions' => ['class' => 'choiceRadio']])->label('Grenzen Sie über diese beiden Radio Buttons Ihre Suche in AdvancedSearch ein'); ?>
    <?php
    ActiveForm::end();
    ?>

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],
        'id',
        'datumerstellung',
        'datumfaellig',
        //'beschreibung:ntext',
        'geldbetrag',
        [
            'attribute' => 'mwst_id',
            'label' => Yii::t('app', 'MwSt/VSt'),
            'value' => function($model) {
                if ($model->mwst) {
                    return $model->mwst->satz . '%';
                } else {
                    return NULL;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\backend\models\LMwst::find()->asArray()->all(), 'id', 'satz'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Mehrwertsteuer', 'id' => 'grid-rechnung-search-mwst_id']
        ],
        [
            'attribute' => 'kunde_id',
            'label' => Yii::t('app', 'Kunde'),
            'format' => 'html',
            'value' => function($model, $id) {
                $kundeGeschlecht = frontend\models\Kunde::findOne(['id' => $model->kunde->id])->geschlecht0->typus;
                $kundeVorname = frontend\models\Kunde::findOne(['id' => $model->kunde->id])->vorname;
                $kundeNachname = frontend\models\Kunde::findOne(['id' => $model->kunde->id])->nachname;
                $kundeBirthday = frontend\models\Kunde::findOne(['id' => $model->kunde->id])->geburtsdatum;
                $expression = new yii\db\Expression('NOW()');
                $now = (new \yii\db\Query)->select($expression)->scalar();
                $diff = strtotime($now) - strtotime($kundeBirthday);
                $hours = floor($diff / (60 * 60));
                $year = floor($hours / 24 / 365);
                $output = $year . " Jahre alt";
                $kundeBirthday = $output;
                $kundePlz = frontend\models\Kunde::findOne(['id' => $model->kunde->id])->lPlz->plz;
                $kundeStadt = frontend\models\Kunde::findOne(['id' => $model->kunde->id])->stadt;
                $givaBackValue = $kundeGeschlecht . ' ' . $kundeVorname . ' ' . $kundeNachname . '<br>' . $kundeBirthday . '<br>wohnhaft in ' . $kundePlz . ' ' . $kundeStadt;
                return $givaBackValue;
                //return $modelKunde;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(frontend\models\Kunde::find()->asArray()->all(), 'id', 'nachname'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Kunde', 'id' => 'grid-rechnung-search-kunde_id']
        ],
        [
            'attribute' => 'makler_id',
            'label' => Yii::t('app', 'Makler'),
            'value' => function($model) {
                return $model->makler->username;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(common\models\User::find()->asArray()->all(), 'id', 'username'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Makler', 'id' => 'grid-rechnung-search-makler_id']
        ],
        /*  [
          'attribute' => 'kopf_id',
          'label' => Yii::t('app', 'Kopf'),
          'value' => function($model){
          if ($model->kopf)
          {return $model->kopf->id;}
          else
          {return NULL;}
          },
          'filterType' => GridView::FILTER_SELECT2,
          'filter' => \yii\helpers\ArrayHelper::map(\backend\models\Kopf::find()->asArray()->all(), 'id', 'id'),
          'filterWidgetOptions' => [
          'pluginOptions' => ['allowClear' => true],
          ],
          'filterInputOptions' => ['placeholder' => 'Kopf', 'id' => 'grid-rechnung-search-kopf_id']
          ],
          [
          'attribute' => 'angelegt_von',
          'label' => Yii::t('app', 'Angelegt Von'),
          'value' => function($model){
          if ($model->angelegtVon)
          {return $model->angelegtVon->id;}
          else
          {return NULL;}
          },
          'filterType' => GridView::FILTER_SELECT2,
          'filter' => \yii\helpers\ArrayHelper::map(common\models\User::find()->asArray()->all(), 'id', 'id'),
          'filterWidgetOptions' => [
          'pluginOptions' => ['allowClear' => true],
          ],
          'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-rechnung-search-angelegt_von']
          ],
          [
          'attribute' => 'aktualisiert_von',
          'label' => Yii::t('app', 'Aktualisiert Von'),
          'value' => function($model){
          if ($model->aktualisiertVon)
          {return $model->aktualisiertVon->id;}
          else
          {return NULL;}
          },
          'filterType' => GridView::FILTER_SELECT2,
          'filter' => \yii\helpers\ArrayHelper::map(common\models\User::find()->asArray()->all(), 'id', 'id'),
          'filterWidgetOptions' => [
          'pluginOptions' => ['allowClear' => true],
          ],
          'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-rechnung-search-aktualisiert_von']
          ],
          'angelegt_am',
          'aktualisiert_am',

         */
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{save-as-new} {view} {update} {delete}<br>{print}',
            'buttons' => [
                'save-as-new' => function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-copy"></span>', $url, ['title' => 'Save As New']);
                },
                'print' => function ($model, $id) {
                    return Html::a('<span class="glyphicon glyphicon-print"></span>', ['/rechnung/print', 'id' => $id->id], ['title' => 'Rechnung drucken', 'target' => '_blank', 'data' => ['pjax' => '0']]);
                },
            ],
        ],
    ];
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => '.choiceRadio',
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'options' => [
        //'style' => 'overflow: auto; word-wrap: break-word;'
        ],
        'condensed' => true,
        'responsiveWrap' => true,
        'hover' => true,
        'persistResize' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            "heading" => "<h3 class='panel-title'><i class='glyphicon glyphicon-globe'></i> " . $this->title . "</h3>",
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['/rechnung/index'], ['class' => 'btn btn-warning', 'title' => 'Setzt die GridView zurück']),
            'toggleDataOptions' => ['minCount' => 10],
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}'
        ],
        'toggleDataOptions' => ['minCount' => 10],
    ]);
    ?>
</div>
