
<?php
if (isset($view->error) && !empty($view->error)) {


    echo '<div class="row">';

    echo'<div  class="alert-box radius">
                <h2>' . $view->error . '</h2>';
    echo '</div>
                <div class="large-2 columns">
                  <form method="get" action="' . Config::get('address') . '/user/login">
                    <input class="button" type="submit"  value="' . _("Register") . ' ">
                  </form></div>
                <div class="large-2 columns>
                    <form method="get" action="' . Config::get('address') . '/user/login">
                    <input class="button" type="submit"  value="' . _("Login") . ' ">
                  </form>
                </div>
               </div>
              ';




    die();
}

if (count($view->inProgress) > 0):
    ?>
    <div class="row">
        <div class="large-12 columns">
            <div data-alert class="alert-box success">
    <?php echo _("Some videos are converted in the background"); ?>

            </div>
        </div>
    </div>    
<? endif; ?>

<?php
foreach ($view->obj as $key) {
    ?>
    <div class="row">
    

        <div class="large-6 columns">
            <a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $key->id; ?>"><img class="img-thumbnail" src="<?php echo Config::get('address'); ?>/video/view/thumbnail?id=<?= $key->id; ?>&width=380&height=200"></a>
        </div>
        <div class="large-6 columns">
            <h2><a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $key->id; ?>"><?php echo $key->title; ?></a></h2> 
            <p><?php echo $key->descripton; ?></p>
        </div>

    </div>
    <?
}
if (count($view->obj) == 0 && count($view->inProgress) == 0):
    ?>
<div class="row">
    <div class="alert alert-info">
        <h2><?php echo _("Ohoh, you did not Upload any Video so far"); ?></h2>
        <button class="button" onclick="location.href='<?php echo Config::get('address'); ?>/video/manager/upload'" ><?php echo _("Upload Now"); ?></button>
    </div>
</div>
<? endif;
?>

<div class="row">
    

<?
if (count($view->obj) > 0):
    ?>
    <div class="large-12 columns">
        <ul class="pagination">
    <?php
    if ($view->page == 1) {
        $url = "";
        $class = "unavailable";
    } else {
        $res = $view->page - 1;
        $url = Config::get('address') . "/user/video?page=" . $res;
    }
    ?>
            <li><h3><a class="<?php echo $class; ?>" href="<?php echo $url; ?>">&laquo;</a></h3></li>
            <?php
            foreach (range(1, $view->Seiten) as $number) {
                echo "<li><h3><a href=\"" . Config::get('address') . "/user/video?page=$number\">$number</a></h3></li>";
            }
            ?>
            <?php
            if ($view->Seiten == $view->page) {
                $url = "";
                $class = "unavailable";
            } else {
                $res = $view->page + 1;
                $url = "?page=" . $res;
            }
            ?>
            <li><h3><a class="<?php echo $class; ?>" href="<?php echo $url; ?>">&raquo;</a></h3></li>
        </ul>

            <?php
        endif;
        ?>
    </div>
</div>