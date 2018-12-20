<?php
/*
Submit.php - The brains of the forum, the "API" so to speak. Enjoy.
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
$type = "";
if($_POST['type']){
	$type = $_POST['type'];
}
if($_GET['type']){
	$type = $_GET['type'];
}


switch($type){

	case "reply":
		if(!$_SESSION['username']){ die(L("error.logged")); }
		if(clean($_POST['post-id']) == "" || clean($_POST['post-id']) == "-" || !isNotEmpty(clean($_POST['post-id']))){
			die(L("error.invalid.name"));
		}
		addPost($_POST['post-id'], $_POST['text'], $_SESSION['username']);
		header("Location: ./post.php?page=last&type=view&post=".clean($_POST['post-id']));
		die();
		break;
	case "new":
		if(!$_SESSION['username']){ die(L("error.logged")); }
		if(clean($_POST['post-id']) == "" || clean($_POST['post-id']) == "-" || !isNotEmpty(clean($_POST['post-id']))){
			die(L("error.invalid.name"));
		}
		if(!$config['allowNewThreads'] && !in_array($_SESSION['username'], $config['admins'])){
			header("Location: ./");
			die();
			break;
		}
		addPost($_POST['post-id'], $_POST['text'], $_SESSION['username']);
		header("Location: ./post.php?type=view&post=".clean($_POST['post-id']));
		die();
		break;
	case "reg":
		if($_POST['cap'] != $_SESSION['captcha']['code']){
			header("Location: ./register.php?msg=Captcha invalid!"); die();
		}
		if($config['registration'] == false){
			header("Location: ./register.php?msg=".L("error.registration.disabled")); die();
		}
		$u = clean($_POST['user']);
		if(strlen($u) > 12){
			$u = substr($u, 0, 12);
		}
		if(strlen($u) <= 3){
			header("Location: ./register.php?msg=".L("error.least.3"));
			die();
		}
		$msg = adduser($u, $_POST['pass']);
		if(!$msg){ header("Location: ./register.php?msg=".L("error.registration.failed")); die(); }
		header("Location: ./login.php?msg=".L("login.to.continue.registration").$u);
		die();
		break;
	case "login":
		if($_POST['cap'] != $_SESSION['captcha']['code'] && $config['captchaLoginForce'] === true){
			header("Location: ./login.php?msg=".L("error.captcha.invalid")); die();
		}
		$msg = auth($_POST['user'], $_POST['pass']);
		if(!$msg){
			header("Location: ./login.php?msg=".L("error.incorrect.userorpass"));
			die();
		}
		$_SESSION['username'] = $_POST['user'];
		header("Location: ./");
		die();
	case "edit":
		if(!$_SESSION['username']){ die(L("error.logged")); }
		if(clean($_POST['post-id']) == "" || clean($_POST['post-id']) == "-" || !isNotEmpty(clean($_POST['post-id']))){
			die(L("error.invalid.name"));
		}
		update($_POST['post-id'], $_SESSION['username'], $_POST['time'], $_POST['text'], $_POST['reply_num']);
		header("Location: ./post.php?page=last&type=view&post=".clean($_POST['post-id']));
		die();
		break;
	case "lock":
		if(!$_SESSION['username']){ die(L("error.logged")); }
		lock($_GET['post'], $_SESSION['username']);
		header("Location: ./post.php?page=last&type=view&post=".clean($_GET['post']));
		die();
		break;
	case "unlock":
		if(!$_SESSION['username']){ die(L("error.logged")); }
		unlock($_GET['post'], $_SESSION['username']);
		header("Location: ./post.php?page=last&type=view&post=".clean($_GET['post']));
		die();
		break;
	case "delete":
		if(!$_SESSION['username']){ die(L("error.logged")); }
		deletePost($_GET['post'], $_SESSION['username']);
		header("Location: ./index.php");
		die();
		break;
	case "passwd":
		if(!$_SESSION['username']){ die(L("error.logged")); }
		$msg = changePasswd($_SESSION['username'], $_POST['currPass'], $_POST['pass1'], $_POST['pass2']);
		if($msg == false){
			$msg = L("error.updating.password");
		}
		if($msg === true){
			$msg = L("error.password.changed");
		}
		header("Location: ./change.php?msg=$msg");
		die();
		break;
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
