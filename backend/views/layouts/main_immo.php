<?php

use yii\helpers\Html;
use common\classes\AdminLteAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;

$js = <<<SCRIPT
$(function () {
   $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
        html:true
    });
});
        $(function () {
   $('body').popover({
    selector: '[data-toggle="popover"]',
        html:true
    });
});
SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js, \yii\web\view::POS_READY);
AdminLteAsset::register($this);
$this->beginPage()
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <style>
            body{
                background-color: #D8D8D8 !important;
            }
        </style>
        <?php $this->head(); ?>
    </head>
    <body>
        <?php $this->beginBody(); ?>
        <div class="wrap">
            <?php
            NavBar::begin([
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                [
                    'label' => 'Admin',
                    'items' => [
                        ['label' => 'Neue Immobilie anlegen', 'url' => ['/immobilien/create']],
                        '<li class="divider"></li>',
                        ['label' => 'Immobilien aufrufen', 'url' => ['/immobilien/index']],
                        '<li class="divider"></li>',
                        ['label' => 'Neuen Benutzer anlegen', 'url' => ['/site/signup']],
                        '<li class="divider"></li>',
                        ['label' => 'Benutzer löschen', 'url' => ['/site/deluser']],
                        '<li class="divider"></li>',
                        ['label' => 'Immobilie löschen', 'url' => ['/site/delimmo']],
                        '<li class="divider"></li>',
                        ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                        '<li class="divider"></li>',
                    ],
                ],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = '<li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>';
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
            ?>

            <div class="container-fluid">
                <?= $content ?>
            </div>
        </div>
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>
