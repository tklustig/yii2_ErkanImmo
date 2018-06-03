<?php

namespace common\classes;

use yii\web\AssetBundle as BaseFrontendAsset;

/**
 * AdminLTEAsset.php
 * @author Thomas Kipp
 * @link http://tklustig.ddns.net
 * Diese Klasse implementiert den Zugriff auf diverse CSS und Jquery-Dateien,welche sich im Ordner ./web/. befinden
 */
class FrontendAsset extends BaseFrontendAsset {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/base.css',
        'css/layout.css',
        'css/shortcodes.css',
        'css/style.css'
    ];
    public $js = [
        'js/jquery/jquery-3.3.1.js',
        'js/jquery/jquery.js',
        'js/plugins.js',
        'js/menu.js',
        'js/scripts.js'
    ];

}
?>

