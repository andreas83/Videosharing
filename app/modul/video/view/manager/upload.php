<? if ($view->showUpload == true): ?>
    <div class="row">


        <form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/uploadFile" id="upload_form" enctype="multipart/form-data">
            <input id="file_upload" name="file_upload" type="file" multiple="false">

            <div class="progress large-6">
                <span class="meter" style="width: 1%"></span>
            </div>
            <span id="upload" class="small button">Upload</span>
        </form>
    </div>
<? endif; ?>

<?php if ($view->showUpload == false): ?>
    <?php if (isset($view->showAlert) && $view->showAlert == true): ?>
        <div class="row">
            <div class="large-12 columns">
                <div class="alert-box success radius">

                    <?php echo $view->success; ?><br/>

                </div>
            </div>
        </div>
    <? endif; ?>   
    <div class="row">
        <div class="large-12 columns">
            <? if ($view->editMode) : ?>



                <form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/update" enctype="multipart/form-data">
                <? endif; ?>
                <? if (!$view->editMode) : ?>

                    <form method="POST" action="<?php echo Config::get('address'); ?>/video/manager/save" enctype="multipart/form-data">
                    <? endif; ?>

                    <label for="inputTitle"><? echo _("Title"); ?></label>

                    <input type="text"  id="inputTitle" name="title" placeholder="<?php echo _("Title"); ?>" value="<?php echo $view->video->title; ?>">


                    <label  for="inputDescription"><? echo _("Description"); ?></label>

                    <textarea id="inputDescription"  name="description" placeholder="<?php echo _("Description"); ?>"><?php echo $view->video->descripton; ?></textarea>

                    <label ><? echo _("Visibility"); ?></label>


                    <select name="visibility">
                        <option <?= ($view->video->visibility_setting == "1" ? "selected" : "") ?> value="1"><? echo _("Everyone"); ?></option>
                        <option <?= ($view->video->visibility_setting == "2" ? "selected" : "") ?> value="2"><? echo _("Just Registered Users"); ?></option>
                        <option <?= ($view->video->visibility_setting == "3" ? "selected" : "") ?> value="3"><? echo _("Just me"); ?></option>
                    </select>





                    <label for="defaultImage"><? echo _("Select Default Image"); ?></label>
                    <div id="defaultImage" class="row">

                        <?php
                        $i = 1;
                        foreach ($view->thumbnails as $thumb) {
                            echo '<div class="large-4 columns">';
                            echo '<img class="img-thumbnail video-thumbnail" style="cursor: pointer;" thumb="' . $i++ . '" src="' . $thumb . '" width="200">';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    
                    <div class="row"> 
                        <div class="large-12 columns">
                            <input type="hidden" name="filename" value="<?= (isset($view->filename) ? $view->filename : "" ); ?>">
                            <input type="hidden" id="thumb" name="thumb" value="<?= (isset($view->video->thumb) ? $view->video->thumb : "1"); ?>">
                            <input type="submit" class="button" value="<?php echo _("Save Changes"); ?>">
                            <? if ($view->editMode) : ?>
                                <input type="hidden" name="id" value="<?= $view->video->id; ?>">
                                <button type="submit" value="true" name="delete" class="btn"><?php echo _("Delete"); ?></button>
                            <? endif; ?>
                        </div>
                    </div>

                </form>   
        </div>
    </div>
        <?php
    endif;
    ?>
