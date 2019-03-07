<?php
use yii\helpers\Html;
use common\widgets\Alert;
?>
<?php
//Hier werden alle Flashnachrichten ausgegeben
$alert=new Alert();
$alert->run();
/*$session = new Session();
if (!empty($session->getAllFlashes())) {
    foreach ($session->getAllFlashes() as $flash) {
        foreach ($flash as $ausgabe) {
            ?><?=
            Alert::widget([
                'type' => Alert::TYPE_SUCCESS,
                'title' => 'Information',
                'icon' => 'glyphicon glyphicon-exclamation-sign',
                'body' => $ausgabe,
                'showSeparator' => true,
                'delay' => false
            ]);
        }
    }
}

 */
?>
<center><div class="page-header">
        <h1 class="text-purple">Administration <small class="text-danger">Untertitel</small></h1>
    </div></center>

<label class="lead">Diese Seite ist nur dem Administator der Seite zugänglich. Sie ist durch einen PasswortHash geschützt.
    Das Passwort steht folglich nicht als Plaintext zur Verfügung.
    Sollten sie ihre Logindaten verloren haben, können Sie neue anfordern, indem Sie auf der Loginseite 'I forgot my password' anklicken. Das Einspeisen von Immobilien u.v.m. lässt sich durch obiges Menu ansteuern.</label>

<?php
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
