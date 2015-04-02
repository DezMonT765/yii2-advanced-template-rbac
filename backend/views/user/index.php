<?php

use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
?>
<div class="user-index">
    <br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             [
                 'attribute'=>'email',
                 'format'=>'raw',
                 'value'=>function($data)
                 {
                    return Html::a($data->email,Url::to(['update','id'=>$data->id]));
                 }
             ],
             [
                 'class'=>\dosamigos\grid\EditableColumn::className(),
                 'filter' => User::$statuses,
                 'attribute'=>'status',
                 'url'=>['ajaxUpdate'],
                 'type'=>'select',
                 'value' => function($data){
                     return User::getStatus($data->status);
                 },
                 'editableOptions'=>[
                     'source' => User::$statuses
                 ]
             ],
             [
               'class'=>\dosamigos\grid\EditableColumn::className(),
               'filter' => User::$roles,
               'attribute'=>'role',
               'url'=>['ajaxUpdate'],
               'value' => function($data){
                   return User::getRole($data->role);
               },
               'type'=>'select',
               'editableOptions'=>
               [
                   'source' => Url::to(['user/available-groups']),
                   'sourceCache' => false
               ]
             ],

             [
                 'attribute'=>'created_at',
                 'filter' => false,
                 'format'=>'datetime'
             ],
             [
                 'attribute'=>'updated_at',
                 'filter' => false,
                 'format'=>'datetime'
             ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<script>
    function colors(value, sourceData) {
        alert(1);
        var selected = $.grep(sourceData, function (o) {
                return value == o.value;
            }),
            colors = '<?=json_encode(User::$status_colors)?>';
        $(this).text(selected[0].text).css("color", colors[value]);
    }
</script>
