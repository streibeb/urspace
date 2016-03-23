<?php
session_start();
include_once("config.php");
// Open database connection
$conn = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST["uName"];
$password = $_POST["pWord"];

// perform database query
$sql = "SELECT u.*,
(SELECT COUNT(1) FROM Administrators a WHERE a.userId = u.userId) as 'isAdmin'
FROM Users u
WHERE email = '$email' AND password = '$password';";
$result = mysqli_query($conn, $sql);

$success = false;
$redirect = 'login.php';

if (mysqli_num_rows($result) > 0) {
	// Store Session Data
	$row = mysqli_fetch_assoc($result);
	$_SESSION['login_user'] = $row['userId'];
	$_SESSION['isAdmin'] = $row['isAdmin'];
	$success = true;
	$isBanned = $row['isBanned'];
	if ($success && !$isBanned) {
		$redirect = SIDEBAR_VIEW_POSTS;
	}
}

mysqli_close($conn);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<META HTTP-EQUIV="Refresh" Content="3; URL=<?php echo $redirect; ?>">
	<html xmlns = "http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" type="text/css" href="mystyle.css"></link>
		<title>Login</title>
	</head>
	<body class="infoPage">
		<?php

		if ($success) {
			if ($isBanned) {
				session_destroy();
				echo "You have been banned!";
				echo "<br/>";
				echo "Redirecting...";
			} else {
				echo "Login Successful.";
				echo "<br/>";
				echo "Redirecting...";
			}
		 } else {
			echo "Invalid username and/or password.";
			echo "<br/>";
			echo "Redirecting...";
		}

		?>
	</body>
</html>
