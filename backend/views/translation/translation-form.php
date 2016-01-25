<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
\backend\assets\Select2Asset::register($this);
?>
<div class="translation-form">
<?php $form = ActiveForm::begin(['layout'=>'inline',
                                 'action'=>Url::to(['translation/create']),
                                 'fieldConfig' => [
                                     'template' => "<span class='left-mrg-10'>{input}\n{hint}\n{error}</span>",
                                     'inputTemplate' => '{input}',
                                 ],
                                ]); ?>
<?= $form->errorSummary($model);?>

<?= $form->field($model, 'source_message')->textInput(['placeholder'=>$model->getAttributeLabel('source_message'),'class'=>'translation-form-input form-control',]) ?>
<?= $form->field($model, 'translation')->textInput(['placeholder'=>$model->getAttributeLabel('translation'),'class'=>'translation-form-input form-control']) ?>
<?= $form->field($model, 'language')->hiddenInput(['style'=>'display:none']) ?>


    <?= Html::submitButton(Yii::t('app', '+') , ['class' =>  'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
</div>


