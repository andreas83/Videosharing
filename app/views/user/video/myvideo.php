
<div class="containter-fluid">
<?php
foreach ($view->obj as $key)
{
    ?>
<div class="row-fluid">
    <div class="span5">
         <div class="mediaplayback" id="<?= $key->id; ?>" uid="<?php echo $_SESSION['user_id']; ?>" video="<?= $key->id; ?>" style="width:340px; height:280px"></div>
    </div>
    <div class="span5">
        <h2><?php echo $key->title;  ?> <button class="btn" onclick="location.href='<?php echo Config::get('address'); ?>/video/manager/delete?id=<?=$key->id; ?>'"><? echo _("Delete"); ?></button></h2> 
        <p><?php echo $key->descripton;  ?></p>
    </div>
    
</div>
<?
}
if(count($view->obj)==0):
?>
    <div class="alert alert-info">
        <h2><?php echo _("Ohoh, you did not Upload any Video so far");  ?></h2>
        <button class="btn btn-primary btn-success" onclick="location.href='<?php echo Config::get('address'); ?>/video/manager/upload'" >Upload Now</button>
    </div>
<? endif; ?>
</div>
<?
if(count($view->obj)>0):
?>
<div class="pagination">
    <ul>
    <?php
    if ($view->page == 1) {
        $url = "";
        $class = "disabled";
    } else {
        $res = $view->page - 1;
        $url = "?page=" . $res;
    }
    ?>
    <li><a class="<?php echo $class; ?>" href="<?php echo $url; ?>">&laquo;</a></li>
    <?php
    foreach (range(1, $view->Seiten) as $number) {
        echo "<li><a href=\"?page=$number\">$number</a></li>";
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
