<?php
session_start();
require("db.php");
require("config.php");
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


<?php


?>