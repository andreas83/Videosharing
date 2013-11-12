
<div class="row">
    <div class="large-12 columns">
        <div class="flex-video height-auto">
    <?php
    $video = new Video();
    $data = $video->get_list(array("isConverted" => 1, "visibility_setting" => 1), "0, 1");
    $i = 0;
    foreach ($data as $view) {
         ?> 
            <video class="height-auto" video="<?= $view->id; ?>"  controls="controls" poster="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/thumb<?php echo $view->thumb; ?>.png">
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/<?= $view->id; ?>.mp4" type="video/mp4">          
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/<?= $view->id; ?>.webm" type="video/webm">

        </video> 
	<?
    }
    ?>
        </div>
    </div>

    <div class="large-12 columns">
        <h1>Recent Videos</h1>
    </div>
   
    <?php
    $video = new Video();
    $data = $video->get_list(array("isConverted" => 1, "visibility_setting" => 1), "1, 9");
    $i = 0;
    foreach ($data as $row) {
    ?>

        <div class="large-4 small-6 columns">
            <a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $row->id; ?>">
                <img src="<?php echo Config::get('address'); ?>/video/view/thumbnail?id=<?= $row->id; ?>&amp;width=200&amp;height=100"  class="img-polaroid" alt="thumbnail of <?= $row->title; ?>" />
            </a>
            <h6><a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $row->id; ?>"><?= $row->title; ?></a></h6>
        </div>

    <?
    $i++;
}
echo "</div>";

?>


