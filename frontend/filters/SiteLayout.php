<?php
namespace frontend\filters;
use console\controllers\RbacController;
use common\models\User;
use dezmont765\yii2bundle\filters\LayoutFilter;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 13:58
 * @method static login()
 * @method static register()
 * @method static profile()
 * @method static place_left_nav()
 * @method static place_right_nav()
 */


class SiteLayout extends LayoutFilter
{

    const place_left_nav = 'place_left_nav';
    const place_right_nav = 'place_right_nav';

    public static function getActiveMap()
    {

        return [
           'login' => [self::login()],
           'register' => [self::register()]
        ];
    }

    public static  function layout(array $active = [])
    {
        $user_role = static::getRole();
        $nav_bar = [];
        switch($user_role)
        {
            case 'Guest' : $nav_bar = [
                self::place_left_nav =>[],
                self::place_right_nav => static::getGuestRightNav($active),
            ];
                break;
            case RbacController::user : $nav_bar = [
                self::place_left_nav => static::getLeftTabs($active),
                self::place_right_nav => static::getRightNav($active),
            ];
                break;
        }

        return $nav_bar;
    }

    public static function getGuestRightNav(array $active = [])
    {
        return [
            ['label'=>'Log in','url'=>Url::to(['/site/login']),'active'=>self::getActive($active,SiteLayout::login())],
            ['label'=>'Register','url'=>Url::to(['/site/register']),'active'=>self::getActive($active,SiteLayout::register())],
        ];
    }

    public static function getLeftTabs(array $active = [])
    {
        $user = User::getLogged(true);
        $tabs = [
            ['label'=>'My profile','url'=>Url::to(['user/view','id'=>$user->id]),'active'=>self::getActive($active,SiteLayout::profile())]
        ];

        return $tabs;
    }

    public static function getRightNav(array $active = [])
    {
        $user = User::getLogged(true);
        return [

            ['label'=>'Hello, '.$user->email,'items'=>[
                ['label'=>'My profile','url'=>Url::to(['user/view','id'=>$user->id]),'active'=>self::getActive($active,SiteLayout::profile())],
                ['label'=>'Log out','url'=>['site/logout']],
            ]]
        ];
    }
}
