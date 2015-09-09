<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MailTemplatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mail Templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-templates-index">
    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'template_type',
            'subject',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
