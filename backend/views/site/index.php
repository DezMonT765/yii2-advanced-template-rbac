<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?php echo Yii::t('index','Congratulations!')?></h1>

        <p class="lead"><?php echo Yii::t('index','Feel the power of User management tool!')?></p>

        <p><a class="btn btn-lg btn-success" href="<?=Url::to('user/list')?>"><?php echo Yii::t('index','Go to user management')?></a></p>
    </div>

    <div class="body-content">



    </div>
</div>
