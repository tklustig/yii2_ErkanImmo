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
            $link_ = \Yii::$app->urlManagerFrontend->baseUrl . '/termin_selektieren';
            $menuItems = [
                [
                    'label' => 'Admin',
                    'items' => [
                        ['label' => 'Frontend Bilder initialisieren', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Theme hochladen', 'url' => ['/site/create']],
                                ['label' => 'Theme initialiseren', 'url' => ['/site/show']],
                                ['label' => 'Theme löschen', 'url' => ['/site/deletion']],
                            ],
                        ],
                        ['label' => '+++++++Impressum initialiseren+++++++', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Begriffe festlegen', 'url' => ['/begriffe/index']],
                                ['label' => 'Theme initialisieren', 'url' => ['/site/initialize']],
                            ],
                        ],
                        ['label' => '+++++++Rechnungen+++++++', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Rechnung anzeigen/drucken/bearbeiten', 'url' => ['/rechnung/index']],
                                ['label' => 'Rechnung erstellen', 'url' => ['/rechnung/create']],
                                ['label' => 'Rechnungsrumpf bearbeiten', 'url' => ['/kopf/index']],
                                ['label' => 'Rechnungsart bearbeiten', 'url' => ['/rechnungsart/index']],
                            ],
                        ],
                        ['label' => '++++++++++Immobilien+++++++++++', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Neue Immobilie anlegen', 'url' => ['/immobilien/decide']],
                                ['label' => 'Immobilien aufrufen', 'url' => ['/immobilien/index']],
                            ],
                        ],
                        ['label' => '++++++++++++Bankverbindungen+++++++++++',
                            'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Bankdaten aufrufen', 'url' => ['/bankverbindung/index']],
                                ['label' => 'Bankdaten anlegen', 'url' => ['/bankverbindung/select']],
                            ],
                        ],
                        ['label' => '+++++++Kunden++++++++', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Alle Kundendaten abrufen', 'url' => ['/kunde/index']],
                            ],
                        ],
                        ['label' => '++++++++Mails++++++++', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Mailserver konfigurieren', 'url' => ['/mailserver/index']],
                                ['label' => 'Mails anzeigen/verschicken', 'url' => ['/mail/index']],
                                ['label' => 'Textbausteine editieren', 'url' => ['/textbaustein/index']]
                            ],
                        ],
                        ['label' => '+++++++Termine++++++', 'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'Alle Besichtigungstermine abrufen', 'url' => $link],
                                ['label' => 'Besichtigungstermine nach/je Makler abrufen', 'url' => $link_],
                            ],
                        ],
                        ['label' => '++++++User und Firmendaten++++',
                            'url' => ['#'],
                            'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                            'items' => [
                                ['label' => 'neuen Benutzer anlegen', 'url' => ['/site/signup']],
                                ['label' => 'Benutzer löschen', 'url' => ['/site/deluser']],
                                ['label' => 'Benutzer anzeigen', 'url' => ['/site/showuser']],
                                ['label' => 'Firmendaten pflegen', 'url' => ['/firma/index']],
                            ],
                        ],
                        ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']]
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
