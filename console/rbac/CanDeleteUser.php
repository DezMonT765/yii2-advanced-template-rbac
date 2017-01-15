<?php
namespace console\rbac;

use common\models\User;
use Yii;
use yii\rbac\Rule;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 31.03.2015
 * Time: 14:46
 */
class CanDeleteUser extends Rule
{
    public $name = __CLASS__;


    /**
     * Executes the rule.
     *
     * @param string|integer $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param \yii\rbac\Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[ManagerInterface::checkAccess()]].
     * @return boolean a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params) {
        /**@var User $user */
        $user = Yii::$app->user->identity;
        if($user instanceof User) {
            return (isset($params['model'])) ?
                $user->id == $params['model']->id || $user->canDelete($params['model']->role) : false;
        }
        else return false;
    }
}