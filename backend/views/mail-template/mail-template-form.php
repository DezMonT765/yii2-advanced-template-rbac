<?php

use backend\assets\CKEditorAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MailTemplates */
/* @var $form yii\widgets\ActiveForm */
CKEditorAsset::register($this);
?>

<div class="mail-templates-form">
    <br>

    <?php $form = ActiveForm::begin(['id'=>'template-create']); ?>

    <?= $form->field($model, 'template_type')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'template')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    CKEDITOR.replace("<?=Html::getInputId($model,'template')?>");

    $('#template-create').on('beforeValidate', function () {
        var text_id = "<?=Html::getInputId($model,'template')?>";
        var text = CKEDITOR.instances.<?=Html::getInputId($model,'template')?>.getData();
        $('#' + text_id).attr('value',text);
        return true;
    });
</script>
