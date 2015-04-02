<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'options'=> [
            'class'=>'col-md-6'
        ]]); ?>
    <?=$form->errorSummary($model);?>
    <?= $form->field($model,'email')->textInput();?>

    <?php if($model->isNewRecord) : ?>

    <?= $form->field($model,'password')->passwordInput();?>

    <?= $form->field($model,'passwordConfirm')->passwordInput();?>

    <?php endif?>

    <?= $form->field($model, 'status')->dropDownList(User::$statuses) ?>

    <?= $form->field($model, 'role')->dropDownList(Yii::$app->user->identity->getEditableRoles()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
