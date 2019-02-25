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
            .id_breite_css {
                background-color:#F2F5A9;
                width: 20px;
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
            $link = \Yii::$app->urlManagerFrontend->baseUrl . '/termin_anzeigen';
            $menuItems = [
                [
                    'label' => 'Admin',
                    'items' => [
                        ['label' => 'Menüoptionen im Backend/Adminbereich', 'url' => ['/site/index']],
                        '<li class="divider"></li>',
                        ['label' => 'Immobilien', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Neue Immobilie anlegen', 'url' => ['/immobilien/decide']],
                                ['label' => 'Immobilien aufrufen', 'url' => ['/immobilien/index']],
                            ],
                        ],
                        '<li class="divider"></li>',
                        ['label' => 'Bankverbindungen',
                            'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Bankdaten aufrufen', 'url' => ['/bankverbindung/index']],
                                ['label' => 'Bankdaten anlegen', 'url' => ['/bankverbindung/select']],
                            ],
                        ],
                        '<li class="divider"></li>',
                        ['label' => 'Kunden', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Kundendaten abrufen(ToDo)', 'url' => ['/site/index']]
                            ],
                        ],
                        '<li class="divider"></li>',
                        ['label' => 'Termine', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Besichtigungstermine abrufen', 'url' => $link],
                            ],
                        ],
                        '<li class="divider"></li>',
                        ['label' => 'User',
                            'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'neuen Benutzer anlegen', 'url' => ['/site/signup']],
                                ['label' => 'Benutzer löschen', 'url' => ['/site/deluser']],
                                ['label' => 'Benutzer anzeigen', 'url' => ['/site/showuser']],
                            ],
                        ],
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
