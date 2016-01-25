<?php
namespace frontend\controllers;

use common\components\Alert;
use common\components\ParamsGetter;
use common\controllers\MainController;
use console\controllers\RbacController;
use frontend\filters\SiteLayout;
use frontend\models\PasswordChangeForm;
use frontend\models\PasswordResetForm;
use Yii;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\RegisterForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\log\Logger;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends MainController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
       $behaviors =  [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['test'],
                'rules' => [
                    [
                        'actions' => ['register','login','index','password-change','password-reset','contact','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index','error','contact','captcha'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
        $behaviors['layout'] =  ['class' => SiteLayout::className()];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * @return array|Response
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login',[
                      'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save())
            {
                Alert::addSuccess('Thank you for contacting us. We will respond to you as soon as possible.');
                return $this->redirect(['/']);
            }
            else
            {
                Alert::addError('There was an error sending email.');
                Yii::getLogger()->log($model->errors,Logger::LEVEL_ERROR);
            }
        }
        else
            return $this->render('contact',['model'=>$model]);

    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->register()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionPasswordReset()
    {
        $model = new PasswordResetForm();
        if(Yii::$app->user->isGuest)
        {
            if($model->load(Yii::$app->request->post()) && $model->validate())
            {
                $model->sendEmail();
                Alert::addSuccess('Thank you. If the email address you entered matches with one that is registered in our system we will send you a reset link within the next few minutes.');
                return $this->goHome();
            }
            else
            {
                return $this->render('password-reset', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            $model->email = Yii::$app->user->identity->email;
        }
        if($model->validate())
        {
            $model->sendEmail();
            Alert::addSuccess('Thank you. If the email address you entered matches with one that is registered in our system we will send you a reset link within the next few minutes.');
            return $this->goHome();
        }
        else
        {
            return $this->render('password-reset', [
                'model' => $model,
            ]);
        }
    }





    public function actionPasswordChange($token)
    {
        try
        {
            $model = new PasswordChangeForm($token);
        }
        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException('Wrong password reset token.');
        }
        if($model->load(Yii::$app->request->post()))
        {
            if($model->validate() && $model->changePassword())
            {
                Alert::addSuccess('New password was saved.');
                return $this->goHome();
            }
            else
            {
                Alert::addError('Password hasn\'t been saved.');
                return $this->goHome();
            }
        }
        return $this->render('password-change', [
            'model' => $model,
        ]);
    }




}
