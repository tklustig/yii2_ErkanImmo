<?php

use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
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
