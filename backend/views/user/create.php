<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Create User');

?>
<div class="user-create">
<br>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
