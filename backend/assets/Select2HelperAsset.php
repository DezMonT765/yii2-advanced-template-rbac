<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use common\components\MainView;
use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap css files.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Select2HelperAsset extends AssetBundle
{
    public $baseUrl = '@web';
    public $basePath = '@webroot';
    public $js = [
        'libs/initSelect2.js',
    ];

    public $jsOptions = [
      'position' => MainView::POS_HEAD
    ];
}
