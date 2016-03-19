<?php
//start session
session_start();
include_once("config.php");

$sResp = array();

// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
// get the total votes
$result = mysqli_query($conn, "SELECT VoteCounter FROM Postss WHERE PostNum =".$_GET['postNum'].";");
$row = mysqli_fetch_assoc($result);
$totalVotes = $row['VoteCounter'];

// get vote information
$result = mysqli_query($conn, "SELECT * FROM votes WHERE PostNum = ".$_GET['postNum']." AND UserEmail = '".$_SESSION['login_user']."';");

if(mysqli_num_rows($result) != 0)
$row = mysqli_fetch_assoc($result);

//if user has not previously voted
if(mysqli_num_rows($result) == 0)
{
	// add database entry for his vote
	mysqli_query($conn, "INSERT INTO votes VALUES (".$_GET['postNum'].",'".$_SESSION['login_user']."','".$_GET['vote']."');");


	// change total votes
	if($_GET['vote'] == "Like"){
	$totalVotes++;
	$sResp = "Liked!";
	}elseif($_GET['vote'] == "Dislike"){
	$totalVotes--;
	$sResp = "Disliked!";
	}


	mysqli_query($conn, "UPDATE posts SET VoteCounter = ".$totalVotes." WHERE PostNum = ".$_GET['postNum'].";");

}elseif($row['Vote'] == "Like" && $_GET['vote'] == "Like") // user is pressing like again
{
	//delete vote in votes database
	mysqli_query($conn, "DELETE FROM votes WHERE PostNum = ".$_GET['postNum']." AND UserEmail = '".$_SESSION['login_user']."';");
	$sResp = "Unliked";
	//change total votes for post
	$totalVotes--;
	mysqli_query($conn, "UPDATE posts SET VoteCounter = ".$totalVotes." WHERE PostNum = ".$_GET['postNum'].";");
}elseif($row['Vote'] == "Dislike" && $_GET['vote'] == "Dislike") // user is pressing dislike again
{
	//delete vote in votes database
	mysqli_query($conn, "DELETE FROM votes WHERE PostNum = ".$_GET['postNum']." AND UserEmail = '".$_SESSION['login_user']."';");
	$sResp = "Undisliked";
	//change total votes for post
	$totalVotes++;
	mysqli_query($conn, "UPDATE posts SET VoteCounter = ".$totalVotes." WHERE PostNum = ".$_GET['postNum'].";");
}elseif($row['Vote'] == "Dislike" && $_GET['vote'] == "Like") // user is pressing like from dislike
{
	//change vote in votes database
	mysqli_query($conn, "UPDATE votes SET Vote = 'Like' WHERE PostNum = ".$_GET['postNum']." AND UserEmail = '".$_SESSION['login_user']."';");
	$sResp = "Liked!";
	//change total votes for post
	$totalVotes++;
	$totalVotes++;
	mysqli_query($conn, "UPDATE posts SET VoteCounter = ".$totalVotes." WHERE PostNum = ".$_GET['postNum'].";");
}elseif($row['Vote'] == "Like" && $_GET['vote'] == "Dislike") // user is pressing dislike from like
{
	//change vote in votes database
	mysqli_query($conn, "UPDATE votes SET Vote = 'Dislike' WHERE PostNum = ".$_GET['postNum']." AND UserEmail = '".$_SESSION['login_user']."';");
	$sResp = "Disliked!";
	//change total votes for post
	$totalVotes--;
	$totalVotes--;
	mysqli_query($conn, "UPDATE posts SET VoteCounter = ".$totalVotes." WHERE PostNum = ".$_GET['postNum'].";");
}

echo json_encode($sResp);

// close database connection
mysqli_close($conn);


?>
