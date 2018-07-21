<?php
namespace app\assets;

use yii\web\AssetBundle;

class DatatablesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css',
        'https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css'
    ];
    public $js = [
        'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
        'https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js',
        'https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js',
        'https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js',
        'js/datatables.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
