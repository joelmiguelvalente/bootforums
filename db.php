<?php
/*
db.php - Handles Fllat script database files. Really a lot of the meat of the forum is in here. Registration backend, login backend, and thread backend for EVERYTHING
Script Created by Mitchell Urgero
Date: Sometime in 2016 ;)
Website: https://urgero.org
E-Mail: info@urgero.org

Script is distributed with Open Source Licenses, do what you want with it. ;)
"I wrote this because I saw that there are not that many databaseless Forums for PHP. It needed to be done. I think it works great, looks good, and is VERY mobile friendly. I just hope at least one other person
finds this PHP script as useful as I do."

*/
require "fllat.php";
$usdata = $config['user_data'];
$thdata = $config['thread_data'];
$log_file = $config['log'];

function auth($username, $password){
	global $usdata, $thdata, $log_file;
	$username = clean($username);
	if(!file_exists("$usdata/$username.dat")){ return false; }
	$users = new Fllat($username, $usdata);
	$pass = $users -> get("password", "username", $username);
	if(password_verify($password, $pass)){
		write_log("[AUTH] $username has logged in successfully.", $log_file);
		return true;
	} else {
		write_log("[AUTH] $username attemped login with password '$password': failed.", $log_file);
		return false;
	}
}

function adduser($username, $password, $email){
	global $usdata, $thdata, $log_file;
	$username = clean($username);
	if(strlen($username) > 12){
		$username = substr($username, 0, 12);
	}
	if(strlen($username) <= 3){
		return "Username must be at least 4 characters long!";
	}
	$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
	$options = array('cost' => 11,'salt'=> $salt);
	$pass = password_hash($password, PASSWORD_BCRYPT, $options);
	$users = new Fllat($username, $usdata);
	$de = $users -> get("username", "username", $username);
	if($de){ return false; }
	$tmp = $users -> add(array("username"=>$username, "password"=>$pass));
	if(!$tmp){ $tmp = "Please login to continue registration:"; write_log("[REG] $username was registered.", $log_file);}
	return $tmp;
}
function changePasswd($username, $currPass, $newPass1, $newPass2){
	global $usdata, $thdata, $log_file;
	$username = clean($username);
	if($newPass1 != $newPass2){
		return "The new password does not match verification, please try again!";
	}
	if(!file_exists("$usdata/$username.dat")){ return "Username does not exist"; }
	$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
	$options = array('cost' => 11,'salt'=> $salt);
	$username = clean($username);
	$users = new Fllat($username, $usdata);
	$index = $users -> index("username", $username);
	if($index === null && !$index >= 0){ return "Could not find a password?"; }
	$canChange = $users ->get("password","username", $username);
	$pass = password_verify($currPass, $canChange);
	if(!$pass) {write_log("[PAS] $username attempted to change password, But failed.", $log_file); return "Current Password Mismatch"; }
	$cc_temp = array("username"=>$username, "password"=>password_hash($newPass1, PASSWORD_BCRYPT, $options));
	$tmp = $users -> update($index, $cc_temp);
	if($tmp){
		write_log("[PAS] $username successfully changed password.", $log_file);
		$tmp = "Password changed successfully!";
	} else {
		write_log("[PAS] Unknown error while changing password for $username. Please confirm with apache/httpd/nginx logs.", $log_file);
		$tmp = "There was an error changing your password, please contact the web master.";
	}
	return $tmp;
}
function update($post, $user, $time, $text, $index){
	global $usdata, $thdata, $log_file;
	if(!file_exists("$usdata/$user.dat")){ return false; }
	$post = clean($post);
	$post = trim($post);
	if($text == ""){ return false; }
	if(file_exists("$thdata/$post.lock") || file_exists("$thdata/$post.lockadmin")){ return false; }
	$posts = new Fllat($post , $thdata);
	if($posts -> canUpdatePost($index - 1, $user)){
		$tmp = array("post"=>$text, "time" =>$time, "user"=>$user);
		if($index == null && !$index >= 0){ return false; }
		$posts -> update($index - 1, $tmp);
		return true;
	} else {
		return false;
	}

}
function addPost($topic, $post, $username){
	global $usdata, $thdata, $log_file;
	//Make topic readable to file system:
	//Convert special chars. and spaces to "_"
	$username = clean($username);
	if(!file_exists("$usdata/$username.dat")){ return false; }
	$name = clean60($topic);
	if(strlen($topic) > 40){
		//$topic = substr($topic, 0, 40);
	}
	$topic = clean($topic);
	$topic = trim($topic);
	
	if($topic === '' || $topic === null || $post === ''){
		return false;
	}
	
	if(file_exists("$thdata/$topic.lock") || file_exists("$thdata/$topic.lockadmin")){ return false; }
	if(!file_exists("$thdata/$topic.name")){ file_put_contents("$thdata/$topic.name",htmlspecialchars($name)); }
	$posts = new Fllat($topic , $thdata);
	$date = date("Y-m-d h:i:sa");
	$tmp = $posts -> add(array("post"=>$post, "time"=>$date, "user"=>$username));
	if(!$tmp){ return false; }
	return true;
}
function lock($thread, $user){
	global $usdata, $thdata, $log_file;
	$user = clean($user);
	$thread = clean($thread);
	if(file_exists("$thdata/$thread.dat")){
		$posts = new Fllat($thread , $thdata);
		$canLock = $posts -> canUpdatePost(0, $user);
		if(isAdmin($user)){
			file_put_contents("$thdata/$thread.lockadmin", "");
			return true;
		}
		if($canLock){
			file_put_contents("$thdata/$thread.lock", "");
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function unlock($thread, $user){
	global $usdata, $thdata, $log_file;
	$user = clean($user);
	$thread = clean($thread);
	if(file_exists("$thdata/$thread.dat")){
		$posts = new Fllat($thread , $thdata);
		$canLock = $posts -> canUpdatePost(0, $user);
		if(isAdmin($user)){
			if(file_exists("$thdata/$thread.lock")){unlink("$thdata/$thread.lock");}
			if(file_exists("$thdata/$thread.lockadmin")){unlink("$thdata/$thread.lockadmin");}
			return true;
		}
		if($canLock && !file_exists("$thdata/$thread.lockadmin")){
			if(file_exists("$thdata/$thread.lock")){unlink("$thdata/$thread.lock");}
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
	
}
function deletePost($thread, $user){
	global $thdata, $log_file;
	if(isAdmin($user)){
		if(file_exists("$thdata/$thread.lock")){unlink("$thdata/$thread.lock");}
		if(file_exists("$thdata/$thread.lockadmin")){unlink("$thdata/$thread.lockadmin");}
		if(file_exists("$thdata/$thread.dat")){unlink("$thdata/$thread.dat");}
		if(file_exists("$thdata/$thread.name")){unlink("$thdata/$thread.name");}
		return true;
	}
	return false;
}
function isAdmin($user){
	global $config;
	if($config['admins'] == array(0=>"")){ return false; }
	if(in_array($user, $config['admins'])){ return true; }else{return false;}
}
function clean($string) {
	$string= str_replace('  ', '', $string);
   	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	$string = str_replace('.','', $string); //Replace '.' with nothing.
	$string = strtolower($string); //Lower case it so that everything is VERY clean
	if(strlen($string) > 40){
		$string = substr($string, 0, 40);
	}
   	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function clean60($string) {
	if(strlen($string) > 60){
		$string = substr($string, 0, 60);
	}
   	return $string; //preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function write_log($message, $logfile='') {
  // Determine log file
  if($logfile == '') {
    // checking if the constant for the log file is defined
    if (defined(DEFAULT_LOG) == TRUE) {
        $logfile = DEFAULT_LOG;
    }
    // the constant is not defined and there is no log file given as input
    else {
        error_log('No log file defined!',0);
        return array(status => false, message => 'No log file defined!');
    }
  }
 
  // Get time of request
  if( ($time = $_SERVER['REQUEST_TIME']) == '') {
    $time = time();
  }
 
  // Get IP address
  if( ($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
    $remote_addr = "REMOTE_ADDR_UNKNOWN";
  }
 
  // Get requested script
  if( ($request_uri = $_SERVER['REQUEST_URI']) == '') {
    $request_uri = "REQUEST_URI_UNKNOWN";
  }
 
  // Format the date and time
  $date = date("Y-m-d H:i:s", $time);
 
  // Append to the log file
  if($fd = @fopen($logfile, "a")) {
    $result = fputcsv($fd, array($date, $remote_addr, $request_uri, $message));
    fclose($fd);
 
    if($result > 0)
      return array(status => true);  
    else
      return array(status => false, message => 'Unable to write to '.$logfile.'!');
  }
  else {
    return array(status => false, message => 'Unable to open log '.$logfile.'!');
  }
}
?>