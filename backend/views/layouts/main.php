<?php
use backend\filters\AdminLayout;
use frontend\filters\SiteLayout;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \common\components\MainView */

include_once('header.php');
?>
<?php $this->beginBody() ?>
<div class="wrap">
   <?php
    
    NavBar::begin([
                      'brandLabel' => 'Advanced Template backend',
                      'brandUrl' => Yii::$app->homeUrl,
                      'options' => [
                          'class' => 'navbar-default navbar-fixed-top',
                      ],
                  ]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-left'],
                         'items' =>$this->getLayoutData(AdminLayout::place_left_nav)
                     ]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-right'],
                         'items' =>$this->getLayoutData(AdminLayout::place_right_nav)
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

