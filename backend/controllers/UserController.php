<?php
namespace backend\controllers;

use backend\actions\UserEditableAction;
use backend\filters\UserLayout;
use common\components\Alert;
use common\controllers\MainController;
use console\controllers\RbacController;
use Exception;
use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends MainController
{
    public function behaviors()
    {
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


    public function actions()
    {
        return [
            'ajaxUpdate' => [
                'class' => UserEditableAction::className(),
                'modelClass' => User::className(),
                'forceCreate' => false
            ]
        ];
    }


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('user-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel(User::className(), $id);
        self::checkAccess(RbacController::update_profile, ['user' => $model]);
        return $this->render('user-view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User(['scenario' => 'create']);
        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['list', 'id' => $model->id]);
        }
        else
        {
            return $this->render('user-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel(User::className(), $id);
        self::checkAccess(RbacController::update_profile, ['user' => $model]);
        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            return $this->render('user-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel(User::className(), $id);
        self::checkAccess(RbacController::delete_profile, ['user' => $model]);
        $model->delete();
        return $this->redirect(['list']);
    }


    public function actionAvailableGroups()
    {
        /**@var User $user */
        $user = Yii::$app->user->identity;
        return json_encode($user->getEditableRoles());
    }


    public function actionMassDelete()
    {
        if(isset($_POST['keys']))
        {
            foreach ($_POST['keys'] as $key)
            {
                try
                {
                    $model = $this->findModel(User::className(), $key);
                    if($model)
                    {
                        if($model->delete())
                        {
                            Alert::addSuccess("Items has been successfully deleted");
                        }
                    }
                }
                catch (Exception $e)
                {
                    Alert::addError('Item has not been deleted', $e->getMessage());
                }
            }
        }
        return $this->redirect(['list']);
    }


    public function actionAsAjax($id)
    {
        $model = $this->findModel(User::className(), $id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->toArray();
    }


    /**
     * Provides json response for select2 plugin
     */
    public function actionGetSelectionList()
    {
        self::selectionList(User::className(), 'name');
    }


    /**
     * Provides json response for select2 plugin
     */
    public function actionGetSelectionById()
    {
        self::selectionById(User::className(), 'name');
    }
}
