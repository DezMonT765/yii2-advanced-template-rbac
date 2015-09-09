<?php
namespace backend\filters;
use common\filters\LayoutFilter;
use common\models\User;
use console\controllers\RbacController;
use Yii;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 17:22
 * @method static login()
 * @method static register()
 * @method static users()
 * @method static templates()
 */

class AdminLayout extends LayoutFilter
{
    const place_left_nav = 'left_nav';
    const place_right_nav = 'right_nav';
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
            case RbacController::admin : $nav_bar = [
                self::place_left_nav => static::getLeftTabs($active),
                self::place_right_nav => static::getRightNav($active),
            ];
                break;
            case RbacController::super_admin : $nav_bar = [
                self::place_left_nav => static::getSuperAdminLeftTabs($active),
                self::place_right_nav => static::getRightNav($active),
            ];
        }

        return $nav_bar;
    }

    public static function getGuestRightNav($active)
    {
        return [
            ['label'=>Yii::t('header','Login'),'url'=>Url::to(['site/login']),'active'=>self::getActive($active,self::login())],
        ];
    }

    public static function getLeftTabs($active)
    {
        $tabs = [
            ['label'=>Yii::t('header','Manage users'),'url'=>Url::to(['user/list']),'active'=>self::getActive($active,self::users())]
        ];

        return $tabs;
    }

    public static function getSuperAdminLeftTabs($active)
    {
        $tabs = [
            ['label' => 'Manage users', 'url' => Url::to(['user/list']), 'active' => self::getActive($active, AdminLayout::users())],
            ['label' => 'Manage templates', 'url' => Url::to(['mail-template/list']), 'active' => self::getActive($active, AdminLayout::templates())]

        ];
        return $tabs;
    }

    public static function getRightNav()
    {
        /**@var User $user*/
        $user = Yii::$app->user->identity;
        return [
            ['label'=>Yii::t('header','Hello, '). $user->email,'items'=>[
                ['label'=>Yii::t('header','My profile'),'url'=>Url::to(['user/view','id'=> $user->id])],
                ['label'=>Yii::t('header','Log out'),'url'=>['site/logout']]
            ]],
        ];
    }
}