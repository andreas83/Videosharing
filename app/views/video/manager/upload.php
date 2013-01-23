<?
if ($view->showUpload==true):?>
<form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/upload" enctype="multipart/form-data">

    <input id="file_upload" name="file_upload" type="file" multiple="true">
    <input class="btn btn-inverse" type="submit" name="upload">

</form>
<? endif; ?>
        
<?php
if ($view->showUpload==false):?>
 <form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/save" enctype="multipart/form-data" class="form-horizontal">
 
        <div class="control-group">
            <label class="control-label" for="inputTitle"><? echo _("Title"); ?></label>
            <div class="controls">
               <input type="text" id="inputTitle" name="title" placeholder="<?php echo _("Title"); ?>">
            </div>
        </div>            
        <div class="control-group">
            <label class="control-label" for="inputDescription"><? echo _("Description"); ?></label>
            <div class="controls">
               <textarea id="inputDescription" name="description" placeholder="<?php echo _("Description"); ?>"></textarea>
            </div>
        </div>  
        <div class="control-group">
            <label class="control-label" for="inputDescription"><? echo _("Visibility"); ?></label>
            <div class="controls">
                <select name="visibility" class="span3">
                    <option value="1"><? echo _("Everyone"); ?></option>
                    <option value="2"><? echo _("Just my Friends"); ?></option>
                    <option value="3"><? echo _("Just me"); ?></option>
                </select>
            </div>
        </div>      
 <?php
    echo '<img src="' . $view->thumb . '_thumbs1/00000001.png" id="thumb1" width="200">';
    echo '<img src="' . $view->thumb . '_thumbs2/00000001.png" id="thumb2" width="200">';
    echo '<img src="' . $view->thumb . '_thumbs3/00000001.png" id="thumb3" width="200">';
    ?>
    <br/>
    <div id="mediaplayback" pid="<?= $view->pid; ?>" video="<?= $view->filename; ?>" style="width:640px; height:480px"></div>

    <!--<video width="320" height="240" controls>
            <source src="<?= $view->thumb; ?>.webm" type="video/mp4"> </source>
            
    </video> -->
        <div class="form-actions">
            <input type="hidden" name="filename" value="<?= $view->filename; ?>">
            <input type="submit" class="btn btn-primary" value="<?php echo _("Save Changes"); ?>">
            <button type="button" class="btn"><?php echo _("Cancle"); ?></button>
        </div>
</form>    
    <?php
endif;
?>