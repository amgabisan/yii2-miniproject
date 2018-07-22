<?php

namespace app\assets;

use yii\web\AssetBundle;


class AlertAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugin/sweetalert/sweetalert.min.css'
    ];
    public $js = [
        'plugin/sweetalert/sweetalert.min.js',
        'js/msgbox.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
