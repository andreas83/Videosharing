



<div class="row">
    <div class="large-12 columns">
        <h1>Latest Video</h1>
    </div>



    <div class="large-12 columns flex-video">
    <?php
    $video = new Video();
    $data = $video->get_list(array("isConverted" => 1, "visibility_setting" => 1), "0, 1");
    $i = 0;
    foreach ($data as $view) {
         ?> 
            <video video="<?= $view->id; ?>"  controls="controls" poster="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/thumb<?php echo $view->thumb; ?>.png">
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/<?= $view->id; ?>.mp4" type="video/mp4">          
            <source src="<?php echo Config::get('address'); ?>/public/video/<?php echo $view->user_id; ?>/<?= $view->id; ?>/<?= $view->id; ?>.webm" type="video/webm">

        </video> 
	<?
    }
    ?>
    </div>

    <div class="large-12 columns">
        <h1>Recent Videos</h1>
    </div>
    <div class="row">'
    <?php
    $video = new Video();
    $data = $video->get_list(array("isConverted" => 1, "visibility_setting" => 1), "1, 9");
    $i = 0;
    foreach ($data as $row) {
/*        if (($i % 3) == 0) {
            if ($i != 1)
                echo "</div>\n";
            echo '<div class="row">';
        }
*/  
      ?>

        <div class="large-4 small-6 columns text-center">
            <a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $row->id; ?>">
                <img src="<?php echo Config::get('address'); ?>/video/view/thumbnail?id=<?= $row->id; ?>&amp;width=200&amp;height=100"  class="img-polaroid" alt="thumbnail of <?= $row->title; ?>" />
            </a>
            <h6 class="hide-for-small"><a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $row->id; ?>"><?= $row->title; ?></a></h6>
        </div>

    <?
    $i++;
}
echo "</div>";
echo "</div>";
?>


