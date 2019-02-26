<?php
use yii\helpers\Html;
use kartik\tabs\TabsX;
use yii\helpers\Url;
$items = [
    [
        'label' => '<i class="glyphicon glyphicon-book"></i> '. Html::encode(Yii::t('app', 'Kunde')),
        'content' => $this->render('_detail', [
            'model' => $model,
        ]),
    ],
        [
        'label' => '<i class="glyphicon glyphicon-book"></i> '. Html::encode(Yii::t('app', 'Adminbesichtigungkunde')),
        'content' => $this->render('_dataAdminbesichtigungkunde', [
            'model' => $model,
            'row' => $model->adminbesichtigungkundes,
        ]),
    ],
            [
        'label' => '<i class="glyphicon glyphicon-book"></i> '. Html::encode(Yii::t('app', 'E Dateianhang')),
        'content' => $this->render('_dataEDateianhang', [
            'model' => $model,
            'row' => $model->eDateianhangs,
        ]),
    ],
                    [
        'label' => '<i class="glyphicon glyphicon-book"></i> '. Html::encode(Yii::t('app', 'Kundeimmobillie')),
        'content' => $this->render('_dataKundeimmobillie', [
            'model' => $model,
            'row' => $model->kundeimmobillies,
        ]),
    ],
    ];
echo TabsX::widget([
    'items' => $items,
    'position' => TabsX::POS_ABOVE,
    'encodeLabels' => false,
    'class' => 'tes',
    'pluginOptions' => [
        'bordered' => true,
        'sideways' => true,
        'enableCache' => false
    ],
]);
?>
