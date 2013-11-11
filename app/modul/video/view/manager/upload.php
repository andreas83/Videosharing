<? if ($view->showUpload == true): ?>
    <form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/uploadFile" id="upload_form" enctype="multipart/form-data">

        <input id="file_upload" name="file_upload" type="file" multiple="false">
    </form>
    <div class="progress progress-striped active">
        <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
    </div>
<? endif; ?>

<?php if ($view->showUpload == false): ?>
    <?php if (isset($view->showAlert) && $view->showAlert == true): ?>
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $view->success; ?><br/>

        </div>
    <? endif; ?>   
    <? if ($view->editMode) : ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">

                    <form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/update" enctype="multipart/form-data" role="form" class="form-horizontal">
                    <? endif; ?>
                    <? if (!$view->editMode) : ?>
                        <form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/save" enctype="multipart/form-data" role="form" class="form-horizontal">
                        <? endif; ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputTitle"><? echo _("Title"); ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputTitle" name="title" placeholder="<?php echo _("Title"); ?>" value="<?php echo $view->video->title; ?>">
                            </div>
                        </div>            
                        <div class="form-group">
                            <label class="col-sm-2 control-label"  for="inputDescription"><? echo _("Description"); ?></label>
                            <div class="col-sm-10">
                                <textarea id="inputDescription" class="form-control" name="description" placeholder="<?php echo _("Description"); ?>"><?php echo $view->video->descripton; ?></textarea>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label class="col-sm-2 control-label" ><? echo _("Visibility"); ?></label>

                            <div class="col-sm-10">
                                <select name="visibility" class="form-control col-md-3">
                                    <option <?= ($view->video->visibility_setting == "1" ? "selected" : "") ?> value="1"><? echo _("Everyone"); ?></option>
                                    <option <?= ($view->video->visibility_setting == "2" ? "selected" : "") ?> value="2"><? echo _("Just Registered Users"); ?></option>
                                    <option <?= ($view->video->visibility_setting == "3" ? "selected" : "") ?> value="3"><? echo _("Just me"); ?></option>
                                </select>
                            </div>
                        </div>  


                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="defaultImage"><? echo _("Select Default Image"); ?></label>
                            <div id="defaultImage" class="col-sm-10">

                                <?php
                                $i = 1;
                                foreach ($view->thumbnails as $thumb) {
                                    echo '<div class="col-md-2">';
                                    echo '<img class="img-thumbnail video-thumbnail" style="cursor: pointer;" thumb="' . $i++ . '" src="' . $thumb . '" width="200">';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="hidden" name="filename" value="<?= (isset($view->filename) ? $view->filename : "" ); ?>">
                                <input type="hidden" id="thumb" name="thumb" value="<?= (isset($view->video->thumb) ? $view->video->thumb : "1"); ?>">
                                <input type="submit" class="btn btn-primary" value="<?php echo _("Save Changes"); ?>">
                                <? if ($view->editMode) : ?>
                                    <input type="hidden" name="id" value="<?= $view->video->id; ?>">
                                    <button type="submit" value="true" name="delete" class="btn"><?php echo _("Delete"); ?></button>
                                <? endif; ?>
                            </div>
                        </div>
                    </form>   
            </div>
        </div>
    </div>
    <?php
endif;
?>
