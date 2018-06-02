<?php

namespace common\classes;

//namespace common\wsl_components;

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
        'js/jquery/jquery.js',
        'js/plugins.js',
        'js/menu.js',
        'js/scripts.js'
    ];
    public $depends = [
        'common\classes\AssetBundle',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    public $skin = '_all-skins';

    public function init() {
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }

            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }

        parent::init();
    }

}
?>

