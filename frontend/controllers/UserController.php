<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 01.04.2015
 * Time: 18:58
 */
namespace frontend\controllers;
use frontend\filters\UserLayout;
use common\controllers\MainController;
use common\models\User;
use console\controllers\RbacController;
use yii\filters\AccessControl;

class UserController extends MainController
{
    public function behaviors()
    {
        return [
            'access' => [
              'class' => AccessControl::className(),
              'rules'=>[

                  [
                      'allow'=>true,
                      'roles' => ['@']
                  ]
              ]
            ],
            'layout' => UserLayout::className()
        ];
    }
    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel(User::className(),$id);
        self::checkAccess(RbacController::update_profile,['user'=>$model]);
        return $this->render('view', [
            'model' => $model,
        ]);
    }


}