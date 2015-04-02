<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 19:07
 */
namespace backend\filters;

use yii\helpers\Url;

class UserLayout extends TabbedLayout
{

    public static  function layout(array $active = [])
    {
        $active = array_merge($active,[self::users()]);
        $nav_bar = parent::layout($active);
        return $nav_bar;
    }

    public static function getTabs(array $active = [])
    {

        $tabs = [
            ['label'=>'List users','url'=>Url::to(['user/list']),'active'=>self::getActive($active,self::manage())],
            ['label'=>'Create user','url'=>Url::to(['user/create']),'active'=>self::getActive($active,self::create())],
        ];
        if(self::getActive($active,self::update()))
        {
            $tabs =  array_merge($tabs,[
                ['label'=>'Update user',
                 'url'=>Url::to(['user/update'] + self::getParams()),
                 'active'=>true
                ]
            ]);
        }
        if(self::getActive($active,self::view()))
        {
            $tabs =  array_merge($tabs,[
                ['label'=>'View user',
                 'url'=>Url::to(['user/view'] + self::getParams()),
                 'active'=>true
                ]
            ]);
        }
        return $tabs;
    }
}