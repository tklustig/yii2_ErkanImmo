<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <div id="Wrapper">
        <div id="Header_wrapper" >
            <header id="Header">
                <div class="header_placeholder"></div>
                <div id="Top_bar" class="loading">
                    <div class="container">
                        <div class="column one">
                        </div>
                    </div>
                </div>
            </header>
        </div>
        <div id="Content">
            <div class="content_wrapper clearfix">
                <div class="sections_group">
                    <div class="entry-content" itemprop="mainContentOfPage">
                        <div class="section mcb-section" style="padding-top:20px; padding-bottom:130px; background-color:#252628;">
                            <div class="section_wrapper mcb-section-inner">
                                <div class="wrap mcb-wrap two-third  valign-top clearfix" style="">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_column  column-margin-">
                                            <div class="column_attr clearfix"  style="">
                                                <h6 class="themecolor" style="text-transform: uppercase;">Kontaktformular</h6>
                                                <h3>Fragen, Probleme? Kontaktieren Sie mich!</h3>
                                                <hr class="no_line" style="margin: 0 auto 40px;">
                                                <div role="form" class="wpcf7" id="wpcf7-f63-p38-o1" lang="en-US" dir="ltr">
                                                    <div class="screen-reader-response"></div>
                                                    <div id="signup">
                                                        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                                                        <?=
                                                        $form->errorSummary($model);
                                                        ?>
                                                        <div class="column one-first">
                                                            <span class="wpcf7-form-control-wrap your-name">
                                                                <?= $form->field($model, 'vorname')->textInput(['class' => 'wpcf7-form-control wpcf7-text wpcf7-validates-as-required', 'aria-required' => true, 'aria-invalid' => false, 'placeholder' => "Ihr Vorname"])->label(false); ?>
                                                            </span>
                                                        </div>
                                                        <div class="column one-second">
                                                            <span class="wpcf7-form-control-wrap your-name">
                                                                <?= $form->field($model, 'nachname')->textInput(['class' => 'wpcf7-form-control wpcf7-text wpcf7-validates-as-required', 'aria-required' => true, 'aria-invalid' => false, 'placeholder' => "Ihr Nachname"])->label(false); ?>
                                                            </span>
                                                        </div>
                                                        <div class="column two-first">
                                                            <span class="wpcf7-form-control-wrap your-email">
                                                                <?= $form->field($model, 'email')->textInput(['class' => 'wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email', 'aria-required' => true, 'aria-invalid' => false, 'placeholder' => "Ihre Mailadresse"])->label(false); ?>
                                                            </span>
                                                        </div>
                                                        <div class="column two-first">
                                                            <span class="wpcf7-form-control-wrap your-subject">
                                                                <?= $form->field($model, 'betreff')->textInput(['class' => 'wpcf7-form-control wpcf7-text', 'aria-invalid' => false, 'placeholder' => 'Betreff'])->label(false); ?>
                                                            </span>
                                                        </div>
                                                        <div class="row">
                                                            <span class="wpcf7-form-control-wrap your-message">
                                                                <?=
                                                                $form->field($model, 'inhalt')->textarea(['style' => ['width' => '558px'], 'rows' => 10]);
                                                                ?>
                                                            </span>
                                                            <?=
                                                            $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                                                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                                                            ])
                                                            ?>
                                                            <?= Html::submitButton('Absenden', ['class' => 'absenden', 'name' => 'contact-button']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wrap mcb-wrap one-third  valign-top clearfix" style="padding:50px 0 0 3%">
                                    <div class="mcb-wrap-inner">
                                        <div class="column mcb-column one column_contact_box ">
                                            <div class="get_in_touch" style="background-image:url(../../../../betheme.muffingroupsc.netdna-cdn.com/be/3d/wp-content/uploads/2017/07/home_3d_contact1.jpg);">
                                                <h4>Meine Kontaktdaten:</h4>
                                                <div class="get_in_touch_wrapper">
                                                    <ul>
                                                        <li class="address">
                                                            <span class="icon">
                                                                <i class="icon-location"></i>
                                                            </span>
                                                            <span class="address_wrapper"><strong>Kanat, Erkan</strong><br> Lerchenstr. 12<br> 30659 Hannover, Nordstadt Deutschland</span>
                                                        </li>
                                                        <li class="phone phone-1">
                                                            <span class="icon"><i class="icon-phone"></i></span>
                                                            <p>
                                                                <label>+49(0)511 3458</label>
                                                            </p>
                                                        </li>
                                                        <li class="mail">
                                                            <span class="icon">
                                                                <i class="icon-mail"></i>
                                                            </span>
                                                            <p>
                                                                <a href='mailto:erkan@web.de'>erkan@web.de</a>
                                                            </p>
                                                        </li>
                                                        <li class="www">
                                                            <span class="icon">
                                                                <i class="icon-link"></i>
                                                            </span>
                                                            <p>
                                                                <?= Html::a(Yii::t('app', 'zurück zur Übersicht'), ['/site/index']) ?>
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php ActiveForm::end(); ?>


</div>