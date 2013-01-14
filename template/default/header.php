<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
    <head>
        <title><?php echo $view->Title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="<?php echo Config::get('address'); ?>/css/default.css" type="text/css"/>
        <script href="<?php echo Config::get('address'); ?>/js/jquery.js" type="text/javascript"></script>
        <?php 
        if(isset($view->CSS))
            echo $view->CSS;

        if(isset($view->JS))
            echo $view->JS;
        
        ?>
        
    </head>
    <body>
