<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User',
]) . ' ' . $model->id;
?>
<div class="user-update">
<br>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
