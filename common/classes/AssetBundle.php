<?php

/**
 * AssetBundle.php
 * @author Thomas Kipp
 * @link http://tklustig.ddns.net
 *  * Diese Klasse implementiert den Zugriff auf diverse CSS und Jquery-Dateien,welche sich im Ordner ./web/. befinden
 */

namespace common\classes;

class AssetBundle extends \yii\web\AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init() {
        parent::init();

        $this->publishOptions['beforeCopy'] = function ($from, $to) {
            return preg_match('%(/|\\\\)(fonts|css)%', $from);
        };
    }

}
