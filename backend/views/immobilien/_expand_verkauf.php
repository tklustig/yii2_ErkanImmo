<?php

use yii\helpers\Html;
use kartik\tabs\TabsX;

$items = [
    [
        'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode(Yii::t('app', 'Immobilien')),
        'content' => $this->render('_detail_verkauf', [
            'model' => $model,
        ]),
    ],
    [
        'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode(Yii::t('app', 'Besichtigungstermin')),
        'content' => $this->render('_dataBesichtigungstermin', [
            'model' => $model,
            'row' => $model->besichtigungstermins,
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
