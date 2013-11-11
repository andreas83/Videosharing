
<?php
if (isset($view->error) && !empty($view->error)) {


    if ($view->showRegister) {
        echo'<div class="row">
                <div class="large-12 columns">
                <div class="alert-box success radius">
                <h2>' . $view->error . '</h2>
                </div>
                    <form method="get" action="' . Config::get('address') . '/user/login">
                    <input class="button" type="submit"  value="' . _("Sign Up") . ' ">
                  </form>';
        echo "</div></div>";
    } else {
        echo'
        <div class="row">
                <div class="large-12 columns">    
                    <div class="alert-box success radius">
                    <h2>' . $view->error . '</h2>';
        echo "</div></div></div>";
    }

    die();
}
?>

<div class="row">
    <div class="large-12 columns flex-video">
        <video video="<?= $view->id; ?>" width="640" height="380" controls="controls"  poster="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/thumb<?php echo $view->thumb; ?>.png">
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/<?= $view->id; ?>.mp4" type="video/mp4">          
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/<?= $view->id; ?>.webm" type="video/webm">
        </video> 
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
            <h1><?php echo $view->title; ?> </h1>
            <? if (isset($_SESSION['user_id']) && $view->user_id == $_SESSION['user_id']): ?>
                <form method="get" action="<?php echo Config::get('address'); ?>/video/manager/edit">
                    <input type="hidden" name="id" value="<?= $view->id; ?>">
                    <input class="button" type="submit"  value="<? echo _("Edit"); ?> ">
                </form>
            <? endif; ?>
       
    </div>

    <div class="large-12 columns">
        <p>
        <?
        echo $view->desc;
        ?>
        </p>
    </div>
</div>
