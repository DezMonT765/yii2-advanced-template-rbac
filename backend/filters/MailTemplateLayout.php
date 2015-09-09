<?php
namespace backend\filters;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 23.04.2015
 * Time: 14:55
 */

class MailTemplateLayout extends TabbedLayout
{
    public static  function layout(array $active =[])
    {
        $active = array_merge($active,[AdminLayout::templates()]);
        $nav_bar = parent::layout($active);
        return $nav_bar;
    }

    public static  function  getTabs(array $active = [])
    {

        $tabs = [
            ['label'=>'List templates','url'=>Url::to(['mail-template/list']),'active'=>self::getActive($active,TabbedLayout::listing())],
            ['label'=>'Create template','url'=>Url::to(['mail-template/create']),'active'=>self::getActive($active,TabbedLayout::create())],
        ];
        if(self::getActive($active,TabbedLayout::update()))
        {
            $tabs =  array_merge($tabs,[
                ['label'=>'Update template',
                 'url'=>Url::to(['mail-template/update'] + self::getParams()),
                 'active'=>true
                ]
            ]);
        }
        if(self::getActive($active,TabbedLayout::view()))
        {
            $tabs =  array_merge($tabs,[
                ['label'=>'View user',
                 'url'=>Url::to(['mail-template/view'] + self::getParams()),
                 'active'=>true
                ]
            ]);
        }
        return $tabs;
    }
}