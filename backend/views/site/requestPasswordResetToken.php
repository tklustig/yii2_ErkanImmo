<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
?>
<div class="jumbotron">
    <div class="container">
        <div class="page-header"><h2><?= Html::encode($this->title) ?><small>
                    <?= Html::a(Yii::t('app', 'zurück zum Login'), ['/site/login', 'class' => 'text-warning']) ?>
                </small></h2></div>
        <div class="site-request-password-reset">
            <p>Geben Sie ihre Mailadresse ein. Ein Link, um das Passwort zurück zu setzen, wird dorthin versandt</p>

            <div class="row">
                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>



