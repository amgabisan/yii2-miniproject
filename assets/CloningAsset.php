<?php

namespace app\assets;

use yii\web\AssetBundle;


class CloningAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/jquery.sheepItPlugin-1.1.1.min.js',
        'js/cloning.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
