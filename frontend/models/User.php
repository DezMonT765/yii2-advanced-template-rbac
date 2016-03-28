<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 25.03.2015
 * Time: 15:28
 */
namespace frontend\models;
use console\controllers\RbacController;

class User extends \common\models\User
{
    public static function findIdentity($id)
    {
        $query = static::find();
        $query->andFilterWhere(['id'=>$id,'status'=>self::STATUS_ACTIVE,'role'=>RbacController::user,'email_verification_status'=>self::EMAIL_VERIFIED]);
        return $query->one();
    }

    public static function findByEmail($email)
    {
        $query = static::find();
        $query->andFilterWhere(['email'=>$email,'status'=>self::STATUS_ACTIVE,'role'=>RbacController::user,'email_verification_status'=>self::EMAIL_VERIFIED]);
        return $query->one();
    }
}
