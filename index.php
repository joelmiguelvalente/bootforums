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
//Body content
if($config['announce'] !== ""){
	echo '<div class="alert alert-info">'.$config['announce'].'</div>';
}
if($_SESSION['username'] && $config['allowNewThreads'] !== false){
	echo '<a href="post.php?type=new" class="btn btn-primary">New Post</a><br />';
}
if(!$config['allowNewThreads']){
	echo '<div class="alert alert-warning">New thread creation has been locked by the Forum Administrator.</div>';
}
if(in_array($_SESSION['username'], $config['admins']) && $config['allowNewThreads'] === false){
	echo '<a href="post.php?type=new" class="btn btn-primary">New Post</a><br />';
}
?>
<div class="page-header">
  <h1>Latest Posts</h1>
</div>
<table class="table">
	<thead>
		<th>Post</th>
		<th>Last Updated</th>
	</thead>
	<tbody>
<?php
$files1 = scan_dir($config['thread_data']);
$data = $config['thread_data'];
$page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;
$total = count( $files1 ); //total items in array    
$limit = $config['perPage']; //per page    
$totalPages = ceil( $total/ $limit ); //calculate total pages
$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
$offset = ($page - 1) * $limit;
if( $offset < 0 ) $offset = 0;
$files1 = array_slice( $files1, $offset, $limit );
foreach($files1 as $file){
	if($file != ".." && $file != "."){
		$file = str_replace(".dat", "", $file);
		$name = file_get_contents("$data/$file.name");
		echo '<tr><td><a href="post.php?page=1&type=view&post='.$file.'">'.$name.'</a> | <a style="font-size:9px;" href="post.php?page=last&type=view&post='.$file.'">Jump to last</a></td><td>'.date("Y-m-d h:i:sA",filemtime("$data/$file.dat")).'</td></tr>';
	}
}
?>
</tbody>
</table>


<?php
echo '<ul class="pagination">';
echo '<li><a href="./?page=1">First</a></li>';
for($i = 1; $i <= $totalPages; $i++){
	if($i == $page){
		echo '<li class="active"><a href="./?page='.$i.'">'.$i.'</a></li>';
	} else {
		echo '<li><a href="./?page='.$i.'">'.$i.'</a></li>';
	}
}
echo '<li><a href="./?page='.$totalPages.'">Last</a></li>';
echo "</ul>";
echo '</div>';
include("footer.php");



//Misc functions
function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        if(strpos($file,".name")) continue;
        if(strpos($file,".lock")) continue;
        if(strpos($file,".lockadmin")) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}
?>