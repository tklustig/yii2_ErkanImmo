<?php
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;

    $dataProvider = new ArrayDataProvider([
        'allModels' => $model->besichtigungstermins,
        'key' => function($model){
            return ['id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'Immobilien_id' => $model->Immobilien_id];
        }
    ]);
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        'l_plz_id',
        'strasse',
        'uhrzeit',
        'Relevanz',
        [
                'attribute' => 'lStadt.id',
                'label' => Yii::t('app', 'L Stadt')
            ],
        'angelegt_am',
        'aktualisiert_am',
        'angelegt_von',
        'aktualisiert_von',
        [
            'class' => 'yii\grid\ActionColumn',
            'controller' => 'besichtigungstermin'
        ],
    ];
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'],
        'pjax' => true,
        'beforeHeader' => [
            [
                'options' => ['class' => 'skip-export']
            ]
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => false,
        'persistResize' => false,
    ]);
