<div class="row">

    <div class="large-6 columns">

        <?php
        if (isset($view->login_error)):
            ?>
            <div data-alert class="alert-box">
                <strong><?php echo _("Warning"); ?>"!</strong> <?php echo $view->login_error; ?>
            </div>
            <?php
        endif;
        ?>
        <h2><?php echo _("Login"); ?></h2> 
        <form action="<?php echo Config::get('address'); ?>/user/login" method="post" accept-charset="UTF-8"  role="form">

            <input id="username" type="text" name="user" size="30" placeholder="<?php echo _("Username"); ?>"/>
            <input id="password" type="password" name="password" size="30" placeholder="<?php echo _("Password"); ?>"/>



            <input class="button" type="submit" name="submit" value="Sign In" />

        </form>
    </div>
    <div class="large-6 columns">
        <h2><?php echo _("Register"); ?></h2>
        <?php if (isset($view->error) && $view->error == true): ?>

            <div data-alert class="alert-box">

                <ul>
                    <?php
                    foreach ($view->error_type as $key => $value)
                        echo "$value<br/>"
                        ?> 
                </ul>

            </div>
        <?php endif; ?> 

        <?php if (isset($view->success)): ?>
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h3><?php echo _("Success"); ?></h3><?php echo $view->success; ?><br/>
            </div>
        <?php endif; ?> 

        <form  action="<?php echo Config::get('address'); ?>/user/login/register" method="post" accept-charset="UTF-8">
            <input id="username"  class="form-control" type="text" name="user"  placeholder="<?php echo _("Username"); ?>"/>
            <input id="email" class="form-control" type="text" name="email"  placeholder="<?php echo _("eMail"); ?>"/>                  
            <input id="password" class="form-control" type="password" name="password1" placeholder="<?php echo _("Password"); ?>" />
            <input id="password"  type="password" name="password2" placeholder="<?php echo _("Password"); ?>"" />

            <input class="button" type="submit" name="submit" value="<?php echo _('Sign Up') ?>" />

        </form>


    </div>
</div>
