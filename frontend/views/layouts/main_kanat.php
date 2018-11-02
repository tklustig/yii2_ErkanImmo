<?php

use yii\helpers\Html;

//use common\classes\KontaktAsset;
?>

<?php
\common\classes\FrontendAsset::register($this);
?>
<!-- Hauptseite: yii2-Adaption by Thomas K. -->
<?php $this->beginPage() ?>
<!Doctype html> <!-- Definition des doctype-Modus -->
<html> <!-- Definition des Stammverzeichnises -->
    <head> <!-- Definition des Kopfbereiches -->
        <meta charset="utf-8"> <!-- charset[utf-8:]  definiert den deutschen Zeichensatz -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <!-- Dieser JS-Code sorgt für die Animierung des Menus -->
        <script id="mfn-dnmc-config-js">window.mfn = {mobile_init: 1240, nicescroll: 40, parallax: "translate3d", responsive: 1, retina_js: 0};
            window.mfn_lightbox = {disable: false, disableMobile: false, title: false, };
            window.mfn_sliders = {blog: 0, clients: 0, offer: 0, portfolio: 0, shop: 0, slider: 0, testimonials: 0};
        </script>
        <?php $this->head() ?>
    </head>
    <!-- Die CSS-Klasse button-flat sorgt für: die richtige Darstelltung des Submitbuttons -->
    <!-- Die CSS-Klasse header-simple sorgt für:die richtige Darstellung des Logo-Containerbereiches -->
    <!-- Die CSS-Klasse minimalist-header-no sorgt für:die richtige Darstellung des oberen Containerbereiches(und für das responsive Design) -->
    <!-- Die CSS-Klasse mobile-side-slide sorgt für:die richtige Darstellung des Menus -->
    <body class="button-flat header-simple  minimalist-header-no mobile-side-slide">
        <?php $this->beginBody() ?>
        <?= $content ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

