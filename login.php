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
$sql = "SELECT *
FROM Users
WHERE email = '$email' AND password = '$password';";
$result = mysqli_query($conn, $sql);

$success = false;
$redirect = 'index.html';

if (mysqli_num_rows($result) > 0) {
	// Store Session Data
	$row = mysqli_fetch_assoc($result);
	$_SESSION['login_user']= $row['userId'];
	$redirect = 'wall.php';
	$success = true;
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
		<?php if (success) { ?>
			Login Successful.
			<br/>
			Redirecting...
			<?php } else { ?>
				Invalid username and/or password.
				<br/>
				Redirecting...
				<?php } ?>
			</body>
			</html>
