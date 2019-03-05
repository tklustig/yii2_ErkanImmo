<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Graphical Frontendinitialisation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jumbotron">
    <div class="container">
        <div class="page-header"><h2><?= Html::encode($this->title) ?><small>Sie können genau ein Theme über die DropDown-Box initialisieren</small></h2>
            <span class="badge badge-light">Das von ihnen initialiserte Bild wird im Frontend als theme.jpg jedesmal auf's neue überschrieben</span>     
        </div>
        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?php
            $url = Yii::getAlias('@web/img/');
            ?>
            <div class="col-md-12">
                <table class="table table-bordered table-responsive">
                    <tr>
                        <?php
                        for ($i = 0; $i < count($arrayOfFileNames); $i++) {
                            ?><th><?=
                                $arrayOfFileNames[$i];
                                ?><td style="text-align:center" ><?=
                                Html::img($url . $arrayOfFileNames[$i], ['alt' => 'Pic not found', 'class' => 'img-circle', 'style' => 'width:125px;height:125px']);
                            }
                            ?> 
                    </tr>
                </table>
            </div>
            <div class="col-md-12">
                <?=
                $form->field($DynamicModel, 'file')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(frontend\models\Dateianhang::find()->where(['l_dateianhang_art_id' => $max])->asArray()->all(), 'dateiname', 'dateiname'),
                    'options' => ['placeholder' => Yii::t('app', 'Filename')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
            <?= Html::a(Yii::t('app', 'Abbruch'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
