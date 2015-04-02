<?php
namespace backend\filters;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 18:50
 * @method static place_tabs()
 * @method static manage()
 * @method static update()
 * @method static create()
 * @method static view()
 */

class TabbedLayout extends AdminLayout
{
    const place_tabs = 'tabs';
    public $layout = 'tabbedLayout';
    public static function getActiveMap()
    {

        return [
            'list' => [static::manage()],
            'create' => [static::create()],
            'update' => [static::update()],
            'view' => [static::view()],
        ];
    }

    public static function layout(array $active = [])
    {
        $nav_bar = parent::layout($active);
        $nav_bar[self::place_tabs] = static::getTabs($active);
        return $nav_bar;
    }

    public static function getTabs(array $active = [])
    {
        $tabs = [

        ];
        return $tabs;
    }
}