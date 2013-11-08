
<?php

if(isset($view->error) && !empty($view->error))
{

    
  
        echo'<div class="alert alert-info">
                <h2>'.$view->error.'</h2>';
        echo "</div>";
        echo '<div class="row">
                <div class="col-md-1">
                  <form method="get" action="'. Config::get('address').'/user/register">
                    <input class="btn btn-large btn-primary" type="submit"  value="'. _("Register").' ">
                  </form></div>
                <div class="col-md-1">
                    <form method="get" action="'. Config::get('address').'/user/login">
                    <input class="btn btn-large btn-success" type="submit"  value="'. _("Login").' ">
                  </form>
                </div>
               </div>
              ';
        
        

    
    die();
}

if( count($view->inProgress)>0):
?>
    <div class="alert alert-info">
        <h4><?php echo _("Some videos are converted in the background");  ?></h4>
        
    </div>
<? endif; ?>
<div class="containter">
<?php
foreach ($view->obj as $key)
{
    ?>
<div class="row">
    <?php
    /*
    <div class="col-md-5">
        <video  width="440" height="280" controls="controls"  poster="<?php echo Config::get('address'); ?>/public/video/<?php echo $_SESSION['user_id']; ?>/<?= $key->id; ?>/thumb1.png">
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $_SESSION['user_id']; ?>/<?= $key->id; ?>/<?= $key->id; ?>.mp4" type="video/mp4">
            
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $_SESSION['user_id']; ?>/<?= $key->id; ?>/<?= $key->id; ?>.webm" type="video/webm">
            <div class="mediaplayback" id="<?= $key->id; ?>" uid="<?php echo $_SESSION['user_id']; ?>" video="<?= $key->id; ?>" style="width:440; height:280px"></div>
            
        </video> 
    </div>
     * 
     */
    ?>
    
    <div class="col-md-3">
        <a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $key->id; ?>"><img class="img-polaroid" src="<?php echo Config::get('address'); ?>/video/view/thumbnail?id=<?= $key->id; ?>&width=380&height=200"></a>
    </div>
    <div class="col-md-5">
        <h2><a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $key->id; ?>"><?php echo $key->title;  ?></a></h2> 
        <p><?php echo $key->descripton;  ?></p>
    </div>
    
</div>
<?
}
if(count($view->obj)==0 && count($view->inProgress)==0):
?>
    <div class="alert alert-info">
        <h2><?php echo _("Ohoh, you did not Upload any Video so far");  ?></h2>
        <button class="btn btn-primary btn-success" onclick="location.href='<?php echo Config::get('address'); ?>/video/manager/upload'" ><?php echo _("Upload Now"); ?></button>
    </div>
<? endif; 

?>

    
</div>
<?
if(count($view->obj)>0):
?>
<div >
    <ul class="pagination">
    <?php
    if ($view->page == 1) {
        $url = "";
        $class = "disabled";
    } else {
        $res = $view->page - 1;
        $url = Config::get('address')."/user/video?page=" . $res;
    }
    ?>
    <li><a class="<?php echo $class; ?>" href="<?php echo $url; ?>">&laquo;</a></li>
    <?php
    foreach (range(1, $view->Seiten) as $number) {
        echo "<li><a href=\"".Config::get('address')."/user/video?page=$number\">$number</a></li>";
    }
    ?>
    <?php
    if ($view->Seiten == $view->page) {
        $url = "";
        $class = "disabled";
    } else {
        $res = $view->page + 1;
        $url = "?page=" . $res;
    }
    ?>
    <li><a class="<?php echo $class; ?>" href="<?php echo $url; ?>">&raquo;</a></li>
    </ul>
</div>
<?php
endif; 
?>
