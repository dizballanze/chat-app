<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
	<meta name="language" content="ru" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/app.css" />
    <link href="css/jquery.cssemoticons.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/soundmanager2-nodebug-jsmin.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.cssemoticons.min.js" type="text/javascript"></script>

    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
    </style>

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<title><?php $this->widget("application.widgets.TitleWidget") ?></title>
</head>

<body>

<?php $this->widget("application.widgets.NavWidget") ?>

<div class="container">

    <div class="row">
        <?php if (Yii::app()->user->hasFlash('success')):?>
            <div class="alert alert-success"><?php echo Yii::app()->user->getFlash('success') ?></div>
        <?php endif ?>
        <?php if (Yii::app()->user->hasFlash('error')):?>
            <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error') ?></div>
        <?php endif ?>
    </div>

    <?php echo $content; ?>

    <hr>

    <footer>
        <p>&copy; Шиканов Юрий 2012</p>
    </footer>

</div> <!-- /container -->

</body>
</html>