<?php
//start session
session_start();
// if user not logged in, redirect to homepage
if(!isset($_SESSION['login_user']))
{
	header('Location: index.html');

}
// include function to add hashtags
include 'bonus.php';

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>

	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<script type = "text/javascript"  src = "java1.js" >
	</script>
	<title>Search</title>
</head>


<body class="allPages">
	<div class="header">
		<h1>
			<a href="index.html" class="homeLink">
				<img src="blank.jpg" class="placeHolder" alt="img"></img> FakeBook
			</a>
		</h1>
	</div>

	<div class="sideBar">
		<br></br>
		<a class="buttons" href="wall.php">View Wall</a>
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


	<form action="search.php" method="GET" id="userSearchForm">
		<fieldset class="largeColorsec">
			<legend>Course Notes Search</legend>
			<class id=courseSelector>

				<div id="selectorOptions">
					Please select a subject:
					<select id=subjectSelection name=subjectSelection>
						<option value="Computer Science">Computer Science</option>
						<option value="Math">Math</option>
						<option value="Physics">Physics</option>
						<option value="Female Gender Studies">Female Gender Studies</option>
					</select>
				</div>

				<div id="selectorOptions">
					Please select a course:
					<select id=courseSelection name=courseSelection>
						<option value="MATH110">MATH110</option>
						<option value="CS310">CS310</option>
						<option value="ARTS200">ARTS200</option>
						<option value="CS110">CS110</option>
					</select>
				</div>

				<div id="selectorOptions">
					Please select a professor:
					<select id=courseSelection name=courseSelection>
						<option value="Sameria">Dr.Sameria</option>
						<option value="Gerhard">Dr.Gerhard</option>
						<option value="Hamilton">Dr.Hamilton</option>
						<option value="Hilderman">Dr.Hilderman</option>
					</select>
				</div>
			</div>
			<span class="errorMsg" id="search1error"></span>
			<p>
				<input type="submit" value="Submit"/>
				<input type="reset" value="Reset"/>
			</p>
		</fieldset>
	</form>

	<div class="largeSec">
		<?php
		include_once("config.php");
// if user has inputted any search
		if(isset($_GET['search1']) || isset($_GET['search2']))
		{
// determine current page to print data
			if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
			$start_from = ($page-1) * 10;


// Open database connection
			$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}

//cleanup database of null entries
			$result = mysqli_query($conn, "DELETE FROM posts WHERE UserEmail='';");

if(isset($_GET['search1'])){ // if user has inputted user search
// perform database query
	$result = mysqli_query($conn, "SELECT users.FirstName, users.LastName,users.Email, posts.UserEmail,
		posts.Comments, posts.ImageLocation, posts.Link, posts.CurrTime, posts.PostNum, posts.Reposts, posts.Reposter, posts.VoteCounter FROM users, posts
		WHERE users.Email = posts.UserEmail AND (users.Email LIKE '%".$_GET['search1']."%' OR users.FirstName LIKE
		'%".$_GET['search1']."%' OR users.LastName LIKE '%".$_GET['search1']."%') ORDER BY PostNum DESC LIMIT ".$start_from.",10;");

// get total number of posts in database
	$rs_result = mysqli_query($conn,"SELECT users.FirstName, users.LastName,users.Email, posts.UserEmail,
		posts.Comments, posts.ImageLocation, posts.Link, posts.CurrTime, posts.PostNum FROM users, posts
		WHERE users.Email = posts.UserEmail AND (users.Email LIKE '%".$_GET['search1']."%' OR users.FirstName LIKE
		'%".$_GET['search1']."%' OR users.LastName LIKE '%".$_GET['search1']."%') ORDER BY PostNum DESC;");
	$num_rows = mysqli_num_rows($rs_result);
	$total_pages = ceil($num_rows / 10);

}elseif(isset($_GET['search2'])){ // if user has inputted text search
// perform database query
	$result = mysqli_query($conn, "SELECT users.FirstName, users.LastName,users.Email, posts.UserEmail,
		posts.Comments, posts.ImageLocation, posts.Link, posts.CurrTime, posts.PostNum, posts.Reposts, posts.Reposter, posts.VoteCounter FROM users, posts
		WHERE users.Email = posts.UserEmail AND (Comments LIKE '%".$_GET['search2']."%') ORDER BY PostNum DESC LIMIT ".$start_from.",10;");

// get total number of posts in database
	$rs_result = mysqli_query($conn,"SELECT users.FirstName, users.LastName,users.Email, posts.UserEmail,
		posts.Comments, posts.ImageLocation, posts.Link, posts.CurrTime, posts.PostNum FROM users, posts
		WHERE users.Email = posts.UserEmail AND (Comments LIKE '%".$_GET['search2']."%') ORDER BY PostNum DESC;");
	$num_rows = mysqli_num_rows($rs_result);
	$total_pages = ceil($num_rows / 10);
}
}



if(isset($result))
{
// loop through printing out the full posts
	while($row = mysqli_fetch_assoc($result)){

	if($row['Reposts'] != -1){ // only display original content, not reposts
		echo '<div class="wallPost">';
		if($row['ImageLocation'] != NULL)
			echo '<div><img src="'.$row['ImageLocation'].'" class="wallImg" alt="img"></img></div>';
		echo '<h2>'.$row['FirstName'].' '.htmlspecialchars($row['LastName']).'</h2>';
		echo '<p class="wallText">'.bonusMarks(htmlspecialchars($row['Comments']));
		if($row['Link'] != NULL)
			echo '<br/> <a href="'.$row['Link'].'">'.htmlspecialchars($row['Link']).'</a>';
		echo '</p>';
		echo '<p class="p3"> Posted '.$row['CurrTime'].' by '.htmlspecialchars($row['FirstName']);
		echo ' - <button type="button" class="repostLink" id="repost'.$row['PostNum'].'" value = "'.$row['PostNum'].'">Repost</button> Reposted:<span id="postCounter'.$row['PostNum'].'">'.$row['Reposts'].'</span>';
		echo ' <button type="button" class="likeButton" value = "'.$row['PostNum'].'"><span id="like'.$row['PostNum'].'">Like</span></button>';
		echo '<button type="button" class="dislikeButton" value = "'.$row['PostNum'].'"><span id="dislike'.$row['PostNum'].'">Dislike</span></button>';
		echo ' Total Votes: <span id="voteCounter'.$row['PostNum'].'">';
		if($row['VoteCounter'] > 0)
			echo '+';
		echo $row['VoteCounter'].'</span>';
		echo '</p>';
		echo '</div>';
	}
}

// CALL JS FUNCTION TO ADD EVENT LISTENTERS TO NEWLY CREATED POSTS
echo '<script type="text/javascript">searchEventListeners(); </script>';



echo "<p> Jump to page: ";
// create links to pages of posts if user is on search1
if(isset($_GET['search1']))
{
	for ($i=1; $i<=$total_pages; $i++) {
		echo "<a href='search.php?page=".$i."&search1=".$_GET['search1']."'>".$i."</a> ";
	}
}

// create links to pages of posts if user is on search2
if(isset($_GET['search2']))
{
	for ($i=1; $i<=$total_pages; $i++) {
		echo "<a href='search.php?page=".$i."&search2=".$_GET['search2']."'>".$i."</a> ";
	}
}
echo "</p>";

	// close database connection
mysqli_free_result($result);
mysqli_close($conn);



}

?>
</div>
<div class="footer">
	<p class="p2">2015 Department of Computer Science CS 215</p>
</div>

<script type = "text/javascript"  src = "search1.js" >
</script>
</body>
</html>
