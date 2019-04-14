<?php

use yii\helpers\Html;
use common\classes\AssetBundle;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
?>
<?php
AssetBundle::register($this);
?>
<?php $this->beginPage() ?>
<!Doctype html> <!-- Definition des doctype-Modus -->
<html> <!-- Definition des Stammverzeichnises -->
    <head> <!-- Definition des Kopfbereiches -->
        <meta charset="utf-8"> <!-- charset[utf-8:]  definiert den deutschen Zeichensatz -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <style>
            body{
                background-color: #D8D8D8 !important;
            }
            .id_breite_css {
                background-color:#F2F5A9;
            }
        </style>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="container-fluid">
            <div class="jumbotron">
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
                                ['label' => '+++++++Frontend Bilder initialisieren+++++++', 'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'Theme hochladen', 'url' => ['/site/create']],
                                        ['label' => 'Theme initialiseren', 'url' => ['/site/show']],
                                        ['label' => 'Theme löschen', 'url' => ['/site/deletion']],
                                    ],
                                ],
                                '<li class="divider"></li>',
                                ['label' => '+++++++Impressum initialiseren+++++++', 'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'Begriffe festlegen', 'url' => ['/begriffe/index']],
                                        ['label' => 'Theme initialisieren', 'url' => ['/site/initialize']],
                                    ],
                                ],
                                '<li class="divider"></li>',
                                ['label' => '+++++++Rechnungen+++++++', 'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'Rechnung anzeigen/drucken', 'url' => ['/rechnung/index']],
                                        ['label' => 'Rechnung erstellen', 'url' => ['/rechnung/create']],
                                        ['label' => 'Rechnungsrumpf bearbeiten', 'url' => ['/kopf/index']],
                                        ['label' => 'Rechnungsart bearbeiten', 'url' => ['/rechnungsart/index']],
                                    ],
                                ],
                                '<li class="divider"></li>',
                                ['label' => '+++++++Immobilien+++++++', 'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'Neue Immobilie anlegen', 'url' => ['/immobilien/decide']],
                                        ['label' => 'Immobilien aufrufen', 'url' => ['/immobilien/index']],
                                    ],
                                ],
                                '<li class="divider"></li>',
                                ['label' => '+++++++Bankverbindungen+++++++',
                                    'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'Bankdaten aufrufen', 'url' => ['/bankverbindung/index']],
                                        ['label' => 'Bankdaten anlegen', 'url' => ['/bankverbindung/select']],
                                    ],
                                ],
                                '<li class="divider"></li>',
                                ['label' => '+++++++Kunden++++++++(ToComplete:Mail gezielt verschicken,Kundenbild löschen)', 'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'Alle Kundendaten abrufen', 'url' => ['/kunde/index']],
                                    ],
                                ],
                                '<li class="divider"></li>',
                                ['label' => '++++++++Mails+++++++', 'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'Mailserver konfigurieren', 'url' => ['/mailserver/index']],
                                        ['label' => 'Mails anzeigen/verschicken', 'url' => ['/mail/index']]
                                    ],
                                ],
                                '<li class="divider"></li>',
                                ['label' => '+++++++Termine+++++++', 'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'Alle Besichtigungstermine abrufen', 'url' => $link],
                                        ['label' => 'Besichtigungstermine nach/je Makler abrufen', 'url' => $link_],
                                    ],
                                ],
                                '<li class="divider"></li>',
                                ['label' => '++++ User und Firmendaten ++++',
                                    'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'neuen Benutzer anlegen', 'url' => ['/site/signup']],
                                        ['label' => 'Benutzer löschen', 'url' => ['/site/deluser']],
                                        ['label' => 'Benutzer anzeigen', 'url' => ['/site/showuser']],
                                        ['label' => 'Firmendaten pflegen', 'url' => ['/firma/index']],
                                    ],
                                ],
                            ],
                        ],
                    ];

                    $Interna = [
                        [
                            'label' => 'Programmiertools',
                            'items' => [
                                ['label' => 'Basis-Url im Backend/Adminbereich', 'url' => ['/site/index']],
                                '<li class="divider"></li>',
                                ['label' => 'Tools', 'url' => ['#'],
                                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                                    'items' => [
                                        ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                                    ],
                                ],
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
                        'items' => $menuItems
                    ]);
                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-left'],
                        'items' => $Interna
                    ]);
                    NavBar::end();
                    ?>
                    <br><br>
                    <?= $content ?>
                </div>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
