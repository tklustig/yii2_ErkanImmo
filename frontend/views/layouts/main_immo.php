<?php

use yii\helpers\Html;
use common\classes\AdminLteAsset;

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
        <div class="wrapper">
            <?= $content ?>
        </div>
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>

