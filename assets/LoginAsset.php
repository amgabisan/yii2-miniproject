<?php

namespace app\assets;

use yii\web\AssetBundle;


class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/login.css',
        'https://fonts.googleapis.com/css?family=Montserrat',
        'https://use.fontawesome.com/releases/v5.0.13/css/all.css'
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
