<?php

use yii\helpers\Html;
?>

<div class=""jumbotron>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Beispiel-Seiten-Überschrift <small>Untertitel</small></h1>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?=
                    Html::a(
                            Html::img(
                                    '@web/img/haus1.jpg', ['alt' => 'PicNotFound', 'class' => 'img-circle', 'style' => 'width:125px;height:125px']
                            ), ['/dateianhang/dateianhang/index'], ['title' => 'Anlagen anzeigen', 'data' => ['pjax' => '0']]
                    )
                    ?>
                </div></div></div></div></div>

