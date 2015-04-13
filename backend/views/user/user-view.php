<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->email;

?>
<div class="user-view">

    <br>
    <p>
        <?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

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
