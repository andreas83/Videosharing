<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
    <head>
        <title><?php echo $view->Title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="<?php echo Config::get('address'); ?>/public/css/default.css" type="text/css"/>
        <link rel="stylesheet" href="<?php echo Config::get('address'); ?>/public/css/bootstrap.min.css" type="text/css"/>
        <base id="base" href="<?php echo Config::get('address'); ?>"></base>
 
        <script src="<?php echo Config::get('address'); ?>/public/js/jquery.js" type="text/javascript"></script>
        <?php 
        if (isset($view->CSS))
            echo $view->CSS;

        if (isset($view->JS))
            echo $view->JS;
        
        ?>
        
    </head>
    <body>
    <div class="navbar">
    <div class="navbar-inner">
    <a class="brand" href="<?php echo Config::get('address'); ?>">Video Manager</a>
    <ul class="nav">
    <li><a href="<?php echo Config::get('address'); ?>">Home</a></li>
    <?
    if(isset($_SESSION['user'])):
    ?>   
    <li><a href="<?php echo Config::get('address'); ?>/video/manager/upload">Upload</a></li>
    <? endif; ?>
    </ul>
    
            <?
            if(isset($_SESSION['user'])):
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
                
            <?else: ?>
    
            <ul class="nav pull-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Account  
                <b class="caret"></b>
                </a>


                <ul class="dropdown-menu">
                    <li class="span3">
                     <h3>Login</h3>
                     <form action="<?php echo Config::get('address'); ?>/user/login" method="post" accept-charset="UTF-8">
                        <div class="input-prepend">
                            <span class="add-on">
                                <i class="icon-user"></i>
                            </span>
                            <input id="username" style="margin-bottom: 15px;" type="text" name="user" size="30" placeholder="Username"/>
                        </div>
                        <div class="input-prepend">
                            <span class="add-on">
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
    </div> 
     
<div class="container-fluid">
    <div class="row-fluid">
    <div class="span2">
    <ul class="nav nav-tabs nav-stacked">
        <?
            if(isset($_SESSION['user'])):
        ?>        
        <li><a href="<?php echo Config::get('address'); ?>/user/video"><?php echo _("My Videos"); ?></a></li>
        <li><a href="<?php echo Config::get('address'); ?>/user/logout"><?php echo _("Logout"); ?></a></li>
        <? else: ?>
        
        <li><a href="<?php echo Config::get('address'); ?>/user/login"><?php echo _("SignIn"); ?></a></li>
        <? endif; ?>
    </ul>
    </div>
      <div class="span10">
          