<?php

namespace common\classes;

use yii\base\Exception;
use yii\web\AssetBundle as BaseAdminLteAsset;

class AdminLteAsset extends BaseAdminLteAsset {
    /*
     * AdminLTEAsset.php
     * @author Thomas Kipp
     *  * Description of AdminLteAsset
     * @link http://tklustig.ddns.net
     * Diese Klasse implementiert den Zugriff auf diverse CSS und Jquery-Dateien,welche sich im Ordner ./web/. befinden
     */

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/AdminLTE.min.css',
    ];
    public $js = [
        'js/adminlte.js'
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
