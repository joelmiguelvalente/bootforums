<?php
session_start();
require("db.php");
require("config.php");
require("simple-php-captcha.php");
//Begin page
include("header.php");
$_SESSION['captcha'] = simple_php_captcha( $config['captchaLogin']);
echo '<div class="container">';
//Body content
?>
<div class="page-header">
  <h1>Log In</h1>
</div>
<?php if($_GET['msg']){ echo '<p style="color:red;">'.$_GET['msg'].'</p>'; } ?>
<form action="submit.php" method="POST">
	<input type="hidden" name="type" id="type" value="login" />
	<div class="form-group">
    <label for="user">Username:</label>
    <input type="username" class="form-control" id="user" name="user">
  </div>
  <div class="form-group">
    <label for="pass">Password:</label>
    <input type="password" class="form-control" id="pass" name="pass">
  </div>
  	<?php 
  	if($config['captchaLoginForce'] === true){
  		echo '';
  		?>
  		  <div class="form-group">
  			<label for="cap">Captcha Request:</label><br />
  			<img src="<?php echo $_SESSION['captcha']['image_src']; ?>">&nbsp;&nbsp;<input type="text" name="cap" id="cap" rows="8">
  		</div>
  		<?php
  	}
  	?>
  <button type="submit" class="btn btn-primary pull-right">Submit</button>
</form>
<?php
echo '</div>';
include("footer.php");
?>