<?php
namespace backend\filters;
use Yii;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 18:50
 * @method static import()
 */

class TranslationLayout extends TabbedLayout
{

    public static function getActiveMap()
    {
        return array_merge(parent::getActiveMap(),[
           'import' => [TranslationLayout::import()]
        ]);
    }

    public static function layout(array $active = [])
    {
        return parent::layout(array_merge($active,[AdminLayout::translations()]));
    }

    public static function getTabs(array $active = [])
    {
        $tabs = [
            ['label'=>Yii::t('app',':translation_layout_list_translations'),'url'=>Url::to(['translation/list'] + $_GET),'active'=>self::getActive($active,TabbedLayout::listing())],
            ['label'=>Yii::t('app',':translation_layout_import_translations'),'url'=>Url::to(['translation/import'] + $_GET),'active'=>self::getActive($active,TranslationLayout::import())],
        ];
        return $tabs;
    }
}