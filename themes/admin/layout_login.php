<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<?php
$this->meta->css( $this->theme_admin_url . "css/login.css" );
echo $this->meta->display( false );
?>
</head>

<body>
  <div class="login-card">
    <h1>Log-in</h1><br>
    <?php echo get_msg(); ?>
  <form method="post" action="<?php echo site_url('auth/login').$url_redirect;?>">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <input type="submit" name="log_in" class="login login-submit" value="login">
  </form>
  <div class="login-help">

  </div>
</div>
</body>

</html>