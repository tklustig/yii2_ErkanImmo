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
                    $menuItems = [
                        [
                            'label' => 'Admin',
                            'items' => [
                                ['label' => 'zur Backendübersicht', 'url' => ['/site/index']],
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
                                        ['label' => 'Aufrufen', 'url' => ['/bankverbindung/index']],
                                        ['label' => 'Anlegen', 'url' => ['/bankverbindung/select']],
                                    ],
                                ],
                                '<li class="divider"></li>',
                                ['label' => 'Kundendaten abrufen', 'url' => ['#']],
                                '<li class="divider"></li>',
                                ['label' => 'Neuen Benutzer anlegen', 'url' => ['/site/signup']],
                                '<li class="divider"></li>',
                                ['label' => 'Benutzer löschen', 'url' => ['/site/deluser']],
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
                    <?= $content ?>
                </div>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
