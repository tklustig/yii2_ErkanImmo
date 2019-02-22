<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        'username',
        'auth_key',
        'password_hash',
        'email:email',
        'telefon',
    ];
    ?>
    <?=
    GridView::widget([
        'columns' => $gridColumn,
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'export' => false,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-user']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
    ]);
    ?>

</div>


