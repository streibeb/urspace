<?php
include_once("config.php");
//start session
session_start();
// if user not logged in, redirect to homepage
if(!isset($_SESSION['login_user']))
{
	header('Location: index.html');
}

if (isset($_GET["a"])) {
	$uid = $_SESSION['login_user'];
	$pid = $_GET["a"];

	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

// Get post
	$query = "SELECT p.*,
	(SELECT 1 FROM ReportedPosts
		WHERE ReportedPosts.userId = '$uid' AND p.postId = ReportedPosts.postId) AS 'userReported'
	FROM Posts p
	WHERE p.postId = '$pid' AND p.isHidden = false;";
	$posts = mysqli_query($conn, $query);

// Get comments
	$query = "SELECT c.*
	FROM Comments c
 	WHERE c.parentPostId = '$pid';";
	$comments = mysqli_query($conn, $query);

	// close database connection
	mysqli_close($conn);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<script type = "text/javascript"  src = "java1.js" ></script>
	<script type = "text/javascript"  src = "report.js" ></script>
	<script type = "text/javascript"  src = "delete.js" ></script>
	<title>Comment</title>
</head>
<body class="allPages">
	<div class="header">
		<h1>
			<a href="<?=SIDEBAR_VIEW_POSTS?>" class="homeLink">
				<img src="blank.jpg" class="placeHolder" alt="img"></img> <?php echo WEBSITE_NAME; ?>
			</a>
		</h1>
	</div>
	<div class="sideBar">
		<br/>
		<a class="buttons" href="<?php echo SIDEBAR_VIEW_POSTS; ?>">View Wall</a>
		<a class="buttons" href="<?php echo SIDEBAR_CREATE_POSTS; ?>">New Post</a>
		<a class="buttons" href="<?php echo SIDEBAR_VIEW_NOTES; ?>">View Notes</a>
		<a class="buttons" href="<?php echo SIDEBAR_ADMIN; ?>">Admin</a>
		<a class="buttons" href="<?php echo WEBSITE_LOGOUT; ?>">Logout</a>
	</div>
	<div class="largeSec">
		<button onclick="history.go(-1);">Go Back </button>
		<br/>
		<?php if (isset($posts) && $post = mysqli_fetch_assoc($posts)) {
			$userReportedPost = $post["userReported"];
			$reportButtonClass = $userReportedPost ? "reportButtonPressed" : "reportButton";
			$reportButtonText = $userReportedPost ? "Post Reported" : "Report Post";
		?>
		<div class="wallPost">
			<?php if(!is_null($post['uploadedFile'])){
					$postFile = USER_IMAGE_UPLOAD_DIRECTORY . $post["uploadedFile"];
			?>
					<img src="<?php echo $postFile; ?>" class="wallImg" alt="img">
			<?php } ?>
			<p class="wallText"><?php echo $post["text"];?></p>
			<p class="p3">Posted Anonymously at <?php echo $post['timestamp'];?></p>
			<button id="report_<?php echo $post["postId"];?>" class="<?php echo $reportButtonClass;?>"><?php echo $reportButtonText;?></button>
			<?php if ($_SESSION['isAdmin'] || $uid == $post["creatorId"]) { ?>
			<button id="delete_<?php echo $post["postId"];?>" class="deleteButton">Delete</button>
			<?php } ?>
		</div>
		<?php } ?>
		<?php while ($comment = mysqli_fetch_assoc($comments)) { ?>
		<div class="postComment">
			<p class="wallText"><?php echo $comment['text'];?></p>
			<p class="p3"> Posted Anonymously at <?php echo $comment['timestamp'];?></p>
		</div>
		<?php } ?>
		<form action="commented.php" method="POST" id="commentForm">
			<fieldset class="largeColorsec">
				<legend>Post a comment</legend>
				<textarea name="comment1" id="comment1" rows="3" cols="50"></textarea><br></br>
				<span class="errorMsg" id="comment1Error"></span>
				<p>
					<?php
					echo '<input type="hidden" name="thePostId" value="';
					echo $_GET["a"];
					echo '">';
					?>
					<input type="submit" value="Submit"/>
					<input type="reset" value="Reset"/>
				</p>
			</fieldset>
		</form>
	</div>
	<div class="footer">
		<p class="p2">2015 Department of Computer Science CS 215</p>
	</div>
</body>
<script type = "text/javascript"  src = "comment.js" ></script>
</html>
