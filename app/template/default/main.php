
<div class="row">
    <div class="large-12 columns">
        <div class="flex-video height-auto">
    <?php
    $video = new Video();
    $data = $video->get_list(array("isConverted" => 1, "visibility_setting" => 1), "0, 1");
    $i = 0;
    foreach ($data as $view) {
         ?> 
            <video class="height-auto" video="<?php echo $view->id; ?>"  controls="controls" poster="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?php echo $view->id; ?>/thumb<?php echo $view->thumb; ?>.png">
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?php echo $view->id; ?>/<?php echo $view->id; ?>.mp4" type="video/mp4">          
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?php echo $view->id; ?>/<?php echo $view->id; ?>.webm" type="video/webm">

        </video> 
	<?php
    }
    ?>
        </div>
    </div>

    <div class="large-12 columns">
        <h1><? echo _("Recent Videos"); ?></h1>
    </div>
   
    <?php
    $video = new Video();
    $data = $video->get_list(array("isConverted" => 1, "visibility_setting" => 1), "1, 9");
    $i = 0;
    foreach ($data as $row) {
    ?>

        <div class="large-4 small-6 columns">
            <a href="<?php echo Config::get('address'); ?>/video/view?id=<?php echo $row->id; ?>">
                <img src="<?php echo Config::get('address'); ?>/video/view/thumbnail?id=<?php echo $row->id; ?>&amp;width=200&amp;height=100"  class="img-polaroid" alt="thumbnail of <?php echo $row->title; ?>" />
            </a>
            <h6><a href="<?php echo Config::get('address'); ?>/video/view?id=<?php echo $row->id; ?>"><?php echo $row->title; ?></a></h6>
        </div>

    <?php
    $i++;
}
echo "</div>";

?>


