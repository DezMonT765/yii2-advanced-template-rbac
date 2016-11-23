<?php
use dezmont765\yii2bundle\views\MainView;
use frontend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this MainView */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <!-- form CSS -->
    <!--    <link rel="stylesheet" href="css/jquery.arcticmodal.css">-->
    <!--    <link rel="stylesheet" href="css/jquery.jgrowl.css">-->


    <!-- fonts -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<div id="login-box"></div>