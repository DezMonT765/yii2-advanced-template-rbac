<?php
namespace common\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 24.03.2015
 * Time: 12:47
 */

class MainController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
            ]
        ];
    }
    public $activeMap = [];

    public function getTabsActivity()
    {
        return isset($this->activeMap[$this->action->id]) ? $this->activeMap[$this->action->id] : [];
    }

    public static  function checkAccess($permission,array $params = [])
    {
        if(Yii::$app->user->can($permission,$params))
            return true;
        else
        {
            if (Yii::$app->user->getIsGuest()) {
                Yii::$app->user->loginRequired();
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }

    }


    /**
     * @param $model_class
     * @param $id
     * @throws NotFoundHttpException
     * @return mixed  $model
     */
    protected function findModel($model_class,$id)
    {
        if (($model = $model_class::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}