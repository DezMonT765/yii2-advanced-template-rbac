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
use yii\rbac\Item;

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

    public static function getRoleHierarchy()
    {
        if(self::$role_hierarchy === null)
        {
            $auth = Yii::$app->authManager;
            self::$roles = self::$roles === null ? $auth->getRoles() : self::$roles;
            foreach ( self::$roles as $role)
            {
                self::$role_hierarchy[$role->name] = !isset(self::$role_hierarchy[$role->name]) ? null : self::$role_hierarchy[$role->name];
                foreach ($auth->getChildren($role->name) as $child_role)
                {
                    if($child_role->type == Item::TYPE_ROLE)
                    {
                        self::$role_hierarchy[$child_role->name][$role->name] = $role->name;
                    }
                }
            }
        }

        return self::$role_hierarchy;
    }

    public static function getRoleHierarchyDesc()
    {
        if(self::$role_hierarchy_desc === null){
            $auth = Yii::$app->authManager;
            self::$roles = self::$roles === null ? $auth->getRoles() : self::$roles;
            foreach (self::$roles as $role)
            {
                self::$role_hierarchy_desc[$role->name] = [];
                $children = $auth->getChildren($role->name);
                foreach ($children as $child_role)
                {
                    if($child_role->type == Item::TYPE_ROLE)
                    {
                        self::$role_hierarchy_desc[$role->name][$child_role->name] = $child_role->name;
                    }
                }
            }
        }
        return self::$role_hierarchy_desc;
    }


    public static   function  generateRoleCondition($role,$checking_role)
    {

        $parent_role = isset(RbacController::getRoleHierarchy()[$role]) || array_key_exists($role,RbacController::getRoleHierarchy()) ?  RbacController::getRoleHierarchy()[$role] : null;

        if(!is_null($parent_role))
        {
            if(is_array($parent_role))
            {
                $condition = false;
                foreach ($parent_role as $c_parent_role)
                {
                    $condition = $condition || self::generateRoleCondition($c_parent_role, $checking_role);
                    $condition = $condition || $role == $checking_role;
                }
            }
            else
            {
                $condition = self::generateRoleCondition($parent_role, $checking_role);
                $condition = $condition || $role == $checking_role;
            }

        }
        else
        {
            $condition = $role == $checking_role;
        }

        return $condition;
    }

    public static function getEditableRoles()
    {
        if(self::$editable_roles === null)
        {
            foreach (self::getRoleHierarchyDesc() as $role => $children)
            {
                self::$editable_roles[$role] = self::getChildrenRecursively($role);
            }
        }
        return  self::$editable_roles;
    }

    public static function getChildrenRecursively($role)
    {
        $child_roles = isset(self::getRoleHierarchyDesc()[$role]) ? self::getRoleHierarchyDesc()[$role] : [];
        foreach(self::getRoleHierarchyDesc()[$role] as $child_role)
        {
            $child_roles = array_merge($child_roles,self::getChildrenRecursively($child_role));
        }
        return $child_roles;
    }

    public static $role_hierarchy = null;
    public static $role_hierarchy_desc = null;
    public static $editable_roles = null;
    public static $roles = null;

}