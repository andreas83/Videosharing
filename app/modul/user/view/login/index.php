<div class="col-md-4">
    <h2><?=_("Login"); ?></h2>
<?php
if(isset($view->login_error)):
?>
    <div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> <?php echo $view->login_error; ?>
    </div>
<?php
endif;
?>
<form action="<?php echo Config::get('address'); ?>/user/login" method="post" accept-charset="UTF-8">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="glyphicon glyphicon-user"></i>
        </span>
        <input id="username" class="form-control"  type="text" name="user" size="30" placeholder="<? echo _("Username"); ?>"/>
    </div>

    <div class="input-group">
        <span class="input-group-addon">
            <i class="glyphicon glyphicon-exclamation-sign"></i>
        </span>                     
        <input id="password" class="form-control"  type="password" name="password" size="30" placeholder="<? echo _("Password"); ?>"/>
    </div>


    <input class="btn btn-primary" type="submit" name="submit" value="Sign In" />
    
</form>
</div>
<div class="col-md-4">
    <h2><?=_("Register"); ?></h2>
<?php if (isset($view->error) && $view->error==true): ?>

     <div class="alert alert-danger">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
         <ul>
         <?php foreach ($view->error_type as $key => $value)
         echo "$value<br/>"
         ?> 
        </ul>
     </div>
<?php endif; ?> 

<?php if (isset($view->success) ): ?>
     <div class="alert alert-info">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
     <h3>Success</h3><?php echo $view->success; ?><br/>
     </div>
<?php endif; ?> 

<form action="<?php echo Config::get('address'); ?>/user/login/register" method="post" accept-charset="UTF-8">
    <div class="input-group">
        <span class="input-group-addon">  
            <i class="glyphicon glyphicon-user"></i>
        </span>
        <input id="username"  class="form-control" type="text" name="user"  placeholder="Username"/>
    </div>
    
    
    
    <div class="input-group">
        <span class="input-group-addon">
            <i class="glyphicon glyphicon-envelope"></i>
        </span>
        <input id="email" class="form-control" type="text" name="email"  placeholder="eMail"/>
    </div>     
    <div class="input-group">
        <span class="input-group-addon">
            <i class="glyphicon glyphicon-exclamation-sign"></i>
        </span>                     
        <input id="password" class="form-control" type="password" name="password1" placeholder="Password" />
    </div>
     
    <div class="input-group">
        <span class="input-group-addon">
            <i class="glyphicon glyphicon-exclamation-sign"></i>
        </span>                     
        <input id="password" class="form-control" type="password" name="password2" placeholder="Password" />
    </div>
                   
                    

    <input class="btn btn-primary" type="submit" name="submit" value="<?=_('Sign Up')?>" />
    
</form>


</div>