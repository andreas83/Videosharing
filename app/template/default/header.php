<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

        <head>
            <title><?php echo $view->Title; ?></title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta name="viewport" content="width=device-width">
            <link rel="stylesheet" href="<?php echo Config::get('address'); ?>/public/css/foundation.min.css" type="text/css"/>
            <script src="<?php echo Config::get('address'); ?>/public/js/custom.modernizr.js" type="text/javascript"></script>
            <base id="base" href="<?php echo Config::get('address'); ?>" />
            <style>
            .container{
                margin-top:20px;
            }
            .height-auto{
                height:auto !important;
            }
            </style>
        </head>
        <body>
            <nav class="top-bar" id="header" >


                <ul class="title-area">

                    <li class="name">

                    </li>

                    <li class="toggle-topbar menu-icon"><a href=""><span>Menu</span></a></li>
                </ul>


                <section class="top-bar-section">
                    <ul class="title-area">
                        <li class="name">
                            <h1>
                                <a href="./" class=""> Videomanager</a>
                            </h1>
                        </li>
                    </ul>



                    <ul class="right">

                        <?php
                        if (isset($_SESSION['user'])):
                            ?>        
                            <li><a href="<?php echo Config::get('address'); ?>/user/video"><?php echo _("My Videos"); ?></a></li>
                            <li><a href="<?php echo Config::get('address'); ?>/video/manager/upload"><?php echo _("Upload"); ?></a></li>
                            <li><a href="<?php echo Config::get('address'); ?>/user/logout"><?php echo _("Logout"); ?></a></li>
                        <?php else: ?>

                            <li><a href="<?php echo Config::get('address'); ?>/user/login"><?php echo _("SignIn"); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </section>

            </nav>
            <div class="container"> 
            
            
            

