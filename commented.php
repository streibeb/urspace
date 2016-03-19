<?php
//start session
session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">

<head>

	<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
	<title>Commented</title>
</head>
<body class="infoPage">

	<?php
	include_once("config.php");

	// Open database connection
	$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$pid = $_POST["thePostId"];
	$uid = $_SESSION['login_user'];
	$content = htmlspecialchars(addslashes(trim($_POST["comment1"])));
	$date = date('Y/m/d H:i:s', time());

	$sql = "INSERT INTO Comments (parentPostId, timestamp, creatorId, text)
	VALUES ('$pid', '$date', '$uid', '$content');";

	//upload comment to database
	if (mysqli_query($conn, $sql)) {
		echo "<br/>Comment successful!<br/>Redirecting...";
	} else { // if failed to add a new record:
		echo "<br/>Comment failed<br/>Redirecting...";
	}

	// close database connection
	mysqli_close($conn);

	//redirect
	echo ' <META HTTP-EQUIV="Refresh" Content="2; URL=comment.php?a=';
	echo $_POST["thePostId"];
	echo '">';
	exit();
	?>
</body>
</html>
