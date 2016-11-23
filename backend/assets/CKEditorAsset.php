<?php
namespace backend\assets;
use dezmont765\yii2bundle\views\MainView;
use yii\web\AssetBundle;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 23.04.2015
 * Time: 15:56
 */

class CKEditorAsset extends AssetBundle
{
    public $sourcePath = '@bower/';
    public $css = [
    ];
    public $js = [
        'ckeditor/ckeditor.js'
    ];
    public $jsOptions = [
        'position' => MainView::POS_HEAD
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}