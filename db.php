<?php
require "fllat.php";


function auth($username, $password){
	$username = clean($username);
	if(!file_exists("./data-users/$username.dat")){ return false; }
	$users = new Fllat($username, "./data-users");
	$pass = $users -> get("password", "username", $username);
	if(hash("SHA256", $password) == $pass){
		return true;
	} else {
		return false;
	}
}

function adduser($username, $password, $email){
	$username = clean($username);
	$pass = hash("SHA256", $password);
	$users = new Fllat($username, "./data-users");
	$de = $users -> get("username", "username", $username);
	var_dump($de);
	if($de){ return false; }
	$tmp = $users -> add(array("username"=>$username, "password"=>$pass));
	if(!$tmp){ $tmp = "Please login to continue registration:";}
	return $tmp;
}
function changePasswd($username, $currPass, $newPass){
	$username = clean($username);
	if(!file_exists("./data-users/$username.dat")){ return false; }
	$pass = hash("SHA256", $currPass);
	$username = clean($username);
	$users = new Fllat($username, "./data-users");
	$index = $users -> index("username", $username);
	var_dump($index);
	if($index == null && !$index >= 0){ return false; }
	$canChange = $users ->get("password","username", $username);
	var_dump($canChange);
	
	if($canChange != $pass) { return false; }
	$cc_temp = array("username"=>$username, "password"=>hash("SHA256",$newPass));
	$tmp = $users -> update($index, $cc_temp);
	return $tmp;
}

function addPost($topic, $post, $username){
	//Make topic readable to file system:
	//Convert special chars. and spaces to "_"
	$username = clean($username);
	if(!file_exists("./data-users/$username.dat")){ return false; }
	$name = $topic;
	$topic = clean($topic);
	$topic = trim($topic);
	if($topic === ''){
		$topic = "NULLName";
		$name = "NULL Name";
	}
	if(!file_exists("./data/$topic.name")){ file_put_contents("./data/$topic.name",htmlspecialchars($name)); }
	$posts = new Fllat($topic , "./data");
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