
<?php
include_once("config.php");
// include function to add hashtags
include 'bonus.php';
include_once("config.php");


//create json variable
$sResp = array();



// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}




// perform database query
$result = mysqli_query($conn, "SELECT Post.*,
  (SELECT COUNT(commentID)
  FROM Comment
  WHERE parentPostId = postId) as 'numOfComments'
FROM Post
WHERE Post.postId > ".$_GET['latestPost']."
ORDER BY postId DESC;");


 if($result !=  FALSE){
// loop through converting data into json object
while($row = mysqli_fetch_assoc($result)){


			$sRow["text"]=bonusMarks(htmlspecialchars($row['text']));
			$sRow["uploadedFile"]=$row["uploadedFile"];
			$sRow["postId"]=$row["postId"];
			$sRow["timestamp"]=$row["timestamp"];
			$sRow["numOfComments"]=$row["numOfComments"];
			$sResp[] = $sRow;

}
//send json object to javascript for print
 }
echo json_encode($sResp);

	// close database connection
	 if($result !=  FALSE)
	mysqli_free_result($result);

mysqli_close($conn);
?>
