<?php
include_once("config.php");
include_once("include.php");
//start session
session_start();
// if user not logged in, redirect to homepage
checkUserSession();

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
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> <!-- This is the link for bootstrap !-->
	<script type = "text/javascript"  src = "scripts/java1.js" ></script>
	<script type = "text/javascript"  src = "scripts/report.js" ></script>
	<script type = "text/javascript"  src = "scripts/delete.js" ></script>
	<title>Comment</title>
</head>
<body class="allPages">
	<div class="container-fluid"> <!-- This is the container div for the page; it is flued so it spands the viewport !-->
		<div class="row"> <!-- Header row !-->
			<div class="col-xs-12">
				<div class="header">
					<h1>
						<a href="<?=SIDEBAR_VIEW_POSTS?>" class="homeLink">
							<img src="logo.png" class="placeHolder" alt="img"></img> <?php echo WEBSITE_NAME; ?>
						</a>
					</h1>
				</div>
			</div>
		</div>

		<div class="row row-eq-height contentRow"> <!-- Content Row !-->
			<div class="col-xs-2 sideBarCol"> <!-- sidebar column !-->
				<div class="sideBar">
					<br/>
					<a class="buttons" href="<?php echo SIDEBAR_VIEW_POSTS; ?>">View Wall</a>
					<a class="buttons" href="<?php echo SIDEBAR_CREATE_POSTS; ?>">Create Post</a>
					<a class="buttons" href="<?php echo SIDEBAR_VIEW_NOTES; ?>">View Notes</a>
					<a class="buttons" href="<?php echo SIDEBAR_CREATE_NOTES; ?>">Create Notes</a>
					<?php if (isAdmin($uid)) { ?><a class="buttons" href="<?php echo SIDEBAR_ADMIN; ?>">Admin</a><?php } ?>
					<a class="buttons" href="<?php echo SIDEBAR_LOGOUT; ?>">Logout</a>
				</div>
			</div>

			<div class="col-xs-10 col-md-6 col-md-offset-2"> <!-- content column !-->
				<div class="largeSec">
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
					<div class="signupSection">
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
				</div>
			</div>
		</div>
		<div class="row"> <!-- Footer Row !-->
			<div class="col-xs-12">
				<div class="footer">
					<p class="p2">UR Space Copyright © 2016 All Rights Reserved</p>
				</div>
			</div>
		</div>
	</div>
</body>
<script type = "text/javascript"  src = "scripts/comment.js" ></script>
</html>
