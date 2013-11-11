
<div class="row">
    <div class="large-12 columns">
        <h1>Recent Videos</h1>
    </div>
</div>
    <?php
    $video = new Video();
    $data = $video->get_list(array("isConverted" => 1, "visibility_setting" => 1), 9);
    $i = 0;
    foreach ($data as $row) {
        if (($i % 3) == 0) {
            if ($i != 1)
                echo "</div>\n";
            echo '<div class="row">';
        }
        ?>

        <div class="large-4 columns">
            <a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $row->id; ?>">
                <img src="<?php echo Config::get('address'); ?>/video/view/thumbnail?id=<?= $row->id; ?>&amp;width=200&amp;height=100" width="200" height="100" class="img-polaroid" alt="thumbnail of <?= $row->title; ?>" />
            </a>
            <h4><a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $row->id; ?>"><?= $row->title; ?></a></h4>
        </div>

    <?
    $i++;
}
echo "</div>";
?>


    <div class="row">
        <div class="large-12 columns">
            <h1>Only for Registered</h1></div>
    </div>

    <div class="large-12 columns">
<?php
$video = new Video();
$data = $video->get_list(array("isConverted" => 1, "visibility_setting" => 2), 9);
$i = 0;
foreach ($data as $row) {
    if (($i % 3) == 0) {
        if ($i != 1)
            echo "</div>\n";
        echo '<div class="row">';
    }
    ?>

            <div class="large-4 small-8  columns">
                <a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $row->id; ?>"><img src="./video/view/thumbnail?id=<?= $row->id; ?>&amp;width=200&amp;height=100" width="200" height="100" class="img-polaroid" alt="thumbnail of <?= $row->title; ?>" /></a>
                <h3><a href="<?php echo Config::get('address'); ?>/video/view?id=<?= $row->id; ?>"><?= $row->title; ?></a></h3>
            </div>

    <?
    $i++;
}
echo "</div>";
?>

