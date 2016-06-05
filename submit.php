<?php
session_start();
require("db.php");
require("config.php");



switch($_POST['type']){
	
	case "reply":
		if(!$_SESSION['username']){ die("You must be logged in to do anything on these forums."); }
		if(clean($_POST['post-id']) == "" || clean($_POST['post-id']) == "-" || !isNotEmpty(clean($_POST['post-id']))){
			die("Invalid name detected, please try again!");
		}
		addPost($_POST['post-id'], $_POST['text'], $_SESSION['username']);
		header("Location: ./post.php?type=view&post=".clean($_POST['post-id']));
		die();
		break;
	case "new":
		if(!$_SESSION['username']){ die("You must be logged in to do anything on these forums."); }
		if(clean($_POST['post-id']) == "" || clean($_POST['post-id']) == "-" || !isNotEmpty(clean($_POST['post-id']))){
			die("Invalid name detected, please try again!");
		}
		addPost($_POST['post-id'], $_POST['text'], $_SESSION['username']);
		header("Location: ./post.php?type=view&post=".clean($_POST['post-id']));
		die();
		break;
	case "reg":
		if($_POST['cap'] != $_SESSION['captcha']['code']){
			header("Location: ./register.php?msg=Captcha invalid!"); die();
		}
		$msg = adduser($_POST['user'], $_POST['pass']);
		if(!$msg){ header("Location: ./register.php?msg=Registration failed!"); die(); }
		header("Location: ./login.php?msg=Login to continue registration, ".clean($_POST['user']));
		die();
		break;
	case "login":
		$msg = auth($_POST['user'], $_POST['pass']);
		if(!$msg){
			header("Location: ./login.php?msg=Incorrect username or password");
			die();
		}
		$_SESSION['username'] = $_POST['user'];
		header("Location: ./");
		die();
}
function isNotEmpty($input) 
{
    $strTemp = $input;
    $strTemp = trim($strTemp);

    if($strTemp !== '') //Also tried this "if(strlen($strTemp) > 0)"
    {
         return true;
    }

    return false;
}

?>