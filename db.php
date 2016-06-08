<?php
require "fllat.php";
require "config.php";
$usdata = $config['user_data'];
$thdata = $config['thread_data'];

function auth($username, $password){
	global $usdata, $thdata;
	$username = clean($username);
	if(!file_exists("$usdata/$username.dat")){ return false; }
	$users = new Fllat($username, $usdata);
	$pass = $users -> get("password", "username", $username);
	if(hash("SHA256", $password) == $pass){
		return true;
	} else {
		return false;
	}
}

function adduser($username, $password, $email){
	global $usdata, $thdata;
	$username = clean($username);
	$pass = hash("SHA256", $password);
	$users = new Fllat($username, $usdata);
	$de = $users -> get("username", "username", $username);
	var_dump($de);
	if($de){ return false; }
	$tmp = $users -> add(array("username"=>$username, "password"=>$pass));
	if(!$tmp){ $tmp = "Please login to continue registration:";}
	return $tmp;
}
function changePasswd($username, $currPass, $newPass){
	global $usdata, $thdata;
	$username = clean($username);
	if(!file_exists("$usdata/$username.dat")){ return false; }
	$pass = hash("SHA256", $currPass);
	$username = clean($username);
	$users = new Fllat($username, $usdata);
	$index = $users -> index("username", $username);
	if($index == null && !$index >= 0){ return false; }
	$canChange = $users ->get("password","username", $username);
	var_dump($canChange);
	
	if($canChange != $pass) { return false; }
	$cc_temp = array("username"=>$username, "password"=>hash("SHA256",$newPass));
	$tmp = $users -> update($index, $cc_temp);
	return $tmp;
}
function update($post, $user, $time, $text, $index){
	global $usdata, $thdata;
	if(!file_exists("$usdata/$user.dat")){ return false; }
	$post = clean($post);
	$post = trim($post);
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
	global $usdata, $thdata;
	//Make topic readable to file system:
	//Convert special chars. and spaces to "_"
	$username = clean($username);
	if(!file_exists("$usdata/$username.dat")){ return false; }
	$name = $topic;
	$topic = clean($topic);
	$topic = trim($topic);
	if($topic === '' || $topic === null ){
		return false;
	}
	if(!file_exists("$thdata/$topic.name")){ file_put_contents("$thdata/$topic.name",htmlspecialchars($name)); }
	$posts = new Fllat($topic , $thdata);
	$date = date("Y-m-d h:i:sa");
	$tmp = $posts -> add(array("post"=>$post, "time"=>$date, "user"=>$username));
	if(!$tmp){ return false; }
	return true;
}
function clean($string) {
	$string= str_replace('  ', '', $string);
   	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	$string = str_replace('.','', $string); //Replace '.' with nothing.
	$string = strtolower($string); //Lower case it so that everything is VERY clean
   	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

?>