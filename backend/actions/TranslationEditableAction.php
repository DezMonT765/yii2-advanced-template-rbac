<?php
/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 25.03.2015
 * Time: 16:53
 */
namespace backend\actions;

use console\controllers\RbacController;
use dosamigos\editable\EditableAction;
use Yii;

class TranslationEditableAction extends  EditableAction
{
    public function beforeRun()
    {
        $class = $this->modelClass;
        $pk = Yii::$app->request->post('pk');
        $pk = unserialize(base64_decode($pk));
        $model = $class::findOne($pk);
        return $this->controller->checkAccess(RbacController::super_admin);
    }
}