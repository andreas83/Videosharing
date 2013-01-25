<?
if ($view->showUpload==true):?>
<form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/upload" enctype="multipart/form-data">

    <input id="file_upload" name="file_upload" type="file" multiple="true">
    <input class="btn btn-inverse" type="submit" name="upload">

</form>
<? endif; ?>
        
<?php
if ($view->showUpload==false):?>
<?php 
if ($view->showAlert==true):?>
    <div class="alert alert-info">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
     <?php echo $view->success; ?><br/>
  
     </div>
<?endif;?>   
        <?  if($view->editMode) : ?>
        <form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/update" enctype="multipart/form-data" class="form-horizontal">
        <?endif;?>
        <?  if(!$view->editMode) : ?>
        <form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/save" enctype="multipart/form-data" class="form-horizontal">
        <?endif;?>
        <div class="control-group">
            <label class="control-label" for="inputTitle"><? echo _("Title"); ?></label>
            <div class="controls">
               <input type="text" id="inputTitle" name="title" placeholder="<?php echo _("Title"); ?>" value="<?php echo $view->video->title?>">
            </div>
        </div>            
        <div class="control-group">
            <label class="control-label" for="inputDescription"><? echo _("Description"); ?></label>
            <div class="controls">
               <textarea id="inputDescription" name="description" placeholder="<?php echo _("Description"); ?>"><?php echo $view->video->descripton?></textarea>
            </div>
        </div>  
        <div class="control-group">
            <label class="control-label" for="inputDescription"><? echo _("Visibility"); ?></label>
            <div class="controls">
                <select name="visibility" class="span3">
                    <option <?= ($view->video->visibility_setting=="1" ? "selected" : "") ?> value="1"><? echo _("Everyone"); ?></option>
                    <option <?= ($view->video->visibility_setting=="2" ? "selected" : "") ?> value="2"><? echo _("Just Registered Users"); ?></option>
                    <option <?= ($view->video->visibility_setting=="3" ? "selected" : "") ?> value="3"><? echo _("Just me"); ?></option>
                </select>
            </div>
        </div>  
                <ul class="thumbnails">

    
    <?php
    $i=1;
    foreach ($view->thumbnails as $thumb)
    {
        echo '<li class="span2"><div class="thumbnail">';
        echo '<img class="thumb" thumb="'.$i++.'" src="'.$thumb.'" width="200">';
        echo '</div></li>';
    }
    ?>
    
    
    </ul>


        <div class="form-actions">
            <input type="hidden" name="filename" value="<?= $view->filename; ?>">
            <input type="hidden" id="thumb" name="thumb" value="<?= (isset($view->video->thumb) ? $view->video->thumb : "1") ; ?>">
            <input type="submit" class="btn btn-primary" value="<?php echo _("Save Changes"); ?>">
            <?
            if($view->editMode) : ?>
            <input type="hidden" name="id" value="<?= $view->video->id; ?>">
            <button type="submit" value="true" name="delete" class="btn"><?php echo _("Delete"); ?></button>
            <?endif;?>
        </div>
</form>    
    <?php
endif;
?>