<?php
use backend\filters\AdminLayout;
use backend\filters\TabbedLayout;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

include_once('header.php');
?>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
                      'brandLabel' => Yii::t('header','Admin panel'),
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
        <?= Nav::widget(
            [
                'options' => ['class'=>'nav-tabs'],
                'items' =>$this->getLayoutData(TabbedLayout::place_tabs)
            ]) ?>
        <?= $content ?>
    </div>
</div>
<?php include_once('footer.php');?>

