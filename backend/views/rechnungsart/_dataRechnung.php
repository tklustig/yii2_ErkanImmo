<?php
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;

    $dataProvider = new ArrayDataProvider([
        'allModels' => $model->rechnungs,
        'key' => 'id'
    ]);
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'datumerstellung',
        'datumfaellig',
        'beschreibung:ntext',
        'vorlage:ntext',
        'geldbetrag',
        [
                'attribute' => 'mwst.id',
                'label' => Yii::t('app', 'Mwst')
            ],
        [
                'attribute' => 'kunde.id',
                'label' => Yii::t('app', 'Kunde')
            ],
        [
                'attribute' => 'makler.id',
                'label' => Yii::t('app', 'Makler')
            ],
        [
                'attribute' => 'kopf.id',
                'label' => Yii::t('app', 'Kopf')
            ],
        'rechnungPlain:ntext',
        [
                'attribute' => 'aktualisiertVon.id',
                'label' => Yii::t('app', 'Aktualisiert Von')
            ],
        [
                'attribute' => 'angelegtVon.id',
                'label' => Yii::t('app', 'Angelegt Von')
            ],
        'aktualisiert_am',
        'angelegt_am',
        [
            'class' => 'yii\grid\ActionColumn',
            'controller' => 'rechnung'
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
