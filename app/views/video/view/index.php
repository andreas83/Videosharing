
<?php

if(isset($view->error) && !empty($view->error))
{

    
    if($view->showRegister)
    {
        echo'<div class="alert alert-info">
                <h2>'.$view->error.'</h2>
                    <form method="get" action="'. Config::get('address').'/user/login">
                    <input class="btn btn-success" type="submit"  value="'. _("Sign Up").' ">
                  </form>';
        echo "</div>";

    }else
    {
        echo'<div class="alert alert-error">
        <h2>'.$view->error.'</h2>';
        echo "</div>";        
    }
    
    die();
}
?>

<div class="row-fluid">
<video video="<?= $view->id; ?>" width="640" height="380" controls="controls"  poster="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/thumb<?php echo $view->thumb; ?>.png">
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/<?= $view->id; ?>.mp4" type="video/mp4">          
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/<?= $view->id; ?>.webm" type="video/webm">

            <div class="mediaplayback" id="<?= $view->id; ?>" uid="<?php echo $view->user_id; ?>" video="<?= $view->id; ?>" thumb="<?= $view->thumb; ?>" style="width:640; height:480"></div>
     
</video> 


</div>
<div class="row-fluid">
    <h1><?php echo $view->title; ?>
    <? if ($view->user_id == $_SESSION['user_id']):?>
    <form method="get" action="<?php echo Config::get('address'); ?>/video/manager/edit">
        <input type="hidden" name="id" value="<?= $view->id; ?>">
        <input class="btn btn-info" type="submit"  value="<? echo _("Edit");?> ">
    </form>
    <? endif; ?>
</h1> 
</div>
<div class="row-fluid">
<?
echo $view->desc;
?>
</div>
