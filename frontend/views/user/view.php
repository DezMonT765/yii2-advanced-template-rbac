<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->email;

?>
<div class="user-view">

    <br>


    <?= DetailView::widget([
                               'model' => $model,
                               'attributes' => [
                                   'id',
                                   'email:email',
                                   [
                                       'attribute'=>'status',
                                       'value'=>$model->getCurrentStatus()
                                   ],
                                   'created_at:datetime',
                                   'updated_at:datetime',
                                   [
                                       'attribute'=>'role',
                                       'value'=>$model->getCurrentRole()
                                   ],
                               ],
                           ]) ?>

</div>
