<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 12:44
 */

namespace console\controllers;

use console\rbac\CanEdit;
use console\rbac\CanDelete;
use console\rbac\UserGroupRule;
use Yii;
use yii\caching\FileCache;
use yii\console\Controller;
use yii\rbac\DbManager;

class RbacController extends Controller
{
    const super_admin = 'super_admin';
    const admin = 'admin';
    const user = 'user';

    const create_profile = 'create_profile';
    const update_profile = 'update_profile';
    const delete_profile = 'delete_profile';


    public function actionInit()
    {
        /**@var DbManager $auth*/



        $auth = Yii::$app->authManager;
        $auth->cache->cachePath = Yii::getAlias('@backend/runtime/cache');
        $auth->removeAll();
        $auth->invalidateCache();
        $auth->cache->cachePath = Yii::getAlias('@frontend/runtime/cache');
        $auth->invalidateCache();

        $create_profile = $auth->createPermission(self::create_profile);
        $auth->add($create_profile);

        $can_edit = new CanEdit();
        $auth->add($can_edit);

        $update_profile = $auth->createPermission(self::update_profile);
        $update_profile->ruleName = $can_edit->name;
        $auth->add($update_profile);

        $can_delete = new CanDelete();
        $auth->add($can_delete);
        $delete_profile = $auth->createPermission(self::delete_profile);
        $delete_profile->ruleName = $can_delete->name;
        $auth->add($delete_profile);



        $user_group_rule = new UserGroupRule();
        $auth->add($user_group_rule);
        $user = $auth->createRole(self::user);
        $user->ruleName = $user_group_rule->name;
        $auth->add($user);
        $auth->addChild($user,$update_profile);

        $admin = $auth->createRole(self::admin);
        $admin->ruleName = $user_group_rule->name;
        $auth->add($admin);
        $auth->addChild($admin,$user);
        $auth->addChild($admin,$delete_profile);
        $auth->addChild($admin,$create_profile);

        $superAdmin = $auth->createRole(self::super_admin);
        $superAdmin->ruleName = $user_group_rule->name;
        $auth->add($superAdmin);
        $auth->addChild($superAdmin,$admin);

    }

    public static $role_hierarchy = [
        self::user => self::admin,
        self::admin => self::super_admin,
        self::super_admin => null
    ];
}