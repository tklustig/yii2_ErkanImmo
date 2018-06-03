<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div id="Wrapper">
    <div id="Header_wrapper" >
        <header id="Header">
            <div class="header_placeholder"></div>
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
                                            <?= Html::a(Yii::t('app', 'Kontak'), ['/site/contact']) ?>
                                        </li>
                                        <li id="menu-item-50" class="menu-item menu-item-type-post_type menu-item-object-page">
                                            <?= Html::a(Yii::t('app', 'Angebote einsehen'), ['/site/index']) ?>
                                        </li>
                                        <li id="menu-item-50" class="menu-item menu-item-type-post_type menu-item-object-page">
                                            <?= Html::a(Yii::t('app', 'Angebote erstellen(Backend)'), ['/site/index']) ?>
                                        </li>
                                    </ul>
                                </nav>
                                <a class="responsive-menu-toggle " href="#">
                                    <i class="icon-menu-fine"></i>
                                </a>
                            </div>
                            <?php $form = ActiveForm::begin(['id' => 'start-form']); ?>
                            <div><br></div>
                            <?=
                            $form->field($DynamicModel, 'searching')->textInput(['class' => 'field', 'placeholder' => 'Suchbegriff eingeben'])->label(false);
                            ?>
                            <div class="form-group">
                                <?= Html::submitButton('Absenden', ['name' => 'start-button']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
    <div id="Content">
        <div class="content_wrapper clearfix">
            <div class="sections_group">
                <div class="extra_content">
                    <div class="section mcb-section" style="padding-top:180px;padding-bottom:0px; background-color:">
                        <div class="section_wrapper mcb-section-inner">
                            <div class="wrap mcb-wrap one  valign-top clearfix" style="">
                                <div class="mcb-wrap-inner">
                                    <div class="column mcb-column one column_column  column-margin-">
                                        <div class="column_attr clearfix"  style="">
                                            <h6 class="themecolor" style="text-transform: uppercase;">Startseite</h6>
                                            <h2>Kanat Immobilien PG & Co.KG</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section the_content no_content">
                        <div class="section_wrapper">
                            <div class="the_content_wrapper">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section ">
                    <div class="section_wrapper clearfix">
                        <div class="column one column_portfolio">
                            <div class="portfolio_wrapper isotope_wrapper">
                                <ul class="portfolio_group lm_wrapper isotope grid col-2">
                                    <li class="portfolio-item isotope-item  has-thumbnail">
                                        <div class="portfolio-item-fw-bg" style="">
                                            <div class="image_frame scale-with-grid">
                                                <div class="image_wrapper">
                                                    <div class="mask"></div>
                                                    <?= Html::img('@web/img/pic1.jpg', ['alt' => 'PicNotFound', 'class' => 'scale-with-grid wp-post-image', 'style' => 'width:960;height:700']); ?>
                                                    <div class="image_links double">
                                                        <div class="image_links double">
                                                            <?= Html::a('', Yii::getAlias('@web') . '/img/pic1.jpg', ['target' => '_blank', 'alt' => 'PicNotFound', 'class' => 'icon-search']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="portfolio-item isotope-item  has-thumbnail">
                                        <div class="portfolio-item-fw-bg" style="">
                                            <div class="portfolio-item-fill"></div>
                                            <div class="image_frame scale-with-grid">
                                                <div class="image_wrapper">
                                                    <div class="mask"></div>
                                                    <?= Html::img('@web/img/pic2.jpg', ['alt' => 'PicNotFound', 'class' => 'scale-with-grid wp-post-image']); ?>
                                                    <div class="image_links double">
                                                        <?= Html::a('', Yii::getAlias('@web') . '/img/pic2.jpg', ['target' => '_blank', 'alt' => 'PicNotFound', 'class' => 'icon-search']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="portfolio-item isotope-item  has-thumbnail">
                                        <div class="portfolio-item-fw-bg" style="">
                                            <div class="portfolio-item-fill"></div>
                                            <div class="image_frame scale-with-grid">
                                                <div class="image_wrapper">
                                                    <div class="mask"></div>
                                                    <?= Html::img('@web/img/pic3.jpg', ['alt' => 'PicNotFound', 'class' => 'scale-with-grid wp-post-image']); ?>
                                                    <div class="image_links double">
                                                        <?= Html::a('', Yii::getAlias('@web') . '/img/pic2.jpg', ['target' => '_blank', 'alt' => 'PicNotFound', 'class' => 'icon-search']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                    </li>
                                    <li class="portfolio-item isotope-item  has-thumbnail">
                                        <div class="portfolio-item-fw-bg" style="">
                                            <div class="portfolio-item-fill"></div>
                                            <div class="image_frame scale-with-grid">
                                                <div class="image_wrapper">
                                                    <div class="mask"></div>
                                                    <?= Html::img('@web/img/pic4.jpg', ['alt' => 'PicNotFound', 'class' => 'scale-with-grid wp-post-image']); ?>
                                                    <div class="image_links double">
                                                        <?= Html::a('', Yii::getAlias('@web') . '/img/pic4.jpg', ['target' => '_blank', 'alt' => 'PicNotFound', 'class' => 'icon-search']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer id="Footer" class="clearfix">
        <div class="widgets_wrapper" style="padding:0 0 30px;">
            <div class="container">
                <div class="column one">
                    <aside id="text-2" class="widget widget_text">
                        <div class="textwidget">
                            <div style="position: relative; margin-top: -100px; z-index: 1;">
                                <div class="image_frame image_item no_link scale-with-grid aligncenter no_border" >
                                    <div class="image_wrapper">
                                        <?= Html::img('@web/img/erkan_logo.jpg', ['alt' => 'PicNotFound', 'class' => 'scale-with-grid', 'style' => 'width:350px;height:150px;']); ?>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: center; margin: 50px 20%;">
                                <h4 style="color: #9e7b4e;">Sie suchen neuen Wohnraum?<br> Wie bieten Wohnobjekte aller Art!</h4>
                                <hr class="no_line" style="margin: 0 auto 15px;">
                                <p> Vom 1-Zimmer-Appartement für Studenten bis hin zu Luxus-Villas für gehobenere Ansprüche: Kanat Immobilien ist der ideale Vermittlungsmarkler.  <?= Html::a(Yii::t('app', 'Kontaktieren'), ['/site/contact']) ?> Sie uns<p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
        <div class="footer_copy">
            <div class="container">
                <div class="column one">
                    <div class="copyright"> &copy; 2018 Kanat Immobilien. All Rights Reserved.
                    </div>
                    <ul class="social"></ul>
                </div>
            </div>
        </div>
    </footer>
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


