<?php
if(isset($view->error)):
?>
    <div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> <?php echo $view->error; ?>
    </div>
<?php
endif;
?>
<form action="<?php echo Config::get('address'); ?>/user/login" method="post" accept-charset="UTF-8">
    <div class="input-prepend">
        <span class="add-on">
            <i class="icon-user"></i>
        </span>
        <input id="username" style="margin-bottom: 15px;" type="text" name="user" size="30" placeholder="<? echo _("Username"); ?>"/>
    </div>

    <div class="input-prepend">
        <span class="add-on">
            <i class="icon-exclamation-sign"></i>
        </span>                     
        <input id="password" style="margin-bottom: 15px;" type="password" name="password" size="30" placeholder="<? echo _("Password"); ?>"/>
    </div>


    <input class="btn btn-primary" type="submit" name="submit" value="Sign In" />
    <input class="btn btn-success" type="button" name="click" onclick="location.href='<?php echo Config::get('address'); ?>/user/register'" value="Register" />
</form>