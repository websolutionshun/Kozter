<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle with Tabler.io integration
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://unpkg.com/@tabler/core@1.4.0/dist/css/tabler.min.css',
        'css/site.css',
    ];
    public $js = [
        'https://unpkg.com/@tabler/core@1.4.0/dist/js/tabler.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
