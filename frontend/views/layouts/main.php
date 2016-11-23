<?php
use dezmont765\yii2bundle\views\MainView;
use frontend\filters\SiteLayout;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this MainView */

include_once('header.php');
?>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php

    NavBar::begin([
                      'brandLabel' => Yii::t('app','Frontend panel'),
                      'brandUrl' => Yii::$app->homeUrl,
                      'options' => [
                          'class' => 'navbar-default navbar-fixed-top',
                      ],
                  ]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-left'],
                         'items' =>$this->getLayoutData(SiteLayout::place_left_nav)
                     ]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-right'],
                         'items' =>$this->getLayoutData(SiteLayout::place_right_nav)
                     ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
                                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                ]) ?>
        <?= $content ?>
    </div>
</div>
<?php include_once('footer.php');?>

