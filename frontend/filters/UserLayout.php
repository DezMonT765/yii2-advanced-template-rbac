<?php
namespace frontend\filters;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 01.04.2015
 * Time: 19:10
 */

class UserLayout extends TabbedLayout
{
    public static  function layout(array $active = [])
    {
        $active = array_merge($active,[SiteLayout::profile()]);
        $nav_bar = parent::layout($active);
        return $nav_bar;
    }

    public static function getTabs(array $active = [])
    {

        $tabs = [
            ['label'=>'My profile',
             'url'=>Url::to(['user/view'] + self::getParams()),
             'active'=>true
            ]
        ];

        return $tabs;
    }
}