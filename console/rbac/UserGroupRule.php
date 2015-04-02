<?php
namespace console\rbac;

use console\controllers\RbacController;
use common\models\User;
use Yii;
use yii\rbac\Rule;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 31.03.2015
 * Time: 12:23
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        /**@var User $current_user
         */
        $current_user = Yii::$app->user->identity;
        if (!Yii::$app->user->isGuest) {
            $role = $current_user->role;
            if(isset(RbacController::$role_hierarchy[$item->name]) || array_key_exists($item->name,RbacController::$role_hierarchy))
                return self::generateRoleCondition($item->name,$role);
        }
        return false;
    }

    protected  function  generateRoleCondition($role,$checking_role)
    {

            $parent_role = isset(RbacController::$role_hierarchy[$role]) || array_key_exists($role,RbacController::$role_hierarchy) ?  RbacController::$role_hierarchy[$role] : null;

            if(!is_null($parent_role))
            {
                $condition = self::generateRoleCondition($parent_role, $checking_role);
                $condition = $condition || $role == $checking_role;
            }
            else
            {
                $condition = $role == $checking_role;
            }

        return $condition;
    }
}