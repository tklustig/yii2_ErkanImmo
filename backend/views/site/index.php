
<div id="test"></div>

<div class="jumbotron">
    <p> Outfit has been changed modifying CSS-File'\frontend\web\site.css'</p></font>
    <h1>Test Page!</h1>
    <h2>Test Programming experiences</h2>

    <label class="lead">You have successfully created your Yii-powered application.</label>

</div>
<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\ErrorException;

\drmabuse\slick\SlickWidget::widget([
    'container' => '.single-item',
    'settings' => [
        'slick' => [
            'infinite' => true,
            'slidesToShow' => 3,
            'onBeforeChange' => new \yii\web\JsExpression('function(){
                }'),
            'onAfterChange' => new \yii\web\JsExpression('function(){
                    console.debug(this);
                }'),
            'responsive' => [
                [
                    'breakpoint' => 768,
                    'settings' => [
                        'arrows' => true,
                        'centerMode' => true,
                        'centerPadding' => 4,
                        'slidesToShow' => 8
                    ]
                ]
            ],
        ],
        'slickGoTo' => 3,
    ]
]);

$ordner = dirname(__DIR__) . "/marx.jpg";
$ordner_ = dirname(__DIR__);
$ordner_ = substr($ordner_, 25);
$ordner__ = "/frontend/web/img/";
if (file_exists($ordner))
    echo"<p>Bilder exisiteren im Ordner $ordner<br></p>";
else
    echo "<p><font color='blue'>Bilder exisiteren nicht im Ordner $ordner_,sondern im Ordner $ordner__<br>Yii2-UrlManager:" . Url::to(['']) . "<br></p></font>";
?>
<?php
$this->title = 'My Yii Application';
?>

<div class="slider single-item">
    <div><p><?= Html::img('@web/img/punk.jpg', ['alt' => 'some', 'class' => 'jumbotron']); ?></p></div>
    <div><p><?= Html::img('@web/img/punk2.jpg', ['alt' => 'some', 'class' => 'jumbotron']); ?></p></div>
    <div><p><?= Html::img('@web/img/anarcho.jpg', ['alt' => 'some', 'class' => 'jumbotron']); ?></p></div>
    <div><p><?= Html::img('@web/img/kom2.jpg', ['alt' => 'some', 'class' => 'jumbotron']); ?></p></div>
    <div><p><?= Html::img('@web/img/kom3.jpg', ['alt' => 'some', 'class' => 'jumbotron']); ?></p></div>
    <div><p><?= Html::img('@web/img/anarcho3.jpg', ['alt' => 'some', 'class' => 'jumbotron']); ?></p></div>
</div>

<div class="body-content">

    <div class="row">
        <div class="col-lg-4">
            <h2>Heading</h2>

            <label>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                fugiat nulla pariatur.</label>
        </div>
        <div class="col-lg-4">
            <h2>Heading</h2>

            <label>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                fugiat nulla pariatur.</label>

        </div>
        <div class="col-lg-4">
            <h2>Heading</h2>

            <label>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                fugiat nulla pariatur.</label>

        </div>
    </div>
</div>
