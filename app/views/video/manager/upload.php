    <form method="POST" src="<?php echo Config::get('address'); ?>/Video/Manager/Upload" enctype="multipart/form-data">
            <div id="queue"></div>
            <input id="file_upload" name="file_upload" type="file" multiple="true">
            <input type="submit" name="upload">
    </form>

<?php
if($view->thumb):
    echo '<img src="'.$view->thumb.'_thumbs1/00000001.png" width="300">';
    echo '<img src="'.$view->thumb.'_thumbs2/00000001.png" width="300">';
    echo '<img src="'.$view->thumb.'_thumbs3/00000001.png" width="300">';

?>

<div class="flowplayer">
   <video src="<?=$view->thumb;?>.mp4"></video>
</div>

<!--<video width="320" height="240" controls>
	<source src="<?=$view->thumb;?>.webm" type="video/mp4"> </source>
	
</video> -->
<?php
endif;

?>