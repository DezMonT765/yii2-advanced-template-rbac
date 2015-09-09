<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <br>
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

    <?= $form->field($model, 'status')->dropDownList(User::statuses()) ?>

    <?= $form->field($model, 'role')->dropDownList(Yii::$app->user->identity->getEditableRoles()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('yii', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
