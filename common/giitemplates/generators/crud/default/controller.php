<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Exception;
use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use console\controllers\RbacController;
use dezmont765\yii2bundle\components\Alert;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use dosamigos\editable\EditableAction;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    public $defaultAction = 'list';
    public function behaviors()
    {
        $behaviors = [
            'layout' => <?=Inflector::camelize($generator->getControllerID())?>Layout::className(),
        ];
        return $behaviors;
    }

    public function actions()
    {
        return  [
            'ajax-update' => [
                'class' => EditableAction::className(),
                'modelClass' => <?=$modelClass?>::className(),
                'forceCreate' => false
            ]
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionList()
    {
        <?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search();

        return $this->render('<?= $generator->getControllerID()?>-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        <?php else: ?>
            $dataProvider = new ActiveDataProvider([
                'query' => <?= $modelClass ?>::find(),
            ]);

            return $this->render('<?= $generator->getControllerID()?>-list', [
                'dataProvider' => $dataProvider,
            ]);
        <?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
         $model = $this->findModel(<?=$modelClass?>::className(),<?= $actionParams ?>);
         return $this->render('<?= $generator->getControllerID()?>-view', [
            'model' => $model,
         ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->render('<?= $generator->getControllerID()?>-form', [
                   'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?=$modelClass?>::className(),<?= $actionParams ?>);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
                return $this->render('<?= $generator->getControllerID()?>-form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        try
        {
            $model = $this->findModel(<?=$modelClass?>::className(),<?= $actionParams ?>);
            $model->delete();
        }
        catch(Exception $e) {
            Alert::addError('Item has not been deleted', $e->getMessage());
        }
        return $this->redirect(['list']);
    }


    public function actionMassDelete()
    {
        if(isset($_POST['keys']))
        {
            foreach ($_POST['keys'] as $key)
            {
                try {
                    $model = $this->findModel(<?=$modelClass?>::className(), $key);
                    if($model)
                    {
                        if($model->delete()){
                            Alert::addSuccess("Items has been successfully deleted");
                        }
                    }
                }
                catch(Exception $e) {
                    Alert::addError('Item has not been deleted',$e->getMessage());
                }
            }
        }
        return $this->redirect(['list']);
    }

    public function actionAsAjax(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?=$modelClass?>::className(),<?= $actionParams ?>);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->toArray();
    }

    /**
    * Provides json response for select2 plugin
    */
    public function actionGetSelectionList()
    {
            self::selectionList(<?=$modelClass?>::className(),'name');
    }

    /**
    * Provides json response for select2 plugin
    */
    public function actionGetSelectionById()
    {
        self::selectionById(<?=$modelClass?>::className(),'name');
    }

}
