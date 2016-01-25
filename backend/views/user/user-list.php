<?php

use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'List users');
?>
<div class="user-index">
    <br>
    <?= GridView::widget([
        'id' => 'user-list',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],

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
                 'filter' => User::statuses(),
                 'attribute'=>'status',
                 'url'=>['ajaxUpdate'],
                 'type'=>'select',
                 'display' => "colors",
                 'editableOptions'=> function ($model)
                 {
                     return [
                        'source' => User::statuses(),
                        'value' =>$model->status
                     ];
                 }

             ],
             [
               'class'=>\dosamigos\grid\EditableColumn::className(),
               'filter' => User::roles(),
               'attribute'=>'role',
               'url'=>['ajaxUpdate'],
               'value' => function($data){
                   return User::getRole($data->role);
               },
               'type'=>'select',
               'editableOptions'=>
               [
                   'source' => Yii::$app->user->identity->getEditableRoles(),
                   'sourceCache' => false,

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
    <?php echo Html::button('Delete',['class'=>'btn btn-danger','id'=>'delete-user'])?>

</div>
<script>
    function colors(value, sourceData) {
        var selected = $.grep(sourceData, function (o) {
                return value == o.value;
            }),
            colors = <?=json_encode(User::$status_colors)?>;
        $(this).html(selected[0].text).css("color", colors[value]);
    }
    $("#delete-user").click(function()
    {
        if(confirm("Are you sure you want to delete all selected items?"))
        {
            var list = $('#user-list').yiiGridView('getSelectedRows');
            var newlist = {};
            $(list).each(function(index,value)
            {
                newlist["keys["+index+"]"] = value;
            });
            $.post("<?=Url::to(['mass-delete'])?>",newlist,function(data)
            {
                window.location.reload();
            });
        }
    });
</script>
