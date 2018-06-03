<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\classes;

use yii\web\AssetBundle as BaseFrontendAsset;

/**
 * Description of KontaktAsset
 *
 * @author tklustig
 */
class KontaktAsset extends BaseFrontendAsset {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/base.css',
        'css/layout.css',
        'css/shortcodes.css',
        'css/style.css'
    ];
    public $js = [
    ];

}
