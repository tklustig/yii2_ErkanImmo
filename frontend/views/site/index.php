<?php

use yii\helpers\Html;
use yii\web\Session;
use common\widgets\Alert;
?>
<?php
$alert = new Alert();
$alert->run();
$link = \Yii::$app->urlManagerBackend->baseUrl . '/login';
//Hier werden alle Flashnachrichten ausgegeben
$session = Yii::$app->session;
$session->getAllFlashes();
$this->title = Yii::t('app', 'Angebote');
if (!empty($session)) {
    foreach ($session->getAllFlashes() as $flash) {
        foreach ($flash as $ausgabe) {
            ?><?=
            Alert::widget([
                'type' => Alert::TYPE_SUCCESS,
                'title' => 'Information',
                'icon' => 'glyphicon glyphicon-exclamation-sign',
                'body' => $ausgabe,
                'showSeparator' => true,
                'delay' => false
            ]);
        }
    }
}
?>
<div id="Header_wrapper" >
    <header id="Header">
        <div id="Top_bar" class="loading">
            <div class="container">
                <div class="column one">
                    <div class="top_bar_left clearfix">
                        <div class="menu_wrapper">
                            <nav id="menu">
                                <ul id="menu-main-menu" class="menu menu-main">
                                    <li id="menu-item-1" class="menu-item menu-item-type-post_type menu-item-object-page">
                                    </li>
                                    <li id="menu-item-50" class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a(Yii::t('app', 'Angebote einsehen'), ['/immobilien/preview']) ?>
                                    </li>
                                    <li id="menu-item-50" class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a('Angebote erstellen(Backend)', $link, ['class' => 'fa fa-gear', 'title' => 'Switch to Backend']) ?>
                                    </li>
                                    <li id="menu-item-50" class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a(Yii::t('app', 'Impressum'), ['/site/about']) ?>
                                    </li>
                                    <li id="menu-item-50" class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a(Yii::t('app', 'Kontakt'), ['/site/contact']) ?>
                                    </li>
                                </ul>
                            </nav>
                            <a class="responsive-menu-toggle " href="#">
                                <i class="icon-menu-fine"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
<?php
$url = Yii::getAlias('@web') . '/img/';
?>
<div style="background:url(<?= $url ?>Theme.jpg);  background-repeat: repeat-y;  background-size: 100% 100%;">
    <div class="section_wrapper mcb-section-inner">
        <div class="wrap mcb-wrap one  valign-top clearfix" style="">
            <div class="column mcb-column one column_column  column-margin-">
                <div class="column_attr clearfix"  style="">
                    <h6 class="themecolor" style="text-transform: uppercase;">Menu</h6>
                    <script>
                        var myWidth = 0;
                        if (typeof (window.innerWidth) == 'number') {
                            myWidth = window.innerWidth;
                        }
                        if (myWidth >= 1680) {
                            document.write("<br><br><br>");
                        } else if (myWidth >= 1920) {
                            document.write("<br><br><br><br><br>");
                        }
                    </script>
                </div>
            </div>
        </div>
        <div class="column one">
            <!--   <div style="position: relative; margin-top: -100px; z-index: 1;">
                   <div class="image_frame image_item no_link scale-with-grid aligncenter no_border" >
                       <div class="image_wrapper">
            <?php //echo Html::img('@web/img/erkan_logo.jpg', ['alt' => 'PicNotFound', 'class' => 'scale-with-grid', 'style' => 'width:350px;height:150px;']); ?>
                       </div>
                   </div>
               </div>-->
            <div style="text-align: center; margin: 7% 20%;">
                <h4 style="color: #FF0040;">Sie suchen neuen Wohnraum?<br> Wie bieten Wohnobjekte aller Art!</h4></div>
            <div style="text-align: left; margin: 13% 23%;">
                <p style="color:#0404B4;font-family:cursive"> Vom 1-Zimmer-Appartement für Studenten bis hin zu<br>Luxus-Villas für gehobenere Ansprüche:<br>Kanat Immobilien ist der ideale Vermittlungsmarkler.<br><?= Html::a(Yii::t('app', 'Kontaktieren'), ['/site/contact'], ['style' => 'color:#088A08;']) ?> Sie uns, oder inspizieren Sie unsere <?= Html::a(Yii::t('app', 'Angebote'), ['/immobilien/preview'], ['style' => 'color:#088A08;']) ?> <p>
            </div>


        </div>
        <div style="text-align: center; margin: 7% 26%;" class="column">
            <div class="copyright"> &copy; 2018 Kanat Immobilien. All Rights Reserved.
            </div>
        </div>
    </div>
</div>






<div id="Side_slide" class="right dark" data-width="250">
    <div class="close-wrapper"><a href="#" class="close">
            <i class="icon-cancel-fine">
            </i>
        </a>
    </div>
    <div class="extras">
        <div class="extras-wrapper"></div>
    </div>
    <div class="lang-wrapper"></div>
    <div class="menu_wrapper"></div>
    <ul class="social"></ul>
</div>
<div id="body_overlay"></div>


