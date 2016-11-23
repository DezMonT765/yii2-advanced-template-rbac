<?php
use dezmont765\yii2bundle\views\MainView;
use frontend\filters\SiteLayout;
use frontend\filters\TabbedLayout;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this MainView */
include_once('header.php');
?>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
                      'brandLabel' => Yii::t('app', 'Frontend panel'),
                      'brandUrl' => Yii::$app->homeUrl,
                      'options' => [
                          'class' => 'navbar-default navbar-fixed-top',
                      ],
                  ]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-left'],
                         'items' => $this->getLayoutData(SiteLayout::place_left_nav)
                     ]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav navbar-right'],
                         'items' => $this->getLayoutData(SiteLayout::place_right_nav)
                     ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Nav::widget(
            [
                'options' => ['class' => 'nav-tabs'],
                'items' => $this->getLayoutData(TabbedLayout::place_tabs)
            ]) ?>
        <?= $content ?>
    </div>
</div>
<?php include_once('footer.php'); ?>

