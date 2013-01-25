<?php if (isset($view->error) && $view->error==true): ?>

     <div class="alert alert-error">
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

<form action="<?php echo Config::get('address'); ?>/user/register" method="post" accept-charset="UTF-8">
    <div class="input-prepend">
        <span class="add-on">
            <i class="icon-user"></i>
        </span>
        <input id="username" style="margin-bottom: 15px;" type="text" name="user" size="30" placeholder="Username"/>
    </div>
    <div class="input-prepend">
        <span class="add-on">
            <i class="icon-envelope"></i>
        </span>
        <input id="email" style="margin-bottom: 15px;" type="text" name="email" size="30" placeholder="eMail"/>
    </div>     
    <div class="input-prepend">
        <span class="add-on">
            <i class="icon-exclamation-sign"></i>
        </span>                     
        <input id="password" style="margin-bottom: 15px;" type="password" name="password1" placeholder="Password" size="30" />
    </div>
     
    <div class="input-prepend">
        <span class="add-on">
            <i class="icon-exclamation-sign"></i>
        </span>                     
        <input id="password" style="margin-bottom: 15px;" type="password" name="password2" placeholder="Password" size="30" />
    </div>
                   
                    

    <input class="btn btn-primary" type="submit" name="submit" value="Register" />
    
</form>