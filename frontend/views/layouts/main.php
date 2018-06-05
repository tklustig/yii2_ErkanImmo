<?php

use yii\helpers\Html;
use common\classes\AssetBundle;
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
        </style>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?= $content ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
