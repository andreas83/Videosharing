<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $view->Title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="<?php echo Config::get('address'); ?>/public/css/default.css" type="text/css"/>
        <link rel="stylesheet" href="<?php echo Config::get('address'); ?>/public/css/bootstrap.min.css" type="text/css"/>
        <base id="base" href="<?php echo Config::get('address'); ?>" />

        <script src="<?php echo Config::get('address'); ?>/public/js/jquery.js" type="text/javascript"></script>
        <?php
        if (isset($view->CSS))
            echo $view->CSS;

        if (isset($view->JS))
            echo $view->JS;
        ?>

    </head>
    <body>

        <nav class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Videomanager</a>
            </div>
            
        </nav>
        <div class="containter">
        
            <div class="row">
                <div class="col-xs-6 col-sm-2" rol="navigation">
                    <ul class="nav nav-pills nav-stacked">
                        <?
                        if (isset($_SESSION['user'])):
                            ?>        
                            <li><a href="<?php echo Config::get('address'); ?>/user/video"><?php echo _("My Videos"); ?></a></li>
                            <li><a href="<?php echo Config::get('address'); ?>/video/manager/upload"><?php echo _("Upload"); ?></a></li>
                            <li><a href="<?php echo Config::get('address'); ?>/user/logout"><?php echo _("Logout"); ?></a></li>
                        <? else: ?>

                            <li><a href="<?php echo Config::get('address'); ?>/user/login"><?php echo _("SignIn"); ?></a></li>
                        <? endif; ?>
                    </ul>
                </div>
            
                <div class="col-xs-12 col-sm-9">

                    <? /*

                      <div class="navbar">

                      <a class="navbar-brand" href="<?php echo Config::get('address'); ?>">Video Manager</a>
                      <ul class="nav">
                      <li><a href="<?php echo Config::get('address'); ?>">Home</a></li>
                      <?
                      if (isset($_SESSION['user'])):
                      ?>
                      <li><a href="<?php echo Config::get('address'); ?>/video/manager/upload">Upload</a></li>
                      <? endif; ?>
                      </ul>

                      <?
                      if (isset($_SESSION['user'])):
                      ?>
                      <ul class="nav pull-right">
                      <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="icon-user"></i>&nbsp;<?php echo $_SESSION['user']; ?>
                      <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu">
                      <li><a href="<?php echo Config::get('address'); ?>/user/video"><i class="icon-play-circle"></i> <?php echo _("My Videos"); ?></a></li>
                      <li><a href="<?php echo Config::get('address'); ?>/user/logout"><i class="icon-off"></i> <?php echo _("Logout"); ?></a></li>
                      </ul>

                      <? else: ?>

                      <ul class="nav pull-right">
                      <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      Account
                      <b class="caret"></b>
                      </a>


                      <ul class="dropdown-menu">
                      <li class="col-md-3">
                      <h3>Login</h3>
                      <form action="<?php echo Config::get('address'); ?>/user/login" method="post" accept-charset="UTF-8">
                      <div class="input-group">
                      <span class="input-group-addon">
                      <i class="icon-user"></i>
                      </span>
                      <input id="username" style="margin-bottom: 15px;" type="text" name="user" size="30" placeholder="Username"/>
                      </div>
                      <div class="input-group">
                      <span class="input-group-addon">
                      <i class="icon-exclamation-sign"></i>
                      </span>
                      <input id="password" style="margin-bottom: 15px;" type="password" name="password" placeholder="Password" size="30" />
                      </div>


                      <input class="btn btn-primary" type="submit" name="submit" value="Sign In" />

                      </form>

                      </li>
                      </ul>
                      </li>
                      </ul>
                      <? endif; ?>

                      </div>

                      <div class="container">
                      <div class="row">
                      <div class="col-md-2">
                      <ul class="nav nav-tabs nav-stacked">
                      <?
                      if (isset($_SESSION['user'])):
                      ?>
                      <li><a href="<?php echo Config::get('address'); ?>/user/video"><?php echo _("My Videos"); ?></a></li>
                      <li><a href="<?php echo Config::get('address'); ?>/user/logout"><?php echo _("Logout"); ?></a></li>
                      <? else: ?>

                      <li><a href="<?php echo Config::get('address'); ?>/user/login"><?php echo _("SignIn"); ?></a></li>
                      <? endif; ?>
                      </ul>
                      </div>
                      <div class="col-md-10">
                     */ ?>