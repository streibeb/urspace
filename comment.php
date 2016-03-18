<?php
include_once("config.php");
//start session
session_start();
// if user not logged in, redirect to homepage
if(!isset($_SESSION['login_user']))
{
	header('Location: login.html');

}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>

<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
<script type = "text/javascript"  src = "java1.js" >
</script>
<title>Comment</title>
</head>


<body class="allPages">
<div class="header">
<h1>
<a href="index.php" class="homeLink">
<img src="blank.jpg" class="placeHolder" alt="img"></img> FakeBook
</a>
</h1>
</div>

<div class="sideBar">
<br></br>
<a class="buttons" href="index.php">View Posts</a>
<a class="buttons" href="post.php">New Post</a>
<p class="blankButton">Search</p>
<a class="buttons" href="logout.php">Logout</a>
<br></br>
<img src="advertisment1.jpg" class="ad" alt="ad1"></img>
<img src="advertisment2.jpg" class="ad" alt="ad2"></img>
<img src="advertisment3.jpg" class="ad" alt="ad3"></img>
<img src="advertisment4.jpg" class="ad" alt="ad4"></img>
<img src="advertisment5.jpg" class="ad" alt="ad5"></img>

</div>

<div class="largeSec">
<?php
// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

$query = 'SELECT Post.* FROM Post WHERE Post.postId ='.$_GET["a"].';';
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);



echo '<button onclick="history.go(-1);">Go Back </button>';
echo '<br></br>';

echo '<div class="wallPost">';
if($row['uploadedFile'] != null){
	echo'<div><img src="';
	echo $row['uploadedFile'];
	echo '" class="wallImg" alt="img"></img></div>';
}
echo '<p class="wallText">';
echo $row['text'];
echo '<p class="p3"> Posted Anonymously at ';
echo $row['timestamp'];
echo '</p>';
echo '</div>';



$query = 'SELECT Comment.* FROM Comment WHERE Comment.parentPostId ='.$_GET["a"].';';
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result))
{
	echo '<div class="postComment">';
	echo '<p class="wallText">';
	echo $row['text'];
	echo '<p class="p3"> Posted Anonymously at ';
	echo $row['timestamp'];
	echo '</p>';
	echo '</div>';
	
}




	// close database connection
	mysqli_free_result($result);
mysqli_close($conn);





?>
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
<script type = "text/javascript"  src = "comment.js" >
	</script>
</html>
