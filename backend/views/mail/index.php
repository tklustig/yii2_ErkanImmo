<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\form\ActiveForm;

$this->title = Yii::t('app', 'Mail');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="mail-index">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($searchModel, 'choice_date')->radioList([0 => 'Vorher', 1 => 'Nachher'], ['itemOptions' => ['class' => 'choiceRadio']])->label('Grenzen Sie über diese beiden Radio Buttons Ihre Suche in AdvancedSearch ein'); ?>
    <?php
    ActiveForm::end();
    ?>
    <?php
//Hier werden alle Flashnachrichten ausgegeben
    $session = new Session();
    $MessageArt = Alert::TYPE_WARNING;
    foreach ($session->getAllFlashes() as $flash) {
        if (count($flash) > 2) {
            ?><?=
            generateOutput($MessageArt, implode("<br/><hr/><br/>", $flash));
        } else {
            foreach ($flash as $ausgabe) {
                ?><?=
                generateOutput($MessageArt, $ausgabe);
            }
        }
    }
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
        'mail_from',
        'mail_to',
        'mail_cc',
        'mail_bcc',
        'betreff',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {delete}<br>{anhang}',
            'buttons' => [
                'anhang' => function ($id, $model) {

                    /*
                      $protocol = "http://";
                      $filename = Dateianhang::filename($model->id);
                      $id = $model->id;
                      $IdeDateianhang = Dateianhang::findOne(['id' => $id])->id_e_dateianhang;
                      $fk = EDateianhang::findOne(['id' => $IdeDateianhang]);
                      $ConfigData = $ftp->Configuration($fk);
                      $url = $protocol . $ConfigData[0] . $ConfigData[3] . $filename;
                      return Html::a('<span class="glyphicon glyphicon-paperclip"></span>', $url, ['target' => '_blank', 'title' => 'Show File in another tab', 'data' => ['pjax' => '0']]);
                     */
                    if (!empty(\frontend\models\EDateianhang::findOne(['mail_id' => $model->id]))) {
                        $arrayOfFilenames = array();
                        $fk = \frontend\models\EDateianhang::findOne(['mail_id' => $model->id])->id;
                        $arrayObjOfFilenames = frontend\models\Dateianhang::find()->where(['e_dateianhang_id' => $fk])->all();
                        foreach ($arrayObjOfFilenames as $item) {
                            array_push($arrayOfFilenames, $item->dateiname);
                        }
                        if (count($arrayOfFilenames) == 1) {
                            $url = '';
                            $rumpf = 'backend' . DIRECTORY_SEPARATOR . 'mailanhaenge';
                            $arrayOfUrlPart = explode('/', $_SERVER['HTTP_REFERER']);
                            $url .= $arrayOfUrlPart[2] . DIRECTORY_SEPARATOR . $arrayOfUrlPart[3] . DIRECTORY_SEPARATOR . $rumpf . DIRECTORY_SEPARATOR . $arrayOfFilenames[0];
                            $protocol = $_SERVER['REQUEST_SCHEME'] . ':' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
                            $url = $protocol . $url;
                            return Html::a('<span class="glyphicon glyphicon-paperclip"></span>', $url, ['target' => '_blank', 'title' => 'Anhang in neuem Tab rendern', 'data' => ['pjax' => '0']]);
                        } else if (count($arrayOfFilenames) > 1)
                            return Html::a('<span class="glyphicon glyphicon-paperclip"></span>', ['mail/anhaenge', 'id' => $fk], ['target' => '_blank', 'title' => 'Anhänge in anderer WebSite rendern', 'data' => ['pjax' => '0']]);
                    }
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
            'before' => Html::a(Yii::t('app', 'Mail erstellen'), ['/mail/create'], ['class' => 'btn btn-success', 'title' => 'Erstellt eine neue Mail']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['/mail/index'], ['class' => 'btn btn-warning', 'title' => 'Setzt die GridView zurück']),
            'toggleDataOptions' => ['minCount' => 10],
        ],
        'toolbar' => [
            ['content' =>
                Html::a('<span class=" fa fa-cut"> Alle löschen <span class="fa fa-cut">', ['/mail/deleteall'], ['class' => 'btn btn-warning', 'title' => 'löscht alle Mails auf einen Schlag', 'data' => ['pjax' => '0']])
            ],
            '{export}',
            '{toggleData}'
        ],
        'toggleDataOptions' => ['minCount' => 10],
    ]);
    ?>

</div>
<?php

function generateOutput($type, $content) {
    return Alert::widget(['type' => $type,
                'title' => 'Information',
                'icon' => 'glyphicon glyphicon-exclamation-sign',
                'body' => $content,
                'showSeparator' => true,
    ]);
}
?>
