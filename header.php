<?php
/*
header.php - The top of any web page needs a proper header, this generates the menu and puts css, js, and other header stuff onto the page.
Script Created by Mitchell Urgero
Date: Sometime in 2016 ;)
Website: https://urgero.org
E-Mail: info@urgero.org

Script is distributed with Open Source Licenses, do what you want with it. ;)
"I wrote this because I saw that there are not that many databaseless Forums for PHP. It needed to be done. I think it works great, looks good, and is VERY mobile friendly. I just hope at least one other person
finds this PHP script as useful as I do."

*/
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $config['title']; ?></title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="<?php echo $config['desc']; ?>">
		<meta name="author" content="">
		<link href="css/<?php echo themeSelector(); ?>" rel="stylesheet">
		<link href="css/bootstrapvalidator.min.css" rel="stylesheet">
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrapvalidator.min.js"></script>
		<script src="js/tinymce/tinymce.min.js"></script>
  		<script>
  			//tinymce.init({ selector:'textarea' });
  		</script>
	</head>
	
	<body>
		<nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./"><?php echo $config['title']; ?></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            	<ul class="nav navbar-nav">
					<li><a href="<?php echo $config['home']; ?>">Home</a></li>
					<li><a href="register.php" <?php if($_SESSION['username']){ echo "style=\"display: none;\""; } ?>>Register</a></li>
					<li><a href="login.php" <?php if($_SESSION['username']){ echo "style=\"display: none;\""; } ?>>Log in</a></li>
				</ul>
									<?php
			if($_SESSION['username']){
				echo '';
				?>
            	<ul class="nav navbar-nav pull-right">
            		<li><form action="search.php" method="GET"><input type="text" class="form-control" name="search" id="search" placeholder="Search..."></form></li>
					<li><a href="change.php">Change Password</a></li>
					<li><a href="logout.php">Logout <?php echo $_SESSION['username'];?></a></li>
				</ul>
				<?php
			}	
		?>
			</div>
 		</nav>

