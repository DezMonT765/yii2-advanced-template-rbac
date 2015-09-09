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
            if(isset(RbacController::getRoleHierarchy()[$item->name]) || array_key_exists($item->name,RbacController::getRoleHierarchy()))
                return RbacController::generateRoleCondition($item->name,$role);
        }
        return false;
    }


}