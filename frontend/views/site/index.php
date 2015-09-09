<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Awesome!</h1>
        <div class="thumbnail">
            <img src="/img/awesome.png" alt="awesome">
        </div>
        <?php if(Yii::$app->user->isGuest) : ?>
        <p class="lead"><?php echo Yii::t('app','The site is now alive! You can :')?></p>

        <p><a class="btn btn-lg btn-success" href="<?=Url::to('site/login')?>"><?php echo Yii::t('app','Login')?></a></p>
        <p class="y-cnt-text">-or-</p>
        <p><a class="btn btn-lg btn-success" href="<?=Url::to('site/register')?>"><?php echo Yii::t('app','Register')?></a></p>
        <?php else:?>
            <p class="lead">You are loggen in! You can :</p>
            <p><a class="btn btn-lg btn-success" href="<?=Url::to(['user/view','id'=>Yii::$app->user->identity->getId()])?>"><?php echo Yii::t('app','Go to your profile')?></a></p>
        <?php endif?>

    </div>

    <div class="body-content">



    </div>
</div>
