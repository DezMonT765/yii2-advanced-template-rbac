<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
\backend\assets\Select2Asset::register($this);
?>
<br>
<?php $form = ActiveForm::begin([
                                 'action'=>Url::to(['translation/load']),

                                 'options' => [
                                     'enctype' => 'multipart/form-data'
                                 ]
                                ]); ?>
<?= $form->errorSummary($model);?>

<?= $form->field($model, 'file')->fileInput(['placeholder'=>$model->getAttributeLabel('file')]) ?>
<?= $form->field($model, 'isUpdate')->checkbox() ?>
<div class="hidden">
<?= $form->field($model, 'language')->hiddenInput(['style'=>'display:none']) ?>
</div>


    <?= Html::submitButton(Yii::t('translation_load', ":upload_translation_file") , ['class' =>  'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
<br>


