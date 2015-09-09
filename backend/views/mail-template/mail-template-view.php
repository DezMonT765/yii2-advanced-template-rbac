<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MailTemplates */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mail Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-templates-view">

    <br>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'template_type',
            'subject',
            'template:html',
        ],
    ]) ?>

</div>
