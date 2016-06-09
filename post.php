<?php
session_start();
require("db.php");
require("config.php");
include("Parsedown.php");
require("functions.php");
//Begin page
if($config['ssl'] == true){
	if($_SERVER["HTTPS"] != "on")
	{
    	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    	die();
	}
}
$usdata = $config['user_data'];
$thdata = $config['thread_data'];
include("header.php");
echo '<div class="container">';
//Body content
if($_GET['type'] == "new"){
	?>
	<div class="page-header">
	  	<h1>New Post</h1>
	</div>
	<form action="submit.php" method="POST" id="new" role="form">
		<input style="display: none;" name="type" id="type" value="new"></input>
		<input class="form-control" name="post-id" id="post-id" placeholder="Enter Post Name here"></input><br />
		<textarea name="text" id="text" rows="10" cols="100%" class="form-control"></textarea><br />
		<button type="submit" class="btn btn-info lgn pull-right">Submit</button>
	</form>
	<script type="text/javascript">

    $(document).ready(function() {
        document.title = 'New post';
    });

</script>
	<?php	
}
if($_GET['type'] == "reply"){
	?>
	<div class="page-header">
	  	<h1>Reply to: <?php $to = $_GET['post']; echo file_get_contents("$thdata/$to.name"); ?></h1>
	</div>
	<?php
	if(!file_exists("$thdata/".$_GET['post'].'.dat')){ echo "That post does not exist!";} else {
	?>
	<p>You may use <a href="https://daringfireball.net/projects/markdown/syntax" target="_blank">Markdown</a> in the text area.</p>
	<form action="submit.php" method="POST">
		<input style="display: none;" name="type" id="type" value="reply"></input>
		<input style="display: none;" name="post-id" id="post-id" value="<?php echo $_GET['post']; ?>"></input>
		<textarea name="text" id="text" rows="10" cols="100%" class="form-control"></textarea><br />
		<button type="submit" class="btn btn-primary pull-right">Submit</button>
	</form>
<script type="text/javascript">

    $(document).ready(function() {
        document.title = '<?php echo $config['title']." | Reply: "; $to = $_GET['post']; echo file_get_contents("$thdata/$to.name"); ?>';
    });

</script>
	<?php
	}
}
if($_GET['type'] == "edit"){
	?>
	<div class="page-header">
	  	<h1>Edit Reply to: <?php $to = $_GET['post']; echo file_get_contents("$thdata/$to.name"); ?><br /><small>Post Number: <?php echo $_GET['reply_num']; ?></small></h1>
	</div>
	<?php
	if(!file_exists("$thdata/".$_GET['post'].'.dat')){ echo "That post does not exist!";} else {
	?>
	<p>You may use <a href="https://daringfireball.net/projects/markdown/syntax" target="_blank">Markdown</a> in the text area.</p>
	<form action="submit.php" method="POST">
		<input style="display: none;" name="type" id="type" value="edit"></input>
		<input style="display: none;" name="reply_num" id="reply_num" value="<?php echo $_GET['reply_num']; ?>"></input>
		<input style="display: none;" name="post-id" id="post-id" value="<?php echo $_GET['post']; ?>"></input>
		<?php 
			$post_id = clean($_GET['post']);
			$posts = new Fllat($post_id , $thdata);
			$p = $posts -> select();
			$temp_time = "";
			$temp_text = "";
			foreach($p as $pp){
				$k = array_search($pp, $p);
				$k++;
				if($pp['user'] == $_SESSION['username'] && $k == $_GET['reply_num']){
					$temp_text = $pp['post'];
					$temp_time = $pp['time'];
					break;
				}
			}
			if($temp_text == "" || $temp_text == null){
				echo 'You do not have permission to edit this reply! Please contact the web master.';
			} else {
				echo '<textarea name="text" id="text" rows="10" cols="100%" class="form-control">'.$temp_text.'</textarea><br />
				<input style="display: none;" name="time" id="time" value="'.$temp_time.'"></input>
				<button type="submit" class="btn btn-info pull-right">Submit</button>
				';
			}
		?>
		
		
	</form>
<script type="text/javascript">

    $(document).ready(function() {
        document.title = '<?php echo $config['title']." | Reply: "; $to = $_GET['post']; echo file_get_contents("$thdata/$to.name"); ?>';
    });

</script>
	<?php
	}
}
if($_GET['type'] == "view"){
	$post_id = clean($_GET['post']);
	?>
	<div class="page-header">
		<?php
		if(file_exists("$thdata/$post_id.lock") || file_exists("$thdata/$post_id.lockadmin")){
			echo '<div class="alert alert-warning">This thread has been locked.</div>';	
		}
		?>
	  	<h1>View: <?php $to = $_GET['post']; echo file_get_contents("$thdata/$to.name"); ?></h1>
	</div>
	<?php
	
	if($_SESSION['username']){
		if(!file_exists("$thdata/$post_id.lock") && !file_exists("$thdata/$post_id.lockadmin")){
			?>
			<a href="post.php?type=reply&post=<?php echo $_GET['post']; ?>" class="btn btn-primary">Reply to post</a>
			<?php
		}
		if(in_array($_SESSION['username'], $config['admins'])){
			if(!file_exists("$thdata/$post_id.lock") && !file_exists("$thdata/$post_id.lockadmin")){
				echo '&nbsp;&nbsp;<a href="./submit.php?type=lock&post='.$post_id.'" class="btn btn-primary btn-sm">Lock Thread</a><br />';
			} else {
				echo '&nbsp;&nbsp;<a href="./submit.php?type=unlock&post='.$post_id.'" class="btn btn-primary btn-sm">Unlock Thread</a><br />';
			}
		}
	}
	?>
	
	<?php
	
	if(!file_exists("$thdata/$post_id.dat")){ echo "This post does not exist!"; } else {
	$posts = new Fllat($post_id , $thdata);
	$p = $posts -> select();
	$canLock = $posts -> canUpdatePost(0, $_SESSION['username']);
	if($canLock){
		if(!file_exists("$thdata/$post_id.lock") && !file_exists("$thdata/$post_id.lockadmin")){
			if(!in_array($_SESSION['username'], $config['admins'])){ echo '&nbsp;&nbsp;<a href="./submit.php?type=lock&post='.$post_id.'" class="btn btn-primary btn-sm">Lock Thread</a><br />'; }
		} elseif(!file_exists("$thdata/$post_id.lockadmin")) {
			if(!in_array($_SESSION['username'], $config['admins'])) {echo '&nbsp;&nbsp;<a href="./submit.php?type=unlock&post='.$post_id.'" class="btn btn-primary btn-sm">Unlock Thread</a><br />';}
		}
	} else {
		echo '<br />';
	}
	if(isAdmin($_SESSION['username'])){
		echo '<a href="./submit.php?type=delete&post='.$post_id.'" class="btn btn-danger btn-sm">Delete Thread</a><br />';
	}
	$page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;
	if($_GET['page'] == "first" || $_GET['page'] == "last"){
		$page = $_GET['page'];
		if($page == "first"){
			$page = 1;
		}
		if($page == "last"){
			$page = count($p);
		}
	}
	$total = count( $p ); //total items in array    
	$limit = $config['perPageThread']; //per page    
	$totalPages = ceil( $total/ $limit ); //calculate total pages
	$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
	$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
	$offset = ($page - 1) * $limit;
	if( $offset < 0 ) $offset = 0;
	$p = array_slice( $p, $offset, $limit ,true);
	echo '<ul class="pagination">';
	echo '<li><a href="./post.php?page=first&type=view&post='.$post_id.'">First</a></li>';
	for($i = 1; $i <= $totalPages; $i++){
		if($i == $page){
			echo '<li class="active"><a href="./post.php?page='.$i.'&type=view&post='.$post_id.'">'.$i.'</a></li>';
		} else {
			echo '<li><a href="./post.php?page='.$i.'&type=view&post='.$post_id.'">'.$i.'</a></li>';
		}
	}
	echo '<li><a href="./post.php?page=last&type=view&post='.$post_id.'">Last</a></li>';
	echo "</ul>";
	foreach($p as $pp){
		$k = array_search($pp, $p);
		$k++;
		$pmd= Parsedown::instance()
   		->setMarkupEscaped(true) # escapes markup (HTML)
   		->text($pp['post']);
   		if($pp['user'] == $_SESSION['username']){
   			$edit = '<a href="./post.php?type=edit&post='.$post_id.'&reply_num='.$k.'">Edit Reply</a>';
   		} else {
   			$edit = "";
   		}
		echo '<div class="panel panel-default">
  				<div class="panel-heading"><b>'.$pp['user'].'</b> @ '.$pp['time'].'&nbsp;&nbsp;'.$edit.'<span class="pull-right">#'.$k.'</span></div>
  				<div class="panel-body">'.$pmd.'</div>
			</div>';
	}
	echo '<ul class="pagination">';
	echo '<li><a href="./post.php?page=first&type=view&post='.$post_id.'">First</a></li>';
	for($i = 1; $i <= $totalPages; $i++){
		if($i == $page){
			echo '<li class="active"><a href="./post.php?page='.$i.'&type=view&post='.$post_id.'">'.$i.'</a></li>';
		} else {
			echo '<li><a href="./post.php?page='.$i.'&type=view&post='.$post_id.'">'.$i.'</a></li>';
		}
	}
	echo '<li><a href="./post.php?page=last&type=view&post='.$post_id.'">Last</a></li>';
	echo "</ul>";
	
	}
?><br />
<?php 
	if($_SESSION['username']){
		if(!file_exists("$thdata/$post_id.lock") && !file_exists("$thdata/$post_id.lockadmin")){
			?>
			<a href="post.php?type=reply&post=<?php echo $_GET['post']; ?>" class="btn btn-primary">Reply to post</a>
			<?php
		}
	}
	?>
<script type="text/javascript">

    $(document).ready(function() {
        document.title = '<?php echo $config['title']." | "; $to = $_GET['post']; echo file_get_contents("$thdata/$to.name"); ?>';
    });

</script>
<?php	

}
echo '</div>';
include("footer.php");
?>