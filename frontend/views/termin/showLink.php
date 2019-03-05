<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use frontend\models\Adminbesichtigungkunde;
use frontend\models\Kunde;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TerminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Besichtigungstermin(e)');

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="besichtigungstermin-index">
    <center>
        <h1><?= Html::encode($this->title) ?></h1>
    </center>
    <?php Pjax::begin(); ?>
    <?php
    $output = "";
    $expression = new yii\db\Expression('NOW()');
    $now = (new \yii\db\Query)->select($expression)->scalar();
    if (!empty(Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $id]))) {
        $idOfmodelAdminBesKu = Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $id])->id;
    } else {
        print_r('Die findOne()-Methode für Adminbesichtigungkunde(Zeile 27) scheint NULL zu sein. Bitte informieren Sie den Softwarehersteller!');
        die();
    }
    if (!empty(Adminbesichtigungkunde::findOne(['id' => $idOfmodelAdminBesKu]))) {
        $kundetelefon = null;
        $kundemail = null;
        $kundeID = Adminbesichtigungkunde::findOne(['id' => $idOfmodelAdminBesKu])->kunde_id;
        $kundenGeschlecht = Kunde::findOne(['id' => $kundeID])->geschlecht0->typus;
        $kundenVorName = Kunde::findOne(['id' => $kundeID])->vorname;
        $kundenNachName = Kunde::findOne(['id' => $kundeID])->nachname;
        $kundeStadt = Kunde::findOne(['id' => $kundeID])->stadt;
        $kundeStrasse = Kunde::findOne(['id' => $kundeID])->strasse;
        if (!empty(Kunde::findOne(['id' => $kundeID])->telefon))
            $kundetelefon = Kunde::findOne(['id' => $kundeID])->telefon;
        if (!empty(Kunde::findOne(['id' => $kundeID])->mail))
            $kundemail = Kunde::findOne(['id' => $kundeID])->mail;
        $kundeGeburtsdatum = Kunde::findOne(['id' => $kundeID])->geburtsdatum;
        if ($kundetelefon == null)
            $kundetelefon = 'nicht verfügbar';
        if ($kundemail == null)
            $kundemail = 'nicht verfügbar';
    } else {
        print_r('Die findOne()-Methode für Adminbesichtigungkunde(Zeile 33) scheint NULL zu sein. Bitte informieren Sie den Softwarehersteller!');
        die();
    }
    $diff = strtotime($now) - strtotime($kundeGeburtsdatum);
    $hours = floor($diff / (60 * 60));
    $year = floor($hours / 24 / 365);
    $output = date("d.m.Y", strtotime($kundeGeburtsdatum)) . ', ' . $year . " Jahre alt";
    $einleitung = 'Folgender Interessent hat mit Ihnen für diese Immobilie einen Termin vereinbart:\n';
    $giveBack = $einleitung . $kundenGeschlecht . ' ' . $kundenVorName . '  ' . $kundenNachName . ',\n' . 'wohnhaft in ' . $kundeStadt . ',\n' . $kundeStrasse . ',\n' . 'Geburtsdaten:' . ' ' . $output . '<br>Kontaktdaten: <span class="glyphicon glyphicon-earphone"></span> ' . $kundetelefon . ' / <span class="glyphicon glyphicon-envelope"></span> ' . $kundemail;
    $js = "krajeeDialog.alert('$giveBack');";
    $this->registerJs($js);
    $link = \Yii::$app->urlManagerBackend->baseUrl . '/home';
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
            }
        ],
        [
            'attribute' => 'id',
            'label' => Yii::t('app', 'Immobilien-Id'),
            'value' => function($model) {
                return $model->immobilien->id;
            }
        ],
        'immobilien.stadt',
        'immobilien.strasse',
        [
            'attribute' => '',
            'label' => Yii::t('app', 'Art'),
            'value' => function($model) {
                if ($model->immobilien->l_art_id == 1)
                    $begriff = "Vermietobjekt";
                else if ($model->immobilien->l_art_id == 2)
                    $begriff = "Verkaufsobjekt";
                return $begriff;
            }
        ],
        'uhrzeit',
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'Relevanz',
            'trueLabel' => 'Ja',
            'falseLabel' => 'Nein',
            'label' => 'Priorität hoch',
            'encodeLabel' => false,
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{kunde}',
            'buttons' => [
                'kunde' => function ( $id, $model) {
                    $output = "";
                    $expression = new yii\db\Expression('NOW()');
                    $now = (new \yii\db\Query)->select($expression)->scalar();
                    $idTermin = $model->id;
                    $idOfmodelAdminBesKu = \frontend\models\Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $idTermin]);
                    if (!(empty($idOfmodelAdminBesKu))) {
                        $kundeID = \frontend\models\Adminbesichtigungkunde::findOne(['id' => $idOfmodelAdminBesKu])->kunde_id;
                        $kundenGeschlecht = frontend\models\Kunde::findOne(['id' => $kundeID])->geschlecht;
                        $kundenVorName = frontend\models\Kunde::findOne(['id' => $kundeID])->vorname;
                        $kundenNachName = frontend\models\Kunde::findOne(['id' => $kundeID])->nachname;
                        $kundeStadt = frontend\models\Kunde::findOne(['id' => $kundeID])->stadt;
                        $kundeStrasse = frontend\models\Kunde::findOne(['id' => $kundeID])->strasse;
                        $kundeGeburtsdatum = frontend\models\Kunde::findOne(['id' => $kundeID])->geburtsdatum;
                        $diff = strtotime($now) - strtotime($kundeGeburtsdatum);
                        $hours = floor($diff / (60 * 60));
                        $year = floor($hours / 24 / 365);
                        $output = date("d.m.Y", strtotime($kundeGeburtsdatum)) . "<br>" . $year . " Jahre alt";
                        $giveBack = $kundenGeschlecht . ' ' . $kundenVorName . ' ' . $kundenNachName . '\n' . 'wohnhaft in ' . $kundeStadt . ' ' . $kundeStrasse . '\n' . 'Geburtsdaten:' . ' ' . $output;
                        //$js = "krajeeDialog.alert('Hold On! This is a Krajee alert!');";
                        $js = "krajeeDialog.alert('$giveBack');";
                        //return Html::a('<span class="glyphicon glyphicon-user"></span>', [$this->registerJs($js)], ['title' => 'Interessent anzeigen', 'data' => ['pjax' => '0']]);
                        return Html::a('<span class="glyphicon glyphicon-home"></span>', ['/termin/link', 'id' => $model->id], ['title' => 'Interessent anzeigen', 'data' => ['pjax' => '0']]);
                    } else {
                        print_r('Die findOne()-Methode für Adminbesichtigungkunde(Zeile 92) scheint NULL zu sein. Bitte informieren Sie den Softwarehersteller!');
                        die();
                    }
                },
            ],
        ],
    ];
    ?>
    <div class="container-fluid">
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
                    Html::a('<span class=" fa fa-envelope-square"> zurück', $link, ['class' => 'btn btn-default', 'title' => 'zeigt alle Mails an, die von Ihnen implementiert wurden', 'data' => ['pjax' => '0']])
                ],
                '{export}',
                '{toggleData}'
            ],
            'toggleDataOptions' => ['minCount' => 10],
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>


