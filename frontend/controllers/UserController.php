<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 01.04.2015
 * Time: 18:58
 */
namespace frontend\controllers;
use dezmont765\yii2bundle\controllers\MainController;
use frontend\filters\UserLayout;
use common\models\User;
use console\controllers\RbacController;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

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
        return $this->render('user-view', [
            'model' => $model,
        ]);
    }

    /**
     * @throws HttpException
     * check users by received code, and if everything is ok, marks them as verified
     */
    public function actionVerifyEmail()
    {
        $code = Yii::$app->request->getQueryParam('code');
        if($code)
        {
            $user = User::findOne(['email_verification_code'=>$code]);
            if(!($user instanceof User))
            {
                throw new NotFoundHttpException(Yii::t('messages','Invalid code'));
            }
            $user->email_verification_status = User::EMAIL_VERIFIED;
            if(!$user->save())
            {
                throw new ServerErrorHttpException(Yii::t('messages','Something goes wrong. Please contact us, or try again later.'));
            }
            $user->sendWelcomeMail();

            return $this->goHome();

        }
        else
        {
            throw new BadRequestHttpException('Invalid code');
        }
    }

    public function actionGetVerificationMail()
    {
        /**@var User $user*/
        $user = Yii::$app->user->identity;
        if($user->renewVerificationCode())
        {
            $user->sendVerificationEmail($user->email_verification_code);
            return $this->redirect(['user/view','id'=>$user->id]);
        }
        else
            return $this->goHome();
    }


}