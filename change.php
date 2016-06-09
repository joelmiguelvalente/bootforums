<?php
/*
change.php - The changer of things. change your password in this simple, easy to look at form.
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
require("functions.php");
//Force SSL if config says so.
if($config['ssl'] == true){
	if($_SERVER["HTTPS"] != "on")
	{
    	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    	die();
	}
}

//Begin page
include("header.php");
echo '<div class="container">';
?>

<div class="page-header">
  <h1>Change Current Password</h1>
</div>
<?php if($_GET['msg']){ echo '<p style="color:black;">'.$_GET['msg'].'</p>'; } ?>
<form action="submit.php" method="POST">
	<input type="hidden" name="type" id="type" value="passwd">
	<div class="form-group">
	    <label for="user">Current Password:</label>
	    <input type="password" class="form-control" id="currPass" name="currPass">
  	</div>
  	<br />
  	<div class="form-group">
	    <label for="pass">New Password:</label>
	    <input type="password" class="form-control" id="pass1" name="pass1">
  	</div>
	<div class="form-group">
	    <label for="pass">Verify Password:</label>
	    <input type="password" class="form-control" id="pass2" name="pass2">
  	</div>
	<button type="submit" class="btn btn-primary pull-right">Submit</button>
	
</form>
</div>

<?php
include("footer.php");

?>