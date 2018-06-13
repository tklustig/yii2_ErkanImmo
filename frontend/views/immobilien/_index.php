<?php

use yii\helpers\Html;
?>
<?php ?>

<div class=""jumbotron>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="page-header">
                <h3>Immobilienofferten <small><?= $count ?> Angebote gefunden</small></h3>
            </div>
            <div class="row">
                <?php
                for ($i = 0; $i < count($ArrayOfFilename); $i++) {
                    ?>
                    <div class="col-md-6">
                        <?=
                        Html::a(
                                Html::img(
                                        "@web/img/$ArrayOfFilename[$i]", ['alt' => 'PicNotFound', 'class' => 'img-circle', 'style' => 'width:125px;height:125px']
                                ), ['/immobilien/index', 'id' => $ArrayOfImmo[$i]], ['title' => 'Anlagen anzeigen', 'data' => ['pjax' => '0']]
                        )
                        ?>

                    </div>
                <?php } ?>
            </div>

        </div></div></div>

