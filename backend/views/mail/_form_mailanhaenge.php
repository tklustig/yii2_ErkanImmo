<?php

use yii\helpers\Html;

$this->title = "Mailanhänge";
$this->params['breadcrumbs'][] = $this->title;
$urlWeb = Yii::getAlias('@web/img/') . DIRECTORY_SEPARATOR;
$urlRoot = Yii::getAlias('@documentsMail') . DIRECTORY_SEPARATOR;
?>
<div class="jumbotron">
    <div class="container">
        <div class="page-header"><h2><?= Html::encode($this->title) ?></h2>                  
            <span class="badge badge-light">Hier werden prinzipiell alle Mailanhänge der Mail-ID:<?= $id ?> zur Schau gestellt</span> 
        </div>
        <div class="row">
            <?php
            if (!empty($arrayOfPics) && empty($arrayOfOther)) {
                ?>
                <div class="col-md-12">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <?php
                            for ($i = 0; $i < count($arrayOfPics); $i++) {
                                ?><th><?=
                                    $arrayOfPics[$i] . '<br>' . $arrayOfBezPics[$i];
                                    ?></th><td style="text-align:center" ><?=
                                    Html::img($urlWeb . $arrayOfPics[$i], ['alt' => 'Pic not found', 'class' => 'img-circle', 'style' => 'width:125px;height:125px']);
                                    ?> 
                                </td> <?php
                            }
                            ?> 
                        </tr>
                    </table>
                </div>
                <?php
            } else if (empty($arrayOfPics) && !empty($arrayOfOther)) {
                ?>
                <div class="col-md-12">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <?php
                            for ($i = 0; $i < count($arrayOfOther); $i++) {
                                ?><th><?=
                                    $arrayOfOther[$i] . '<br>' . $arrayOfBezOther[$i];
                                    ?></th><td><?=
                                    Html::a('Doukument anzeigen', ['/mail/document', 'id' => $arrayOfOther[$i]], ['class' => 'btn btn-primary', 'target' => '_blank']);
                                    ?>                                  
                                </td> <?php
                            }
                            ?> 
                        </tr>
                    </table>
                </div>
                <?php
            } else if (!empty($arrayOfPics) && !empty($arrayOfOther)) {
                ?>
                <div class="col-md-6">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <?php
                            for ($i = 0; $i < count($arrayOfPics); $i++) {
                                ?><th><?=
                                    $arrayOfPics[$i] . '<br>' . $arrayOfBezPics[$i];
                                    ?></th><td style="text-align:center" ><?=
                                    Html::img($urlWeb . $arrayOfPics[$i], ['alt' => 'Pic not found', 'class' => 'img-circle', 'style' => 'width:125px;height:125px']);
                                    ?> 
                                </td> <?php
                            }
                            ?> 
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <?php
                            for ($i = 0; $i < count($arrayOfOther); $i++) {
                                ?><th><?=
                                    $arrayOfOther[$i] . '<br>' . $arrayOfBezOther[$i];
                                    ?></th><td><?=
                                    Html::a('Doukument anzeigen', ['/mail/document', 'id' => $arrayOfOther[$i]], ['class' => 'btn btn-primary', 'target' => '_blank']);
                                    ?>                                  
                                </td> <?php
                            }
                            ?> 
                        </tr>
                    </table>
                </div>
            <?php }
            ?>
        </div>
    </div>
</div>

