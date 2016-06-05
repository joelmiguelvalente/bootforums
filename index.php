<?php
session_start();
require("db.php");
require("config.php");
//Begin page
include("header.php");
echo '<div class="container">';
//Body content
if($config['announce'] !== ""){
	echo '<div class="alert alert-info">'.$config['announce'].'</div>';
}
if($_SESSION['username']){
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
foreach($files1 as $file){
	if($file != ".." && $file != "."){
		$file = str_replace(".dat", "", $file);
		$name = file_get_contents("$data/$file.name");
		echo '<tr><td><a href="post.php?type=view&post='.$file.'">'.$name.'</a></td><td>'.date("Y-m-d h:i:sA",filemtime("$data/$file.dat")).'</td></tr>';
	}
}
?>
</tbody>
</table>


<?php
echo '</div>';
include("footer.php");



//Misc functions
function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        if(strpos($file,".name")) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}
?>