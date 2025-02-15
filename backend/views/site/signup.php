<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\password\PasswordInput;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jumbotron">
    <div class="container">
        <div class="page-header"><h2><?= Html::encode($this->title) ?><small>
                    <?= Html::a(Yii::t('app', 'zurück zur GridView'), ['/site/index', 'class' => 'text-warning']) ?>
                </small></h2></div>

        <p>Folgende Felder werden für eine Registrierung benötigt:</p>

        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <div class="col-md-3">
                <?= $form->field($model, 'username')->textInput(['placeholder' => 'neuen Benutzernamen eingeben']) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'email')->textInput(['placeholder' => 'neue Mailadresse eingeben']) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'telefon')->textInput(['value' => $telefon])->hint('Bitte ändern') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'password')->widget(PasswordInput::classname(), []) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

