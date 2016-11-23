<?php

namespace backend\controllers;

use backend\filters\MailTemplateLayout;
use console\controllers\RbacController;
use dezmont765\yii2bundle\controllers\MainController;
use Yii;
use common\models\MailTemplates;
use common\models\MailTemplatesSearch;
use yii\filters\AccessControl;

/**
 * MailTemplateController implements the CRUD actions for MailTemplates model.
 */
class MailTemplateController extends MainController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'=>true,
                        'roles'=>[RbacController::super_admin]
                    ]
                ]
            ],
            'layout' => MailTemplateLayout::className(),
        ];
    }


    /**
     * Lists all MailTemplates models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new MailTemplatesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('mail-template-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single MailTemplates model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel(MailTemplates::className(), $id);
        return $this->render('mail-template-view', [
            'model' => $model
        ]);
    }


    /**
     * Creates a new MailTemplates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MailTemplates();
        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            return $this->render('mail-template-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing MailTemplates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel(MailTemplates::className(), $id);
        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            return $this->render('mail-template-form', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing MailTemplates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel(MailTemplates::className(), $id);
        $model->delete();
        return $this->redirect(['index']);
    }
}
