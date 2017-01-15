<?php
namespace backend\controllers;

use backend\actions\UserEditableAction;
use backend\filters\UserLayout;
use common\models\User;
use common\models\UserSearch;
use console\controllers\RbacController;
use dezmont765\yii2bundle\actions\CreateAction;
use dezmont765\yii2bundle\actions\DeleteAction;
use dezmont765\yii2bundle\actions\ListAction;
use dezmont765\yii2bundle\actions\UpdateAction;
use dezmont765\yii2bundle\components\Alert;
use dezmont765\yii2bundle\controllers\MainController;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends MainController
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['list', 'create', 'availableGroups'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [RbacController::admin, RbacController::super_admin]
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'layout' => UserLayout::className()
        ];
    }


    public function actions() {
        return [
            'ajaxUpdate' => [
                'class' => UserEditableAction::className(),
                'modelClass' => User::className(),
                'forceCreate' => false
            ],
            'list' => [
                'class' => ListAction::className(),
                'model_class' => UserSearch::className()
            ],
            'create' => [
                'class' => CreateAction::className(),
                'permission' => RbacController::create_profile
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'permission' => RbacController::update_profile
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'permission' => RbacController::delete_profile
            ]
        ];
    }


    public function getModelClass() {
        return User::className();
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel(User::className(), $id);
        self::checkAccess(RbacController::delete_profile, ['user' => $model]);
        $model->delete();
        return $this->redirect(['list']);
    }


    public function actionAvailableGroups() {
        /**@var User $user */
        $user = Yii::$app->user->identity;
        return json_encode($user->getEditableRoles());
    }


    public function actionMassDelete() {
        if(isset($_POST['keys'])) {
            foreach($_POST['keys'] as $key) {
                try {
                    $model = $this->findModel(User::className(), $key);
                    if($model) {
                        if($model->delete()) {
                            Alert::addSuccess("Items has been successfully deleted");
                        }
                    }
                }
                catch(Exception $e) {
                    Alert::addError('Item has not been deleted', $e->getMessage());
                }
            }
        }
        return $this->redirect(['list']);
    }


    public function actionAsAjax($id) {
        $model = $this->findModel(User::className(), $id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->toArray();
    }


    /**
     * Provides json response for select2 plugin
     */
    public function actionGetSelectionList() {
        self::selectionList(User::className(), 'name');
    }


    /**
     * Provides json response for select2 plugin
     */
    public function actionGetSelectionById() {
        self::selectionById(User::className(), 'name');
    }
}
