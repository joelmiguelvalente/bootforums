<?php
/*
register.php - Register? You mean I have to register to use the forum???
Script Created by Mitchell Urgero
Date: Sometime in 2016 ;)
Website: https://urgero.org
E-Mail: info@urgero.org

Script is distributed with Open Source Licenses, do what you want with it. ;)
"I wrote this because I saw that there are not that many databaseless Forums for PHP. It needed to be done. I think it works great, looks good, and is VERY mobile friendly. I just hope at least one other person
finds this PHP script as useful as I do."

*/
session_start();
require("db.php");
require("config.php");
require("simple-php-captcha.php");
require("functions.php");
if($config['ssl'] == true){
	if($_SERVER["HTTPS"] != "on")
	{
    	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    	die();
	}
}
$_SESSION['captcha'] = simple_php_captcha( $config['captcha']);
//Begin page
include("header.php");
echo '<div class="container">';
//Body content
echo '<div class="page-header">
  <h1>Registration</h1>
</div>';
if($config['registration'] == true){
?>
<?php if($_GET['msg']){ echo '<p style="color:red;">'.$_GET['msg'].'</p>'; } ?>
<form action="submit.php" method="POST">
	<input type="hidden" name="type" id="type" value="reg" />
	<div class="form-group">
    <label for="user">Username:</label>
    <input type="username" class="form-control" id="user" name="user">
  </div>
  <div class="form-group">
    <label for="pass">Password:</label>
    <input type="password" class="form-control" id="pass" name="pass">
  </div>
  <div class="form-group">
  	<label for="cap">Captcha Request:</label><br />
  	<img src="<?php echo $_SESSION['captcha']['image_src']; ?>">&nbsp;&nbsp;<input type="text" name="cap" id="cap" rows="8">
  </div>
  <button type="submit" class="btn btn-primary pull-right">Submit</button>
  
</form>
<?php
} else {
	echo "<p>Registration is currently disabled by the site administrator. If you think you got this in error, please contact the web master.</p>";
}

echo '</div>';
include("footer.php");
?>