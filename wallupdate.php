
<?php

// include function to add hashtags
include 'bonus.php';



//create json variable
$sResp = array();



// Open database connection
$conn = mysqli_connect("localhost", "mantta2t", "winter15", "mantta2t");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

//cleanup database of null entries
$result = mysqli_query($conn, "DELETE FROM posts WHERE UserEmail='';");


// perform database query
$result = mysqli_query($conn, "SELECT users.FirstName, users.LastName,users.Email, posts.UserEmail,
 posts.Comments, posts.ImageLocation, posts.Link, posts.CurrTime, posts.PostNum, posts.Reposts, posts.Reposter, posts.VoteCounter FROM users, posts
 WHERE (users.Email = posts.UserEmail) AND (PostNum > ".$_GET['latestPost'].") ORDER BY PostNum DESC;");


 
// loop through converting data into json object
while($row = mysqli_fetch_assoc($result)){
			

			
			$sRow["FirstName"]= htmlspecialchars($row['FirstName']);
			$sRow["LastName"]= htmlspecialchars($row['LastName']);
			$sRow["Comments"]=bonusMarks(htmlspecialchars($row['Comments']));
			$sRow["ImageLocation"]=$row["ImageLocation"];
			$sRow["Link"]=$row['Link'];
		    $sRow["CurrTime"]= $row['CurrTime'];
			$sRow["PostNum"]= $row['PostNum'];
			$sRow["Reposts"]= $row['Reposts'];
			$sRow["Reposter"]= $row['Reposter'];
			$sRow["VoteCounter"]= $row['VoteCounter'];
			$sResp[] = $sRow;
	
}
//send json object to javascript for print
echo json_encode($sResp);

	// close database connection
	mysqli_free_result($result);
mysqli_close($conn);
?>


