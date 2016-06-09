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
					<li><a href="./">Home</a></li>
					<li><a href="register.php" <?php if($_SESSION['username']){ echo "style=\"display: none;\""; } ?>>Register</a></li>
					<li><a href="login.php" <?php if($_SESSION['username']){ echo "style=\"display: none;\""; } ?>>Log in</a></li>
				</ul>
									<?php
			if($_SESSION['username']){
				echo '';
				?>
            	<ul class="nav navbar-nav pull-right">
					<li><a href="change.php">Change Password</a></li>
					<li><a href="logout.php">Logout <?php echo $_SESSION['username'];?></a></li>
				</ul>
				<?php
			}	
		?>
			</div>
 		</nav>

