<?php
//start session
session_start();
include_once("config.php");

// if user not logged in, redirect to homepage
if(!isset($_SESSION['login_user'])) {
	header('Location: index.php');
}

$uid = $_SESSION['login_user'];

//create json variable
$sResp = array();

// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT COUNT(postId) as 'count'
FROM Posts
WHERE isHidden = false;";
$result = mysqli_query($conn, $query);
$totalPages = 0;
if ($r = mysqli_fetch_assoc($result)){
    $totalPages = ceil($r["count"]/POSTS_PER_PAGE);
}

$postsPerPage = POSTS_PER_PAGE;

$pageBegin = 0;
if (isset($_GET["page"])) {
    $pageNum = $_GET["page"];
    if ($pageNum == 0) {
        $pageNum = 1;
    } else if ($pageNum > $totalPages) {
        $pageNum = $totalPages;
    }
    $pageBegin = ($pageNum-1) * POSTS_PER_PAGE;
} else {
    $pageNum = 1;
}

$query = "SELECT p.*,
	(SELECT COUNT(commentID)
	FROM Comments c
	WHERE parentPostId = postId) as 'numOfComments'
	FROM Posts p
	WHERE isHidden = false
	ORDER BY timestamp DESC
	LIMIT $pageBegin, $postsPerPage;";

// perform database query
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    echo mysqli_error($db);
}

echo mysqli_error($conn);
mysqli_close($conn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> <!-- This is the link for bootstrap !-->
	<!--<script type = "text/javascript"  src = "java1.js" ></script>-->
	<script type = "text/javascript"  src = "report.js" ></script>
	<script type = "text/javascript"  src = "delete.js" ></script>
<title>Public Wall</title>
</head>
<body class="allPages">
	<div class="container-fluid"> <!-- This is the container div for the page; it is flued so it spands the viewport !-->
		<div class="row"> <!-- Header row !-->
			<div class="col-xs-12">
				<div class="header">
					<h1>
						<a href="<?=SIDEBAR_VIEW_POSTS?>" class="homeLink">
							<img src="blank.jpg" class="placeHolder" alt="img"></img> <?php echo WEBSITE_NAME; ?>
						</a>
					</h1>
				</div>
			</div>
		</div>
		<div class="row row-eq-height"> <!-- Content row!-->
			<div class="col-xs-2 sideBarCol"> <!--Sidebar column !-->
				<div class="sideBar">
					<br/>
					<p class="blankButton">View Posts</p>
					<a class="buttons" href="<?php echo SIDEBAR_CREATE_POSTS; ?>">New Post</a>
					<a class="buttons" href="<?php echo SIDEBAR_VIEW_NOTES; ?>">View Notes</a>
					<a class="buttons" href="<?php echo SIDEBAR_ADMIN; ?>">Admin</a>
					<a class="buttons" href="<?php echo SIDEBAR_LOGOUT; ?>">Logout</a>
					<br></br>
				</div>
			</div>
			<div class="col-xs-10"> <!-- content column !-->
				<div class="largeSec">
					<div class="wallOptions">
						Disable automatic updating: 	<input type="checkbox" id="dynamicUpdate"></input>
					</div>
					<div id="wallArea">
					<?php while($row = mysqli_fetch_assoc($result)) {
						$userReportedPost = $post["userReported"];
						$reportButtonClass = $userReportedPost ? "reportButtonPressed" : "reportButton";
						$reportButtonText = $userReportedPost ? "Post Reported" : "Report Post";

						$postContent = htmlspecialchars($row['text']);
						$image = false;
						if (!is_null($row["uploadedFile"])) {
							$image = true;
							$postFile = USER_IMAGE_UPLOAD_DIRECTORY . $row["uploadedFile"];
						}
						$postId = $row["postId"];
						$postTimestamp = $row["timestamp"];
						$postNumComments = $row["numOfComments"];
					?>
						<div class="wallPost" id="post<?=$pid?>">
						<div>
							<?php if ($image) { ?>
							<img src="<?php echo $postFile; ?>" class="wallImg" alt="img">
							<?php } ?>
						</div>
						<p class="wallText"><?php echo $postContent; ?></p>
						<p class="p3">
							Posted Anonymously at <?php echo $postTimestamp; ?> -
							<a href="comment.php?a=<?php echo $postId;?>">
								<span id="commentCounter<?php echo $postId;?>">
									<?php echo $postNumComments;
                  if ($postNumComments != 1) { ?>
                      Comments
                  <?php } else { ?>
                      Comment
                	<?php } ?>
								</span>
							</a>
						</p>
						<button id="report_<?php echo $postId;?>" class="<?php echo $reportButtonClass;?>"><?php echo $reportButtonText;?></button>
						<?php if ($_SESSION['isAdmin'] || $uid == $row["creatorId"]) { ?>
						<button id="delete_<?php echo $postId;?>" class="deleteButton">Delete</button>
						<?php } ?>
						</div>
					<?php } ?>
					</div>
					<div id="linksArea" class="pageSelect">
					<?php if ($pageNum > 1) { ?>
               <a href="<?=SIDEBAR_VIEW_POSTS?>?page=<?=$pageNum-1?>"><button id="lastPage">&lt;&lt;</button></a>
           <?php } ?>
           <span id="pageIndicator"><?=$pageNum?></span>
           <?php if ($pageNum < $totalPages) { ?>
               <a href="<?=SIDEBAR_VIEW_POSTS?>?page=<?=$pageNum+1?>"><button id="nextPage">&gt;&gt;</button></a>
           <?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="footer">
					<p class="p2">2015 Department of Computer Science CS 215</p>
				</div>
			</div>
		</div>
	</div>
	<script type = "text/javascript"  src = "wall1.js" ></script>
</body>
</html>
