<?php
include_once("config.php");
//start session
session_start();

$sResp = array();

// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}


// get reposters first and last name
$result = mysqli_query($conn, "SELECT * FROM users WHERE Email ='".$_SESSION['login_user']."';");
$row = mysqli_fetch_assoc($result);
$reposterName = $row['FirstName']." ".$row['LastName'];

//get SQL data from original post that is being reposted
$result = mysqli_query($conn, "SELECT * from posts WHERE PostNum =".$_GET['PostNum'].";");
$row = mysqli_fetch_assoc($result);



//check to make sure this user has not already reposted this
$result2 = mysqli_query($conn, "SELECT Reposter, OriginPost FROM posts;");
$continue = true;
while($row2 = mysqli_fetch_assoc($result2))
{
	if($row2['Reposter'] == $reposterName && $_GET['PostNum'] == $row2['OriginPost'])
	{
	$continue = false;
	$sResp = $row['Reposts'];
	}
}

if($continue){



// only continue if user is not trying to repost his own posts
if($row['UserEmail'] != $_SESSION['login_user']){

//copy relevent data into new post string setting reposts value to -1
if(isset($row['ImageLocation']) && isset($row['Link']))
$sql = "INSERT INTO posts (UserEmail,comments,ImageLocation,Link,Reposts,Reposter,OriginPost) VALUES ('".$row['UserEmail']."','".$row['Comments']."','".$row['ImageLocation']."','".$row['Link']."',-1,'".$reposterName."','".$_GET['PostNum']."'); ";
elseif(isset($row['ImageLocation']) && !isset($row['Link']))
$sql = "INSERT INTO posts (UserEmail,comments,ImageLocation,Reposts,Reposter,OriginPost) VALUES ('".$row['UserEmail']."','".$row['Comments']."','".$row['ImageLocation']."',-1,'".$reposterName."','".$_GET['PostNum']."'); ";
elseif(!isset($row['ImageLocation']) && isset($row['Link']))
$sql = "INSERT INTO posts (UserEmail,comments,Link,Reposts,Reposter,OriginPost) VALUES ('".$row['UserEmail']."','".$row['Comments']."','".$row['Link']."',-1,'".$reposterName."','".$_GET['PostNum']."'); ";
else
$sql = "INSERT INTO posts (UserEmail,comments,Reposts,Reposter,OriginPost) VALUES ('".$row['UserEmail']."','".$row['Comments']."',-1,'".$reposterName."','".$_GET['PostNum']."'); ";

//upload new repost
mysqli_query($conn, $sql);

//update repost count
$newPostCount = $row['Reposts'] + 1;
mysqli_query($conn, "UPDATE posts SET Reposts = ".$newPostCount." WHERE PostNum = ".$_GET['PostNum'].";");

//update repost count on search page

$sResp = $newPostCount;
}
else
	$sResp = $row['Reposts'];
}

echo json_encode($sResp);

// close database connection
mysqli_close($conn);


?>
