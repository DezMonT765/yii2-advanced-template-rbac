<?php
namespace common\components;
use yii\base\Behavior;
use yii\base\Exception;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 18.03.2015
 * Time: 14:18
 * @property xlsImport $owner
 */

class xlsSaveBehavior extends Behavior
{
    public function saveByMode($model)
    {
        try
        {
            $model_class = $this->owner->modelName;
            $old_model = $this->owner->findOldModel($model);
            if($old_model instanceof $model_class)
            {
                if($this->owner->isUpdate())
                {
                    $old_model->scenario = xlsImport::XLS_IMPORT;
                    $old_model->setAttributes($model->getAttributes($model->safeAttributes()));
                    $this->owner->saveModel($old_model);
                }
                else
                {
                    $this->owner->warnings[] = 'Object with id = '.$old_model->id . ' already exist';
                }
            }
            else
            {
                $this->owner->saveModel($model);
            }
        }
        catch(Exception $e)
        {
            $this->owner->errors[] = $e->getMessage();
        }
    }

    public static  function getClass()
    {
        return get_called_class();
    }
}