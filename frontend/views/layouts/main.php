<?php

use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!Doctype html> <!-- Definition des doctype-Modus -->
<html> <!-- Definition des Stammverzeichnises -->
    <head> <!-- Definition des Kopfbereiches -->
        <meta charset="utf-8"> <!-- charset[utf-8:]  definiert den deutschen Zeichensatz -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="page-template-default page page-id-38  color-custom style-default button-flat layout-full-width if-border-hide no-content-padding header-simple minimalist-header-no sticky-header sticky-tb-color ab-hide subheader-both-center menu-link-color footer-copy-center mobile-tb-center mobile-side-slide mobile-mini-mr-ll be-reg-2077>
          <?php $this->beginBody() ?>
          <?= $content ?>
          <?php $this->endBody() ?>
          </body>
          </html>
          <?php $this->endPage() ?>
