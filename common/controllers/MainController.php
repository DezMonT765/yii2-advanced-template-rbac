<?php
namespace common\controllers;
use MainActiveRecord;
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
            throw new NotFoundHttpException(Yii::t('messages','The requested page does not exist.'));
        }
    }





    public function selectionList($model_class,$attribute,callable $return_wrap = null)
    {
        /** @var MainActiveRecord $model_class
          * @var MainActiveRecord $model
         */
        $value = Yii::$app->request->getQueryParam('value');
        $model = new $model_class;
        $models = $model->searchByAttribute($attribute,$value);
        $model_array = [];
        foreach ($models as $model)
        {
            $model_array[] =['id'=>$model->id,'text'=> is_null($return_wrap) ? $model->name : $return_wrap($model) ];
        }
        echo json_encode(['more'=>false,'results'=>$model_array]);
    }

    public function selectionById($model_class,callable $return_wrap = null)
    {
        /** @var MainActiveRecord $model_class
         *  @var MainActiveRecord $model
         */
        $id = Yii::$app->request->getQueryParam('id');
        $model = new $model_class;
        $ids = explode(',',$id);
        $models = $model->searchByIds($ids);
        $model_array = [];
        if(count($models) == 1)
        {
            $model = array_shift($models);
            $model_array = ['id'=>$model->id,'text'=> is_null($return_wrap) ? $model->name : $return_wrap($model)];
        }
        else
        {
            foreach ($models as $model)
            {
                $model_array[] =['id'=>$model->id,'text'=> is_null($return_wrap) ? $model->name : $return_wrap($model)];
            }
        }
        echo json_encode(['more'=>false,'results'=>$model_array]);
    }
}